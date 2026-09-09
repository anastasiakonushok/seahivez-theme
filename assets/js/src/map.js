/**
 * SeaHivez Google Map — Map ID + AdvancedMarkerElement.
 *
 * @package seahivez-theme
 */

const LOG_PREFIX = '[SeaHivez Map]';

const MAP_OPTIONS = {
	zoom: 15,
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
 * Round branded marker (anchor icon) for AdvancedMarkerElement.
 *
 * @returns {HTMLElement}
 */
function createMarkerContent() {
	const marker = document.createElement( 'div' );
	marker.className = 'seahivez-map-marker';
	marker.setAttribute( 'role', 'img' );
	marker.innerHTML = `
		<svg class="seahivez-map-marker__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
			<path d="M12 3c-2.8 0-5 2.2-5 5.1 0 3.7 5 9.9 5 9.9s5-6.2 5-9.9C17 5.2 14.8 3 12 3Z" stroke="currentColor" stroke-width="1.6"/>
			<circle cx="12" cy="8.1" r="1.6" fill="currentColor"/>
			<path d="M8.5 19.5h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
			<path d="M10 17.2h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
		</svg>
	`;

	return marker;
}

/**
 * Load Google Maps JS API once (maps + marker libraries).
 *
 * @param {string} apiKey
 * @returns {Promise<{ mapsLib: google.maps.MapsLibrary, markerLib: google.maps.MarkerLibrary }>}
 */
function loadGoogleMapsApi( apiKey ) {
	if ( window.__seahivezMapsLibraries?.mapsLib && window.__seahivezMapsLibraries?.markerLib ) {
		return Promise.resolve( window.__seahivezMapsLibraries );
	}

	if ( window.__seahivezMapsPromise ) {
		return window.__seahivezMapsPromise;
	}

	window.__seahivezMapsPromise = new Promise( ( resolve, reject ) => {
		const existing = document.querySelector( 'script[data-seahivez-maps-api]' );

		if ( existing ) {
			existing.addEventListener( 'load', () => resolveLibraries( resolve, reject ), { once: true } );
			existing.addEventListener( 'error', () => reject( new Error( 'Google Maps script tag failed to load.' ) ), { once: true } );
			return;
		}

		const script = document.createElement( 'script' );
		script.dataset.seahivezMapsApi = 'true';
		script.src = `https://maps.googleapis.com/maps/api/js?key=${ encodeURIComponent( apiKey ) }&loading=async`;
		script.async = true;
		script.defer = true;
		script.onload = () => resolveLibraries( resolve, reject );
		script.onerror = () => reject( new Error( 'Google Maps script tag failed to load.' ) );
		document.head.appendChild( script );
	} );

	return window.__seahivezMapsPromise;
}

/**
 * @param {(value: { mapsLib: google.maps.MapsLibrary, markerLib: google.maps.MarkerLibrary }) => void} resolve
 * @param {(reason?: unknown) => void} reject
 */
async function resolveLibraries( resolve, reject ) {
	try {
		if ( ! window.google?.maps?.importLibrary ) {
			reject( new Error( 'google.maps.importLibrary is unavailable after script load.' ) );
			return;
		}

		const [ mapsLib, markerLib ] = await Promise.all( [
			window.google.maps.importLibrary( 'maps' ),
			window.google.maps.importLibrary( 'marker' ),
		] );

		if ( ! markerLib?.AdvancedMarkerElement ) {
			reject( new Error( 'AdvancedMarkerElement library is unavailable.' ) );
			return;
		}

		window.__seahivezMapsLibraries = { mapsLib, markerLib };
		resolve( window.__seahivezMapsLibraries );
	} catch ( error ) {
		reject( error );
	}
}

/**
 * @param {HTMLElement} root
 */
function showMapLoading( root ) {
	root.classList.add( 'is-loading' );
	root.classList.remove( 'is-ready', 'is-fallback' );

	const loading = root.querySelector( '[data-map-loading]' );
	const canvas = root.querySelector( '[data-map-canvas]' );
	const fallback = root.querySelector( '[data-map-fallback]' );

	if ( loading ) {
		loading.hidden = false;
	}
	if ( canvas ) {
		canvas.hidden = true;
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
 * @param {google.maps.Map} map
 * @returns {Promise<void>}
 */
function waitForTiles( map ) {
	return new Promise( ( resolve, reject ) => {
		const timeout = window.setTimeout( () => {
			reject( new Error( 'Map tiles did not load within 10 seconds.' ) );
		}, 10000 );

		map.addListener( 'tilesloaded', () => {
			window.clearTimeout( timeout );
			resolve();
		} );
	} );
}

/**
 * @param {HTMLElement} root
 * @param {{ mapsLib: google.maps.MapsLibrary, markerLib: google.maps.MarkerLibrary }} libraries
 */
async function initSingleMap( root, libraries ) {
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
		showMapFallback(
			root,
			'Missing Google Maps Map ID. Add SEAHIVEZ_GOOGLE_MAPS_MAP_ID to wp-config.php or environment.',
			{ mapsApiKeyPresent: Boolean( window.seahivezData?.mapsApiKey ) }
		);
		return;
	}

	showMapLoading( root );

	const position = { lat, lng };
	const { Map } = libraries.mapsLib;
	const { AdvancedMarkerElement } = libraries.markerLib;

	try {
		const map = new Map( canvas, {
			...MAP_OPTIONS,
			center: position,
			mapId,
		} );

		await waitForTiles( map );

		const marker = new AdvancedMarkerElement( {
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
	const roots = document.querySelectorAll( '[data-seahivez-map]:not([data-map-initialized])' );

	if ( ! roots.length ) {
		return;
	}

	const apiKey = window.seahivezData?.mapsApiKey || '';
	const mapId = window.seahivezData?.mapsMapId || '';

	roots.forEach( ( root ) => {
		root.dataset.mapInitialized = 'pending';
		showMapLoading( root );
	} );

	if ( ! apiKey ) {
		roots.forEach( ( root ) => {
			root.dataset.mapInitialized = 'false';
			showMapFallback(
				root,
				'Missing Google Maps API key. Set SEAHIVEZ_GOOGLE_MAPS_API_KEY in wp-config.php or server environment.',
				{ mapsMapId: mapId || null }
			);
		} );
		return;
	}

	loadGoogleMapsApi( apiKey )
		.then( async ( libraries ) => {
			for ( const root of roots ) {
				await initSingleMap( root, libraries );
			}
		} )
		.catch( ( error ) => {
			roots.forEach( ( root ) => {
				root.dataset.mapInitialized = 'false';
				showMapFallback( root, 'Google Maps API failed to load.', {
					error,
					mapId: mapId || null,
				} );
			} );
		} );
}
