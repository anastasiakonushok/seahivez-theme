/**
 * Palma / Mallorca weather + local date widget.
 *
 * Date is rendered client-side in Europe/Madrid.
 * Weather is fetched from the theme REST proxy.
 *
 * @package seahivez-theme
 */

const MALLORCA_TZ = 'Europe/Madrid';

/**
 * Format today's date in Mallorca local time.
 *
 * @returns {string}
 */
function formatMallorcaDate() {
	return new Intl.DateTimeFormat( 'en-GB', {
		timeZone: MALLORCA_TZ,
		day: 'numeric',
		month: 'long',
		year: 'numeric',
	} ).format( new Date() );
}

/**
 * @param {HTMLElement} root
 */
function renderDate( root ) {
	const dateEl = root.querySelector( '[data-weather-date]' );
	if ( dateEl ) {
		dateEl.textContent = formatMallorcaDate();
	}
}

/**
 * @param {HTMLElement} root
 * @param {{ temperature?: number, condition?: string }} data
 */
function renderWeather( root, data ) {
	const tempEl = root.querySelector( '[data-weather-temp]' );
	const conditionEl = root.querySelector( '[data-weather-condition]' );

	if ( tempEl && typeof data.temperature === 'number' ) {
		tempEl.textContent = `${ data.temperature }°`;
	}

	if ( conditionEl && data.condition ) {
		conditionEl.textContent = data.condition;
	}
}

/**
 * @param {HTMLElement} root
 */
function renderWeatherUnavailable( root ) {
	const conditionEl = root.querySelector( '[data-weather-condition]' );
	const tempEl = root.querySelector( '[data-weather-temp]' );
	const sep = root.querySelector( '.weather-card__sep' );

	if ( tempEl ) {
		tempEl.textContent = '';
	}
	if ( sep ) {
		sep.hidden = true;
	}
	if ( conditionEl ) {
		conditionEl.textContent = 'Weather unavailable';
	}
}

/**
 * Initialize weather widgets on the page.
 */
export function initWeather() {
	const widgets = document.querySelectorAll( '[data-weather-widget]' );
	if ( ! widgets.length ) {
		return;
	}

	widgets.forEach( ( root ) => {
		renderDate( root );
	} );

	const endpoint = window.seahivezData?.weatherEndpoint;
	if ( ! endpoint ) {
		widgets.forEach( ( root ) => renderWeatherUnavailable( root ) );
		return;
	}

	fetch( endpoint, { credentials: 'same-origin' } )
		.then( ( response ) => {
			if ( ! response.ok ) {
				throw new Error( 'Weather request failed' );
			}
			return response.json();
		} )
		.then( ( data ) => {
			widgets.forEach( ( root ) => renderWeather( root, data ) );
		} )
		.catch( () => {
			widgets.forEach( ( root ) => renderWeatherUnavailable( root ) );
		} );
}
