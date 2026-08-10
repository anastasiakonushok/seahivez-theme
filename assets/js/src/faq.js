/**
 * FAQ accordion — one open panel at a time.
 *
 * @package seahivez-theme
 */

/**
 * @param {HTMLElement} item
 * @param {boolean} open
 */
function setFaqItemOpen( item, open ) {
	const trigger = item.querySelector( '[data-faq-trigger]' );
	const panel = item.querySelector( '[data-faq-panel]' );

	if ( ! trigger || ! panel ) {
		return;
	}

	item.classList.toggle( 'is-open', open );
	trigger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	panel.setAttribute( 'aria-hidden', open ? 'false' : 'true' );
}

/**
 * @param {HTMLElement} accordion
 * @param {HTMLElement} item
 */
function toggleFaqItem( accordion, item ) {
	const wasOpen = item.classList.contains( 'is-open' );

	accordion.querySelectorAll( '[data-faq-item].is-open' ).forEach( ( openItem ) => {
		setFaqItemOpen( openItem, false );
	} );

	if ( ! wasOpen ) {
		setFaqItemOpen( item, true );
	}
}

/**
 * Initialize all FAQ accordions on the page.
 */
export function initFaq() {
	document.querySelectorAll( '[data-faq-accordion]' ).forEach( ( accordion ) => {
		if ( accordion.dataset.faqReady === 'true' ) {
			return;
		}

		accordion.dataset.faqReady = 'true';

		accordion.querySelectorAll( '[data-faq-trigger]' ).forEach( ( trigger ) => {
			trigger.addEventListener( 'click', ( event ) => {
				const button = event.currentTarget;

				if ( ! ( button instanceof HTMLElement ) ) {
					return;
				}

				const item = button.closest( '[data-faq-item]' );

				if ( ! item || ! accordion.contains( item ) ) {
					return;
				}

				toggleFaqItem( accordion, item );
			} );
		} );
	} );
}
