/**
 * Homepage header scroll behavior.
 */
export function initHeader() {
	const header = document.getElementById( 'masthead' );

	if ( ! header || ! header.classList.contains( 'site-header--overlay' ) ) {
		return;
	}

	const updateHeaderState = () => {
		if ( window.scrollY > 48 ) {
			header.classList.add( 'site-header--scrolled' );
		} else {
			header.classList.remove( 'site-header--scrolled' );
		}
	};

	updateHeaderState();
	window.addEventListener( 'scroll', updateHeaderState, { passive: true } );
}
