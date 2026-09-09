/**
 * SeaHivez Google Map — Map ID + AdvancedMarkerElement (direct loader, no importLibrary).
 *
 * @package seahivez-theme
 */

const LOG_PREFIX = '[SeaHivez Map]';
const MAPS_CALLBACK_NAME = 'initSeaHivezMap';

const MAP_OPTIONS = {
	zoom: 16,
	disableDefaultUI: true,
	zoomControl: true,
	mapTypeControl: false,
	streetViewControl: false,
	fullscreenControl: false,
	gestureHandling: 'cooperative',
	clickableIcons: false,
};

/**
 * @param {string} message
 * @param {unknown} [details]
 */
function logMapError( message, details ) {
	if ( details !== undefined ) {
		console.error( LOG_PREFIX, message, details );
		return;
	}

	console.error( LOG_PREFIX, message );
}

/**
 * @param {string} message
 * @param {unknown} [details]
 */
function logMapWarn( message, details ) {
	if ( details !== undefined ) {
		console.warn( LOG_PREFIX, message, details );
		return;
	}

	console.warn( LOG_PREFIX, message );
}

/**
 * @returns {boolean}
 */
function isGoogleMapsReady() {
	return Boolean(
		window.google?.maps?.Map &&
		window.google?.maps?.marker?.AdvancedMarkerElement
	);
}

/**
 * Flush pending loadGoogleMaps() resolvers.
 *
 * @param {'resolve'|'reject'} action
 * @param {unknown} [reason]
 */
function flushMapsLoaders( action, reason ) {
	const queue = window.__seahivezMapsResolvers || [];

	window.__seahivezMapsResolvers = [];

	queue.forEach( ( entry ) => {
		if ( 'resolve' === action ) {
			entry.resolve( window.google.maps );
		} else {
			entry.reject( reason );
		}
	} );
}

/**
 * Global Maps bootstrap callback — must exist before the script tag is injected.
 */
function registerSeaHivezMapsCallback() {
	if ( window.__seahivezMapsCallbackRegistered ) {
		return;
	}

	window.__seahivezMapsCallbackRegistered = true;

	window[ MAPS_CALLBACK_NAME ] = function () {
		if ( ! isGoogleMapsReady() ) {
			const error = new Error(
				'Google Maps callback fired but Map or AdvancedMarkerElement is unavailable.'
			);

			logMapError( error.message, {
				version: window.google?.maps?.version || null,
				hasMap: Boolean( window.google?.maps?.Map ),
				hasMarker: Boolean( window.google?.maps?.marker?.AdvancedMarkerElement ),
			} );

			flushMapsLoaders( 'reject', error );
			return;
		}

		window.__seahivezMapsApiReady = true;
		flushMapsLoaders( 'resolve' );
	};
}

/**
 * @returns {NodeListOf<HTMLScriptElement>}
 */
function getGoogleMapsScriptTags() {
	return document.querySelectorAll( 'script[src*="maps.googleapis.com/maps/api/js"]' );
}

/**
 * Singleton loader — one script tag, callback-based bootstrap.
 *
 * @param {string} apiKey
 * @returns {Promise<typeof google.maps>}
 */
function loadGoogleMaps( apiKey ) {
	registerSeaHivezMapsCallback();

	if ( isGoogleMapsReady() ) {
		return Promise.resolve( window.google.maps );
	}

	if ( window.__seahivezMapsApiReady && window.google?.maps ) {
		return Promise.resolve( window.google.maps );
	}

	if ( window.__seahivezMapsPromise ) {
		return window.__seahivezMapsPromise;
	}

	window.__seahivezMapsPromise = new Promise( ( resolve, reject ) => {
		window.__seahivezMapsResolvers = window.__seahivezMapsResolvers || [];
		window.__seahivezMapsResolvers.push( { resolve, reject } );

		const existingScripts = getGoogleMapsScriptTags();
		const ownScript = document.querySelector( 'script[data-seahivez-maps-api="true"]' );

		if ( existingScripts.length > 0 && ! ownScript ) {
			logMapWarn(
				'Another Google Maps script is already on the page. SeaHivez requires libraries=marker and callback=initSeaHivezMap.',
				Array.from( existingScripts ).map( ( script ) => script.src )
			);
		}

		if ( ownScript ) {
			return;
		}

		if ( existingScripts.length > 0 ) {
			const foreignScript = existingScripts[ 0 ];

			if (
				foreignScript.src.includes( `callback=${ MAPS_CALLBACK_NAME }` ) &&
				foreignScript.src.includes( 'libraries=marker' )
			) {
				foreignScript.dataset.seahivezMapsApi = 'true';
				return;
			}
		}

		if ( existingScripts.length === 0 ) {
			const script = document.createElement( 'script' );
			script.dataset.seahivezMapsApi = 'true';
			script.async = true;
			script.src = `https://maps.googleapis.com/maps/api/js?key=${ encodeURIComponent( apiKey ) }&v=weekly&libraries=marker&loading=async&callback=${ MAPS_CALLBACK_NAME }`;
			script.onerror = () => {
				const error = new Error( 'Google Maps script tag failed to load.' );
				window.__seahivezMapsPromise = null;
				flushMapsLoaders( 'reject', error );
			};
			document.head.appendChild( script );
		}
	} );

	return window.__seahivezMapsPromise;
}

/**
 * Round branded marker for AdvancedMarkerElement.
 *
 * @returns {HTMLElement}
 */
function createMarkerContent() {
	const marker = document.createElement( 'div' );
	marker.className = 'seahivez-map-marker';
	marker.setAttribute( 'role', 'img' );
	marker.innerHTML = '<span class="seahivez-map-marker__icon" aria-hidden="true">⚓</span>';

	return marker;
}

/**
 * @param {HTMLElement} root
 */
function showMapLoading( root ) {
	root.classList.add( 'is-loading' );
	root.classList.remove( 'is-ready', 'is-fallback' );

	const loading = root.querySelector( '[data-map-loading]' );
	const fallback = root.querySelector( '[data-map-fallback]' );

	if ( loading ) {
		loading.hidden = false;
	}
	if ( fallback ) {
		fallback.hidden = true;
	}
}

/**
 * @param {HTMLElement} root
 * @param {string} reason
 * @param {unknown} [details]
 */
function showMapFallback( root, reason, details ) {
	const loading = root.querySelector( '[data-map-loading]' );
	const canvas = root.querySelector( '[data-map-canvas]' );
	const fallback = root.querySelector( '[data-map-fallback]' );

	logMapError( reason, details );

	root.classList.add( 'is-fallback' );
	root.classList.remove( 'is-ready', 'is-loading' );

	if ( loading ) {
		loading.hidden = true;
	}
	if ( canvas ) {
		canvas.hidden = true;
	}
	if ( fallback ) {
		fallback.hidden = false;
	}
}

/**
 * @param {HTMLElement} root
 */
function showMapReady( root ) {
	const loading = root.querySelector( '[data-map-loading]' );
	const canvas = root.querySelector( '[data-map-canvas]' );
	const fallback = root.querySelector( '[data-map-fallback]' );

	root.classList.add( 'is-ready' );
	root.classList.remove( 'is-fallback', 'is-loading' );

	if ( loading ) {
		loading.hidden = true;
	}
	if ( canvas ) {
		canvas.hidden = false;
	}
	if ( fallback ) {
		fallback.hidden = true;
	}
}

/**
 * @param {string} title
 * @param {string} place
 * @param {string} mapsUrl
 * @returns {google.maps.InfoWindow}
 */
function createInfoWindow( title, place, mapsUrl ) {
	const openLink = mapsUrl
		? `<p style="margin:8px 0 0;"><a href="${ mapsUrl }" target="_blank" rel="noopener noreferrer" style="color:#0B1F3A;text-decoration:underline;">Open in Google Maps</a></p>`
		: '';

	return new google.maps.InfoWindow( {
		content: `
			<div style="font-family:Satoshi,Arial,sans-serif;padding:4px 2px;max-width:220px;color:#0B1F3A;">
				<strong style="display:block;font-size:14px;margin-bottom:2px;">${ title }</strong>
				<span style="font-size:13px;color:#5C6570;">${ place }</span>
				${ openLink }
			</div>
		`,
	} );
}

/**
 * Wait until the map reports idle (tiles rendered). Never rejects — hidden containers
 * and vector Map IDs can delay tile events without meaning the map failed.
 *
 * @param {google.maps.Map} map
 * @returns {Promise<void>}
 */
function waitForMapIdle( map ) {
	return new Promise( ( resolve ) => {
		let settled = false;

		const finish = () => {
			if ( settled ) {
				return;
			}

			settled = true;
			resolve();
		};

		const timeout = window.setTimeout( finish, 12000 );

		google.maps.event.addListenerOnce( map, 'idle', () => {
			window.clearTimeout( timeout );
			finish();
		} );

		google.maps.event.addListenerOnce( map, 'tilesloaded', () => {
			window.clearTimeout( timeout );
			finish();
		} );

		window.requestAnimationFrame( () => {
			google.maps.event.trigger( map, 'resize' );
		} );
	} );
}

/**
 * @param {HTMLElement} root
 */
async function initSingleMap( root ) {
	if ( root.dataset.mapInitialized === 'true' ) {
		return;
	}

	const canvas = root.querySelector( '[data-map-canvas]' );
	if ( ! canvas ) {
		showMapFallback( root, 'Map canvas element is missing.' );
		return;
	}

	const lat = parseFloat( root.dataset.lat || '' );
	const lng = parseFloat( root.dataset.lng || '' );
	const title = root.dataset.label || "S'Arenal Marina";
	const place = root.dataset.place || "S'Arenal, Mallorca";
	const mapsUrl = root.dataset.mapsUrl || '';
	const mapId = window.seahivezData?.mapsMapId || '';

	if ( Number.isNaN( lat ) || Number.isNaN( lng ) ) {
		showMapFallback( root, 'Invalid map coordinates.', { lat: root.dataset.lat, lng: root.dataset.lng } );
		return;
	}

	if ( ! mapId ) {
		showMapFallback( root, 'Missing Google Maps Map ID.', {
			mapsApiKeyPresent: Boolean( window.seahivezData?.mapsApiKey ),
		} );
		return;
	}

	if ( ! isGoogleMapsReady() ) {
		showMapFallback( root, 'Google Maps marker library is not available.' );
		return;
	}

	showMapLoading( root );

	const position = { lat, lng };

	try {
		canvas.hidden = false;

		const map = new google.maps.Map( canvas, {
			...MAP_OPTIONS,
			center: position,
			mapId,
		} );

		const marker = new google.maps.marker.AdvancedMarkerElement( {
			map,
			position,
			title,
			content: createMarkerContent(),
		} );

		const info = createInfoWindow( title, place, mapsUrl );
		marker.addListener( 'click', () => {
			info.open( { map, anchor: marker } );
		} );

		root.dataset.mapInitialized = 'true';
		showMapReady( root );

		waitForMapIdle( map ).then( () => {
			google.maps.event.trigger( map, 'resize' );
		} );
	} catch ( error ) {
		delete root.dataset.mapInitialized;
		showMapFallback( root, 'Map failed to initialize.', {
			mapId,
			position,
			error,
		} );
	}
}

/**
 * Initialize all SeaHivez map containers.
 */
export function initMap() {
	const roots = document.querySelectorAll( '[data-seahivez-map]' );

	if ( ! roots.length ) {
		return;
	}

	const apiKey = window.seahivezData?.mapsApiKey || '';
	const mapId = window.seahivezData?.mapsMapId || '';

	roots.forEach( ( root ) => {
		if ( root.dataset.mapInitialized !== 'true' ) {
			showMapLoading( root );
		}
	} );

	if ( ! apiKey ) {
		roots.forEach( ( root ) => {
			showMapFallback( root, 'Missing Google Maps API key.', { mapsMapId: mapId || null } );
		} );
		return;
	}

	loadGoogleMaps( apiKey )
		.then( async () => {
			for ( const root of roots ) {
				await initSingleMap( root );
			}
		} )
		.catch( ( error ) => {
			roots.forEach( ( root ) => {
				showMapFallback( root, 'Google Maps API failed to load.', {
					error,
					mapId: mapId || null,
					scriptCount: getGoogleMapsScriptTags().length,
				} );
			} );
		} );
}
