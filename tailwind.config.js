/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php',
		'./assets/js/src/**/*.js',
	],
	theme: {
		fontFamily: {
			sans: [
				'Satoshi',
				'Arial',
				'sans-serif',
			],
		},
		extend: {
			colors: {
				navy: {
					950: '#071428',
					900: '#0B1F3A',
					800: '#123052',
					700: '#1A4068',
				},
				sand: {
					50: '#FAFAF8',
					100: '#F5F1EA',
					200: '#E8E0D4',
				},
				gold: {
					DEFAULT: '#C7A46A',
					light: '#D4B57E',
					dark: '#A68244',
				},
				warm: {
					white: '#FDFCFA',
				},
			},
			maxWidth: {
				site: '80rem',
			},
			borderRadius: {
				DEFAULT: '0.375rem',
			},
			transitionDuration: {
				DEFAULT: '200ms',
			},
		},
	},
	plugins: [],
};
