import { initNavigation } from './navigation.js';
import { initHeader } from './header.js';
import { initReveal } from './reveal.js';
import { initGallery } from './gallery.js';
import { initFaq } from './faq.js';
import { initMap } from './map.js';
import { initCharterCalculator } from './charter-calculator.js';

document.addEventListener( 'DOMContentLoaded', () => {
	initNavigation();
	initHeader();
	initReveal();
	initGallery();
	initFaq();
	initMap();
	initCharterCalculator();
} );
