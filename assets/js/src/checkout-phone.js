import intlTelInput from 'intl-tel-input';

/**
 * Initialize international phone input on checkout form.
 */
export function initCheckoutPhone() {
	const input = document.getElementById( 'customer_phone' );

	if ( ! ( input instanceof HTMLInputElement ) || input.dataset.intlPhoneReady === 'true' ) {
		return;
	}

	const form = input.closest( 'form' );
	const utilsUrl = window.seahivezData?.intlTelUtilsUrl || '';

	const iti = intlTelInput( input, {
		initialCountry: 'es',
		preferredCountries: [ 'es', 'gb', 'de', 'fr', 'it', 'ru', 'by', 'pl', 'ua' ],
		separateDialCode: false,
		countrySearch: true,
		formatOnDisplay: true,
		nationalMode: false,
		autoPlaceholder: 'polite',
		loadUtilsOnInit: utilsUrl
			? utilsUrl
			: () => import( 'intl-tel-input/utils' ),
	} );

	input.dataset.intlPhoneReady = 'true';

	const showError = ( message ) => {
		input.classList.add( 'is-invalid' );
		input.setCustomValidity( message );
		input.reportValidity();
	};

	const clearError = () => {
		input.classList.remove( 'is-invalid' );
		input.setCustomValidity( '' );
	};

	const isPhoneValid = () => {
		const value = input.value.trim();

		if ( ! value ) {
			return false;
		}

		try {
			return iti.isValidNumber();
		} catch {
			return value.replace( /\D/g, '' ).length >= 8;
		}
	};

	input.addEventListener( 'countrychange', clearError );

	input.addEventListener( 'blur', () => {
		if ( ! input.value.trim() ) {
			clearError();
			return;
		}

		if ( ! isPhoneValid() ) {
			showError( 'Please enter a valid phone number with country code.' );
			return;
		}

		clearError();
	} );

	form?.addEventListener( 'submit', ( event ) => {
		if ( ! input.value.trim() ) {
			showError( 'Phone number is required.' );
			event.preventDefault();
			return;
		}

		if ( ! isPhoneValid() ) {
			showError( 'Please enter a valid phone number with country code.' );
			event.preventDefault();
			return;
		}

		const fullNumber = iti.getNumber();

		if ( fullNumber ) {
			input.value = fullNumber;
		}

		clearError();
	} );
}
