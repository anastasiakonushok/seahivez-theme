/**
 * Homepage gallery — Swiper slider + Fancybox lightbox.
 *
 * @package seahivez-theme
 */

import Swiper from 'swiper';
import { Navigation, Thumbs, Keyboard, A11y } from 'swiper/modules';
import { Fancybox } from '@fancyapps/ui';

const FANCYBOX_GROUPS = [
	'[data-fancybox="seahivez-gallery"]',
	'[data-fancybox="seahivez-full-gallery"]',
];

let fancyboxBound = false;

/**
 * Bind Fancybox once for SeaHivez gallery groups.
 */
function bindGalleryFancybox() {
	if ( fancyboxBound ) {
		return;
	}

	const hasGallery = FANCYBOX_GROUPS.some( ( selector ) => document.querySelector( selector ) );

	if ( ! hasGallery ) {
		return;
	}

	FANCYBOX_GROUPS.forEach( ( selector ) => {
		if ( document.querySelector( selector ) ) {
			Fancybox.bind( selector, {
				theme: 'dark',
			} );
		}
	} );

	fancyboxBound = true;
}

/**
 * Open Fancybox starting at a given gallery index.
 *
 * @param {number} startIndex
 */
function openGalleryFancybox( startIndex = 0 ) {
	const triggers = Array.from( document.querySelectorAll( FANCYBOX_GROUPS[ 0 ] ) );

	if ( ! triggers.length ) {
		return;
	}

	const index = Math.max( 0, Math.min( startIndex, triggers.length - 1 ) );
	const target = triggers[ index ];

	if ( target instanceof HTMLElement ) {
		target.click();
	}
}

/**
 * Initialize the immersive homepage gallery slider.
 */
function initGallerySlider() {
	const root = document.querySelector( '[data-gallery-slider]' );

	if ( ! root || root.dataset.galleryReady === 'true' ) {
		return;
	}

	const mainEl = root.querySelector( '[data-gallery-main]' );
	const thumbsEl = root.querySelector( '[data-gallery-thumbs]' );
	const prevEl = root.querySelector( '[data-gallery-prev]' );
	const nextEl = root.querySelector( '[data-gallery-next]' );
	const moreBtn = root.querySelector( '[data-gallery-more]' );

	if ( ! ( mainEl instanceof HTMLElement ) || ! ( thumbsEl instanceof HTMLElement ) ) {
		return;
	}

	root.dataset.galleryReady = 'true';

	const thumbsSwiper = new Swiper( thumbsEl, {
		modules: [ A11y ],
		spaceBetween: 10,
		slidesPerView: 'auto',
		watchSlidesProgress: true,
		slideToClickedSlide: true,
		a11y: {
			enabled: true,
		},
	} );

	const mainSwiper = new Swiper( mainEl, {
		modules: [ Navigation, Thumbs, Keyboard, A11y ],
		speed: 600,
		spaceBetween: 0,
		loop: false,
		grabCursor: true,
		preventClicks: true,
		preventClicksPropagation: true,
		keyboard: {
			enabled: true,
			onlyInViewport: true,
		},
		navigation: {
			prevEl: prevEl instanceof HTMLElement ? prevEl : null,
			nextEl: nextEl instanceof HTMLElement ? nextEl : null,
		},
		thumbs: {
			swiper: thumbsSwiper,
			autoScrollOffset: 1,
		},
		a11y: {
			enabled: true,
			prevSlideMessage: 'Previous image',
			nextSlideMessage: 'Next image',
		},
	} );

	if ( moreBtn instanceof HTMLElement ) {
		moreBtn.addEventListener( 'click', () => {
			openGalleryFancybox( mainSwiper.activeIndex || 0 );
		} );
	}

	mainSwiper.on( 'slideChange', () => {
		thumbsSwiper.slideTo( mainSwiper.activeIndex );
	} );
}

/**
 * Initialize gallery slider and lightbox.
 */
export function initGallery() {
	initGallerySlider();
	bindGalleryFancybox();
}
