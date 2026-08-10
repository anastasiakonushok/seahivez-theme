/**
 * SeaHivez Google Map — grayscale custom style + navy marker.
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
 * Load the Google Maps JS API once.
 *
 * @param {string} apiKey
 * @returns {Promise<typeof google.maps>}
 */
function loadGoogleMapsApi( apiKey ) {
	if ( window.google?.maps ) {
		return Promise.resolve( window.google.maps );
	}

	if ( window.__seahivezMapsPromise ) {
		return window.__seahivezMapsPromise;
	}

	window.__seahivezMapsPromise = new Promise( ( resolve, reject ) => {
		const script = document.createElement( 'script' );
		script.src = `https://maps.googleapis.com/maps/api/js?key=${ encodeURIComponent( apiKey ) }`;
		script.async = true;
		script.defer = true;
		script.onload = () => {
			if ( window.google?.maps ) {
				resolve( window.google.maps );
			} else {
				reject( new Error( 'Google Maps failed to initialize' ) );
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
 * @param {HTMLElement} root
 * @param {typeof google.maps} maps
 */
function initSingleMap( root, maps ) {
	const canvas = root.querySelector( '[data-map-canvas]' );
	if ( ! canvas ) {
		return;
	}

	const lat = parseFloat( root.dataset.lat || '' );
	const lng = parseFloat( root.dataset.lng || '' );
	const label = root.dataset.label || 'SeaHivez';
	const place = root.dataset.place || "S'Arenal, Mallorca";
	const mapsUrl = root.dataset.mapsUrl || '';

	if ( Number.isNaN( lat ) || Number.isNaN( lng ) ) {
		showMapFallback( root );
		return;
	}

	const position = { lat, lng };

	const map = new maps.Map( canvas, {
		center: position,
		zoom: 13,
		styles: SEA_HIVEZ_MAP_STYLES,
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeControl: false,
		streetViewControl: false,
		fullscreenControl: false,
		gestureHandling: 'greedy',
		clickableIcons: false,
		backgroundColor: '#F4F1EA',
	} );

	const marker = new maps.Marker( {
		map,
		position,
		title: label,
		icon: {
			url: getMarkerIconUrl(),
			scaledSize: new maps.Size( 40, 52 ),
			anchor: new maps.Point( 20, 52 ),
		},
	} );

	const openLink = mapsUrl
		? `<p style="margin:8px 0 0;"><a href="${ mapsUrl }" target="_blank" rel="noopener noreferrer" style="color:#0B1F3A;text-decoration:underline;">Open in Google Maps</a></p>`
		: '';

	const info = new maps.InfoWindow( {
		content: `
			<div style="font-family:Satoshi,Arial,sans-serif;padding:4px 2px;max-width:200px;color:#0B1F3A;">
				<strong style="display:block;font-size:14px;margin-bottom:2px;">${ label }</strong>
				<span style="font-size:13px;color:#5C6570;">${ place }</span>
				${ openLink }
			</div>
		`,
	} );

	marker.addListener( 'click', () => {
		info.open( { map, anchor: marker } );
	} );

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

	if ( ! apiKey ) {
		roots.forEach( ( root ) => showMapFallback( root ) );
		return;
	}

	loadGoogleMapsApi( apiKey )
		.then( ( maps ) => {
			roots.forEach( ( root ) => initSingleMap( root, maps ) );
		} )
		.catch( () => {
			roots.forEach( ( root ) => showMapFallback( root ) );
		} );
}
