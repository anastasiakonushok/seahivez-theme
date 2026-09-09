/**
 * SeaHivez Google Map — cloud Map ID with legacy style fallback.
 *
 * @package seahivez-theme
 */

/** @type {google.maps.MapTypeStyle[]} */
const SEA_HIVEZ_MAP_STYLES = [
	{ elementType: 'geometry', stylers: [ { color: '#F4F1EA' } ] },
	{ elementType: 'labels.text.fill', stylers: [ { color: '#5C6570' } ] },
	{ elementType: 'labels.text.stroke', stylers: [ { color: '#F4F1EA' } ] },
	{
		featureType: 'administrative',
		elementType: 'geometry.stroke',
		stylers: [ { color: '#D7D5CF' } ],
	},
	{
		featureType: 'administrative.land_parcel',
		elementType: 'labels',
		stylers: [ { visibility: 'off' } ],
	},
	{
		featureType: 'poi',
		stylers: [ { visibility: 'off' } ],
	},
	{
		featureType: 'poi.park',
		elementType: 'geometry',
		stylers: [ { color: '#E8E4DC' } ],
	},
	{
		featureType: 'road',
		elementType: 'geometry',
		stylers: [ { color: '#D7D5CF' } ],
	},
	{
		featureType: 'road',
		elementType: 'geometry.stroke',
		stylers: [ { color: '#C8C5BD' } ],
	},
	{
		featureType: 'road',
		elementType: 'labels.icon',
		stylers: [ { visibility: 'off' } ],
	},
	{
		featureType: 'road.highway',
		elementType: 'geometry',
		stylers: [ { color: '#C9C5BC' } ],
	},
	{
		featureType: 'transit',
		stylers: [ { visibility: 'off' } ],
	},
	{
		featureType: 'water',
		elementType: 'geometry',
		stylers: [ { color: '#D9E2E8' } ],
	},
	{
		featureType: 'water',
		elementType: 'labels.text.fill',
		stylers: [ { color: '#8A96A3' } ],
	},
];

const MAP_OPTIONS = {
	zoom: 15,
	disableDefaultUI: true,
	zoomControl: true,
	mapTypeControl: false,
	streetViewControl: false,
	fullscreenControl: false,
	gestureHandling: 'greedy',
	clickableIcons: false,
};

/**
 * Custom navy pin with gold center (SVG data URL).
 *
 * @returns {string}
 */
function getMarkerIconUrl() {
	const svg = `
		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="52" viewBox="0 0 40 52" fill="none">
			<path d="M20 0C9.5 0 1 8.5 1 19c0 14.2 19 33 19 33s19-18.8 19-33C39 8.5 30.5 0 20 0z" fill="#0B1F3A"/>
			<circle cx="20" cy="19" r="7" fill="#C7A46A"/>
		</svg>
	`.trim();

	return `data:image/svg+xml;charset=UTF-8,${ encodeURIComponent( svg ) }`;
}

/**
 * @param {string} apiKey
 * @param {boolean} withMarkerLib
 * @returns {Promise<{ mapsLib: google.maps.MapsLibrary, markerLib?: google.maps.MarkerLibrary }>}
 */
function loadGoogleMapsApi( apiKey, withMarkerLib ) {
	if ( window.__seahivezMapsLibraries && ( ! withMarkerLib || window.__seahivezMapsLibraries.markerLib ) ) {
		return Promise.resolve( window.__seahivezMapsLibraries );
	}

	if ( window.__seahivezMapsPromise ) {
		return window.__seahivezMapsPromise;
	}

	window.__seahivezMapsPromise = new Promise( ( resolve, reject ) => {
		const script = document.createElement( 'script' );
		script.src = `https://maps.googleapis.com/maps/api/js?key=${ encodeURIComponent( apiKey ) }&loading=async`;
		script.async = true;
		script.defer = true;
		script.onload = async () => {
			try {
				if ( ! window.google?.maps?.importLibrary ) {
					reject( new Error( 'Google Maps failed to initialize' ) );
					return;
				}

				const mapsLib = await window.google.maps.importLibrary( 'maps' );
				const libraries = { mapsLib };

				if ( withMarkerLib ) {
					libraries.markerLib = await window.google.maps.importLibrary( 'marker' );
				}

				window.__seahivezMapsLibraries = libraries;
				resolve( libraries );
			} catch ( error ) {
				reject( error );
			}
		};
		script.onerror = () => reject( new Error( 'Google Maps script failed to load' ) );
		document.head.appendChild( script );
	} );

	return window.__seahivezMapsPromise;
}

/**
 * @param {HTMLElement} root
 */
function showMapFallback( root ) {
	const canvas = root.querySelector( '[data-map-canvas]' );
	const fallback = root.querySelector( '[data-map-fallback]' );

	root.classList.add( 'is-fallback' );
	if ( canvas ) {
		canvas.hidden = true;
	}
	if ( fallback ) {
		fallback.hidden = false;
	}
}

/**
 * @param {string} label
 * @param {string} place
 * @param {string} mapsUrl
 * @returns {google.maps.InfoWindow}
 */
function createInfoWindow( label, place, mapsUrl ) {
	const openLink = mapsUrl
		? `<p style="margin:8px 0 0;"><a href="${ mapsUrl }" target="_blank" rel="noopener noreferrer" style="color:#0B1F3A;text-decoration:underline;">Open in Google Maps</a></p>`
		: '';

	return new google.maps.InfoWindow( {
		content: `
			<div style="font-family:Satoshi,Arial,sans-serif;padding:4px 2px;max-width:200px;color:#0B1F3A;">
				<strong style="display:block;font-size:14px;margin-bottom:2px;">${ label }</strong>
				<span style="font-size:13px;color:#5C6570;">${ place }</span>
				${ openLink }
			</div>
		`,
	} );
}

/**
 * @param {google.maps.Map} map
 * @param {google.maps.LatLngLiteral} position
 * @param {string} label
 * @param {string} place
 * @param {string} mapsUrl
 * @returns {google.maps.Marker}
 */
function createClassicMarker( map, position, label, place, mapsUrl ) {
	const marker = new google.maps.Marker( {
		map,
		position,
		title: label,
		icon: {
			url: getMarkerIconUrl(),
			scaledSize: new google.maps.Size( 40, 52 ),
			anchor: new google.maps.Point( 20, 52 ),
		},
	} );

	const info = createInfoWindow( label, place, mapsUrl );
	marker.addListener( 'click', () => {
		info.open( { map, anchor: marker } );
	} );

	return marker;
}

/**
 * @param {HTMLElement} canvas
 * @param {google.maps.MapsLibrary} mapsLib
 * @param {google.maps.LatLngLiteral} position
 * @param {string} mapId
 * @returns {google.maps.Map}
 */
function createCloudMap( canvas, mapsLib, position, mapId ) {
	const { Map } = mapsLib;

	return new Map( canvas, {
		...MAP_OPTIONS,
		center: position,
		mapId,
	} );
}

/**
 * @param {HTMLElement} canvas
 * @param {google.maps.MapsLibrary} mapsLib
 * @param {google.maps.LatLngLiteral} position
 * @returns {google.maps.Map}
 */
function createLegacyMap( canvas, mapsLib, position ) {
	const { Map } = mapsLib;

	return new Map( canvas, {
		...MAP_OPTIONS,
		center: position,
		styles: SEA_HIVEZ_MAP_STYLES,
		backgroundColor: '#F4F1EA',
	} );
}

/**
 * @param {HTMLElement} root
 * @param {{ mapsLib: google.maps.MapsLibrary, markerLib?: google.maps.MarkerLibrary }} libraries
 */
function initSingleMap( root, libraries ) {
	const canvas = root.querySelector( '[data-map-canvas]' );
	if ( ! canvas ) {
		return;
	}

	const lat = parseFloat( root.dataset.lat || '' );
	const lng = parseFloat( root.dataset.lng || '' );
	const label = root.dataset.label || 'SeaHivez';
	const place = root.dataset.place || "S'Arenal, Mallorca";
	const mapsUrl = root.dataset.mapsUrl || '';
	const mapId = window.seahivezData?.mapsMapId || '';

	if ( Number.isNaN( lat ) || Number.isNaN( lng ) ) {
		showMapFallback( root );
		return;
	}

	const position = { lat, lng };
	let map;

	try {
		if ( mapId ) {
			map = createCloudMap( canvas, libraries.mapsLib, position, mapId );

			if ( libraries.markerLib?.AdvancedMarkerElement ) {
				const markerImage = document.createElement( 'img' );
				markerImage.src = getMarkerIconUrl();
				markerImage.width = 40;
				markerImage.height = 52;
				markerImage.alt = '';

				const marker = new libraries.markerLib.AdvancedMarkerElement( {
					map,
					position,
					title: label,
					content: markerImage,
				} );

				const info = createInfoWindow( label, place, mapsUrl );
				marker.addListener( 'click', () => {
					info.open( { map, anchor: marker } );
				} );
			} else {
				createClassicMarker( map, position, label, place, mapsUrl );
			}
		} else {
			map = createLegacyMap( canvas, libraries.mapsLib, position );
			createClassicMarker( map, position, label, place, mapsUrl );
		}
	} catch ( error ) {
		map = createLegacyMap( canvas, libraries.mapsLib, position );
		createClassicMarker( map, position, label, place, mapsUrl );
	}

	root.classList.add( 'is-ready' );
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

	if ( ! apiKey ) {
		roots.forEach( ( root ) => showMapFallback( root ) );
		return;
	}

	loadGoogleMapsApi( apiKey, Boolean( mapId ) )
		.then( ( libraries ) => {
			roots.forEach( ( root ) => initSingleMap( root, libraries ) );
		} )
		.catch( () => {
			roots.forEach( ( root ) => showMapFallback( root ) );
		} );
}
