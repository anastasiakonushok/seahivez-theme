import { initNavigation } from './navigation.js';
import { initHeader } from './header.js';
import { initReveal } from './reveal.js';
import { initGallery } from './gallery.js';
import { initFaq } from './faq.js';
import { initWeather } from './weather.js';
import { initMap } from './map.js';

document.addEventListener( 'DOMContentLoaded', () => {
	initNavigation();
	initHeader();
	initReveal();
	initGallery();
	initFaq();
	initWeather();
	initMap();
} );
