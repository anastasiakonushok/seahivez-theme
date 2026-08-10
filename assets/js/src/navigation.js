/**
 * Mobile navigation — accessible toggle, escape close, body scroll lock.
 */
export function initNavigation() {
	const toggle = document.getElementById( 'mobile-menu-toggle' );
	const panel = document.getElementById( 'mobile-navigation' );
	const siteHeader = document.getElementById( 'masthead' );

	if ( ! toggle || ! panel ) {
		return;
	}

	const menu = panel.querySelector( 'ul' );

	if ( ! menu ) {
		toggle.hidden = true;
		return;
	}

	const focusableSelector =
		'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';

	function isOpen() {
		return toggle.getAttribute( 'aria-expanded' ) === 'true';
	}

	function openMenu() {
		toggle.setAttribute( 'aria-expanded', 'true' );
		panel.hidden = false;
		document.body.classList.add( 'overflow-hidden' );
		siteHeader?.classList.add( 'is-menu-open' );
	}

	function closeMenu() {
		toggle.setAttribute( 'aria-expanded', 'false' );
		panel.hidden = true;
		document.body.classList.remove( 'overflow-hidden' );
		siteHeader?.classList.remove( 'is-menu-open' );
	}

	function toggleMenu() {
		if ( isOpen() ) {
			closeMenu();
		} else {
			openMenu();
		}
	}

	toggle.addEventListener( 'click', () => {
		toggleMenu();
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' && isOpen() ) {
			closeMenu();
			toggle.focus();
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		if ( ! isOpen() ) {
			return;
		}

		const target = event.target;

		if (
			panel.contains( target ) ||
			toggle.contains( target )
		) {
			return;
		}

		closeMenu();
	} );

	panel.querySelectorAll( 'a' ).forEach( ( link ) => {
		link.addEventListener( 'click', () => {
			closeMenu();
		} );
	} );

	// Submenu focus support for desktop navigation.
	const desktopNav = document.getElementById( 'site-navigation' );

	if ( desktopNav ) {
		const linksWithChildren = desktopNav.querySelectorAll(
			'.menu-item-has-children > a, .page_item_has_children > a'
		);

		linksWithChildren.forEach( ( link ) => {
			link.addEventListener( 'focus', toggleSubmenuFocus, true );
			link.addEventListener( 'blur', toggleSubmenuFocus, true );
			link.addEventListener( 'touchstart', toggleSubmenuTouch, false );
		} );
	}

	function toggleSubmenuFocus( event ) {
		let self = event.currentTarget;

		while ( self && ! self.classList.contains( 'nav-menu' ) ) {
			if ( self.tagName.toLowerCase() === 'li' ) {
				self.classList.toggle( 'focus', event.type === 'focus' );
			}
			self = self.parentNode;
		}
	}

	function toggleSubmenuTouch( event ) {
		const menuItem = event.currentTarget.parentNode;
		event.preventDefault();

		menuItem.parentNode.querySelectorAll( ':scope > li' ).forEach( ( sibling ) => {
			if ( sibling !== menuItem ) {
				sibling.classList.remove( 'focus' );
			}
		} );

		menuItem.classList.toggle( 'focus' );
	}
}
