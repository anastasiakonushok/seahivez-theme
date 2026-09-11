const CARD_SELECTOR = '[data-charter-card]';

/**
 * @param {number} amount
 * @returns {string}
 */
function formatEuro( amount ) {
	const value = Number.isFinite( amount ) ? amount : 0;

	return `€${ value.toLocaleString( 'en-GB', {
		minimumFractionDigits: 0,
		maximumFractionDigits: 0,
	} ) }`;
}

/**
 * @param {number} amount
 * @returns {string}
 */
function formatSignedEuro( amount ) {
	if ( amount > 0 ) {
		return `+${ formatEuro( amount ) }`;
	}

	return formatEuro( amount );
}

/**
 * @param {Record<string, unknown>} config
 * @param {string} routeId
 * @param {Record<string, boolean>} selectedExtras
 * @param {Record<string, number>} quantities
 */
function calculateTotals( config, routeId, selectedExtras, quantities ) {
	const routes = Array.isArray( config.routes ) ? config.routes : [];
	const route = routes.find( ( item ) => item.id === routeId ) || routes[0] || { surcharge: 0 };
	const base = Number( config.basePrice ) || 0;
	const surcharge = Number( route.surcharge ) || 0;
	const deposit = Number( config.deposit ) || 0;
	const lines = [];

	lines.push( {
		key: 'base',
		label: config.labels?.baseCharter || 'Base charter',
		amount: base,
	} );

	lines.push( {
		key: 'route',
		label: config.labels?.routeSurcharge || 'Route surcharge',
		amount: surcharge,
	} );

	const extras = Array.isArray( config.extras ) ? config.extras : [];

	extras.forEach( ( extra ) => {
		if ( ! selectedExtras[ extra.id ] ) {
			return;
		}

		lines.push( {
			key: `extra-${ extra.id }`,
			label: extra.label,
			amount: Number( extra.price ) || 0,
		} );
	} );

	const quantityExtras = Array.isArray( config.quantityExtras ) ? config.quantityExtras : [];

	quantityExtras.forEach( ( extra ) => {
		const qty = Number( quantities[ extra.id ] ) || 0;

		if ( qty <= 0 ) {
			return;
		}

		const unitPrice = Number( extra.price ) || 0;

		lines.push( {
			key: `qty-${ extra.id }`,
			label: extra.label,
			amount: qty * unitPrice,
			detail: `${ qty } × ${ formatEuro( unitPrice ) }`,
		} );
	} );

	const charterTotal = lines.reduce( ( sum, line ) => sum + line.amount, 0 );

	return {
		base,
		surcharge,
		lines,
		charterTotal,
		deposit,
		amountToPrepare: charterTotal + deposit,
		route,
	};
}

class InlineCharterCalculator {
	/**
	 * @param {HTMLElement} article
	 * @param {HTMLElement} charterCard
	 * @param {Record<string, unknown>} config
	 */
	constructor( article, charterCard, config ) {
		this.article = article;
		this.charterCard = charterCard;
		this.config = config;
		this.panel = charterCard.querySelector( '[data-charter-calculator-panel]' );
		this.body = charterCard.querySelector( '[data-charter-calculator-body]' );
		this.toggle = charterCard.querySelector( '[data-charter-calculator-toggle]' );
		this.toggleText = charterCard.querySelector( '[data-charter-calculator-toggle-text]' );
		this.toggleIcon = charterCard.querySelector( '[data-charter-calculator-toggle-icon]' );
		this.openLabel = config.labels?.calculateFinalPrice || 'Calculate final price';
		this.closeLabel = config.labels?.closeCalculator || 'Close calculator';
		this.activeRouteId = charterCard.getAttribute( 'data-default-route' ) || config.defaultRouteId || '';
		this.selectedExtras = {};
		this.quantities = {};
		this.isOpen = false;
		this.priceStack = article.querySelector( '[data-charter-price-stack]' );
		this.basePrice = article.querySelector( '[data-charter-base-price]' );
		this.fuelRow = article.querySelector( '[data-charter-fuel-row]' );
		this.fuelValue = article.querySelector( '[data-charter-fuel-value]' );
		this.totalPrice = article.querySelector( '[data-charter-total-price]' );

		this.resetState();
		this.bindEvents();
		this.updateToggleLabel();
		this.updateFooterPrice();
	}

	resetState() {
		this.selectedExtras = {};
		this.quantities = {};

		if ( Array.isArray( this.config.extras ) ) {
			this.config.extras.forEach( ( extra ) => {
				this.selectedExtras[ extra.id ] = false;
			} );
		}

		if ( Array.isArray( this.config.quantityExtras ) ) {
			this.config.quantityExtras.forEach( ( extra ) => {
				this.quantities[ extra.id ] = 0;
			} );
		}
	}

	updateToggleLabel() {
		if ( this.toggleText ) {
			this.toggleText.textContent = this.isOpen ? this.closeLabel : this.openLabel;
		}

		if ( this.toggleIcon ) {
			this.toggleIcon.textContent = this.isOpen ? '↑' : '↓';
		}
	}

	bindEvents() {
		this.toggle?.addEventListener( 'click', () => {
			this.isOpen = ! this.isOpen;
			this.panel?.classList.toggle( 'hidden', ! this.isOpen );
			this.panel?.toggleAttribute( 'hidden', ! this.isOpen );
			this.toggle?.setAttribute( 'aria-expanded', this.isOpen ? 'true' : 'false' );
			this.toggle?.classList.toggle( 'is-open', this.isOpen );
			this.updateToggleLabel();

			if ( this.isOpen ) {
				this.render();
			}
		} );

		this.charterCard.querySelectorAll( '[data-route-tab]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const routeId = button.getAttribute( 'data-route-tab' ) || this.activeRouteId;
				this.setRoute( routeId );
			} );
		} );
	}

	/**
	 * @param {string} routeId
	 */
	setRoute( routeId ) {
		this.activeRouteId = routeId;

		this.charterCard.querySelectorAll( '[data-route-panel]' ).forEach( ( panel ) => {
			const isActive = panel.getAttribute( 'data-route-panel' ) === routeId;
			panel.classList.toggle( 'hidden', ! isActive );
			panel.toggleAttribute( 'hidden', ! isActive );
		} );

		this.charterCard.querySelectorAll( '[data-route-tab]' ).forEach( ( button ) => {
			const isActive = button.getAttribute( 'data-route-tab' ) === routeId;
			button.classList.toggle( 'is-active', isActive );
			button.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
		} );

		this.updateFooterPrice();

		if ( this.isOpen ) {
			this.render();
		}
	}

	updateFooterPrice() {
		if ( ! this.priceStack ) {
			return;
		}

		const routes = Array.isArray( this.config.routes ) ? this.config.routes : [];
		const route = routes.find( ( item ) => item.id === this.activeRouteId ) || routes[0] || { surcharge: 0 };
		const base = Number( this.config.basePrice ) || 0;
		const surcharge = Number( route.surcharge ) || 0;
		const total = base + surcharge;
		const fuelLabel = this.config.labels?.fuel || 'Fuel';

		const hasFuelAdjustment = surcharge > 0;

		if ( this.basePrice ) {
			this.basePrice.classList.toggle( 'hidden', ! hasFuelAdjustment );
			this.basePrice.toggleAttribute( 'hidden', ! hasFuelAdjustment );
		}

		if ( this.fuelRow ) {
			const fuelLabelNode = this.fuelRow.querySelector( 'span:first-child' );

			if ( fuelLabelNode ) {
				fuelLabelNode.textContent = fuelLabel;
			}

			this.fuelRow.classList.toggle( 'hidden', ! hasFuelAdjustment );
			this.fuelRow.toggleAttribute( 'hidden', ! hasFuelAdjustment );
		}

		if ( this.fuelValue ) {
			this.fuelValue.textContent = hasFuelAdjustment ? formatSignedEuro( surcharge ) : '';
		}

		if ( this.totalPrice ) {
			this.totalPrice.textContent = formatEuro( total );
			this.totalPrice.classList.toggle( 'experience-card__price--with-fuel', hasFuelAdjustment );
		}

		this.priceStack?.classList.toggle( 'experience-card__price-stack--with-fuel', hasFuelAdjustment );
	}

	render() {
		if ( ! this.body ) {
			return;
		}

		const totals = calculateTotals(
			this.config,
			this.activeRouteId,
			this.selectedExtras,
			this.quantities
		);

		this.body.innerHTML = `
			${ this.buildPriceBreakdownSection( totals ) }
			${ this.buildExtrasSection() }
			${ this.buildQuantitySection() }
			${ this.buildTotalsSection( totals ) }
		`;

		this.bindBodyEvents();
	}

	/**
	 * @param {ReturnType<typeof calculateTotals>} totals
	 */
	buildPriceBreakdownSection( totals ) {
		const route = totals.route;
		const routeRef = route?.name
			? `<p class="charter-calculator__route-ref">${ this.config.labels?.selectedRoutePrefix || 'Selected route:' } ${ route.name }</p>`
			: '';

		const breakdownLines = totals.lines
			.filter( ( line ) => line.key === 'base' || line.key === 'route' )
			.map( ( line ) => {
				const value = line.key === 'route' && line.amount > 0
					? formatSignedEuro( line.amount )
					: formatEuro( line.amount );

				return `
					<div class="charter-calculator__line">
						<span>${ line.label }</span>
						<span class="charter-calculator__line-value">${ value }</span>
					</div>
				`;
			} )
			.join( '' );

		return `
			<section class="charter-calculator__section charter-calculator__section--breakdown">
				<h4 class="charter-calculator__section-title">${ this.config.labels?.priceBreakdown || 'Price breakdown' }</h4>
				${ routeRef }
				<div class="charter-calculator__breakdown">${ breakdownLines }</div>
			</section>
		`;
	}

	buildExtrasSection() {
		const extras = Array.isArray( this.config.extras ) ? this.config.extras : [];

		if ( ! extras.length ) {
			return '';
		}

		const rows = extras
			.map( ( extra ) => {
				const checked = !! this.selectedExtras[ extra.id ];

				return `
					<label class="charter-calculator__extra-compact${ checked ? ' is-selected' : '' }">
						<input type="checkbox" class="charter-calculator__extra-checkbox" value="${ extra.id }" ${ checked ? 'checked' : '' } data-charter-extra-input>
						<span class="charter-calculator__extra-compact-label">${ extra.label }</span>
						<span class="charter-calculator__extra-compact-price">${ formatEuro( extra.price || 0 ) }</span>
					</label>
					${ extra.description ? `<p class="charter-calculator__extra-compact-note">${ extra.description }</p>` : '' }
				`;
			} )
			.join( '' );

		return `
			<section class="charter-calculator__section">
				<h4 class="charter-calculator__section-title">${ this.config.labels?.optionalExtras || 'Optional extras' }</h4>
				<div class="charter-calculator__extras-compact">${ rows }</div>
			</section>
		`;
	}

	buildQuantitySection() {
		const quantityExtras = Array.isArray( this.config.quantityExtras ) ? this.config.quantityExtras : [];

		if ( ! quantityExtras.length ) {
			return '';
		}

		const rows = quantityExtras
			.map( ( extra ) => {
				const qty = Number( this.quantities[ extra.id ] ) || 0;

				return `
					<div class="charter-calculator__quantity-compact">
						<span class="charter-calculator__quantity-compact-label">${ extra.label } · ${ formatEuro( extra.price || 0 ) }/${ extra.unit || 'person' }</span>
						<div class="charter-calculator__quantity-controls">
							<button type="button" class="charter-calculator__qty-btn" data-charter-qty-minus="${ extra.id }" aria-label="Decrease ${ extra.label }">−</button>
							<span class="charter-calculator__qty-value">${ qty }</span>
							<button type="button" class="charter-calculator__qty-btn" data-charter-qty-plus="${ extra.id }" aria-label="Increase ${ extra.label }">+</button>
						</div>
					</div>
				`;
			} )
			.join( '' );

		return `
			<section class="charter-calculator__section">
				<h4 class="charter-calculator__section-title">${ this.config.labels?.foodDrinks || 'Food & drinks' }</h4>
				<div class="charter-calculator__quantities-compact">${ rows }</div>
			</section>
		`;
	}

	/**
	 * @param {ReturnType<typeof calculateTotals>} totals
	 */
	buildTotalsSection( totals ) {
		const summaryLines = totals.lines
			.filter( ( line ) => line.key !== 'base' && line.key !== 'route' )
			.map( ( line ) => {
				const label = line.detail
					? `${ line.label } · ${ line.detail }`
					: line.label;

				return `
					<div class="charter-calculator__total-line">
						<span>${ label }</span>
						<span class="charter-calculator__line-value">${ formatEuro( line.amount ) }</span>
					</div>
				`;
			} )
			.join( '' );

		return `
			<section class="charter-calculator__section charter-calculator__section--totals">
				<div class="charter-calculator__totals">
					${ summaryLines }
					<div class="charter-calculator__total-divider" aria-hidden="true"></div>
					<div class="charter-calculator__total-line charter-calculator__total-line--strong">
						<span>${ this.config.labels?.charterTotal || 'Charter total' }</span>
						<span class="charter-calculator__line-value">${ formatEuro( totals.charterTotal ) }</span>
					</div>
					<div class="charter-calculator__total-line">
						<span>${ this.config.labels?.deposit || 'Refundable security deposit' }</span>
						<span class="charter-calculator__line-value">${ formatEuro( totals.deposit ) }</span>
					</div>
					<div class="charter-calculator__total-line charter-calculator__total-line--prepare">
						<span>${ this.config.labels?.amountToPrepare || 'Amount to prepare' }</span>
						<span class="charter-calculator__line-value">${ formatEuro( totals.amountToPrepare ) }</span>
					</div>
				</div>
				<p class="charter-calculator__deposit-note">${ this.config.labels?.depositNote || '' }</p>
				<p class="charter-calculator__deposit-explainer">${ this.config.labels?.depositExplainer || '' }</p>
			</section>
		`;
	}

	bindBodyEvents() {
		this.body?.querySelectorAll( '[data-charter-extra-input]' ).forEach( ( input ) => {
			input.addEventListener( 'change', ( event ) => {
				const target = event.target;

				if ( ! ( target instanceof HTMLInputElement ) ) {
					return;
				}

				this.selectedExtras[ target.value ] = target.checked;
				this.render();
			} );
		} );

		this.body?.querySelectorAll( '[data-charter-qty-minus]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const id = button.getAttribute( 'data-charter-qty-minus' ) || '';
				this.updateQuantity( id, -1 );
			} );
		} );

		this.body?.querySelectorAll( '[data-charter-qty-plus]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const id = button.getAttribute( 'data-charter-qty-plus' ) || '';
				this.updateQuantity( id, 1 );
			} );
		} );
	}

	/**
	 * @param {string} id
	 * @param {number} delta
	 */
	updateQuantity( id, delta ) {
		const maxGuests = Number( this.config.maxGuests ) || 10;
		const current = Number( this.quantities[ id ] ) || 0;
		this.quantities[ id ] = Math.max( 0, Math.min( maxGuests, current + delta ) );
		this.render();
	}
}

const EXTRAS_PAGE_SELECTOR = '[data-extras-calculator]';

class ExtrasPageCalculator {
	/**
	 * @param {HTMLElement} root
	 * @param {Record<string, Record<string, unknown>>} configs
	 */
	constructor( root, configs ) {
		this.root = root;
		this.configs = configs;
		this.layout = root.querySelector( '[data-extras-calculator-layout]' );
		this.panel = root.querySelector( '[data-extras-calculator-panel]' );
		this.body = root.querySelector( '[data-extras-calculator-body]' );
		this.routeWrap = root.querySelector( '[data-extras-route-wrap]' );
		this.routeList = root.querySelector( '[data-extras-route-list]' );
		this.packageTabs = root.querySelectorAll( '[data-extras-package-tab]' );
		const defaultTab = root.querySelector( '[data-extras-package-tab].is-active' )
			|| root.querySelector( '[data-extras-package-tab][data-extras-package-simple="false"]' )
			|| root.querySelector( '[data-extras-package-tab]' );
		this.activePackageId = defaultTab?.getAttribute( 'data-extras-package-tab' )
			|| Object.keys( configs )[ 0 ]
			|| '';
		this.config = configs[ this.activePackageId ] || configs[ Object.keys( configs )[ 0 ] ] || null;
		this.activeRouteId = this.config?.defaultRouteId || '';
		this.selectedExtras = {};
		this.quantities = {};

		this.bindPageEvents();

		if ( this.isSimplePackage( this.activePackageId ) ) {
			this.showSimplePackage( this.activePackageId );
			return;
		}

		this.resetState();
		this.updateRouteTabs();
		this.render();
	}

	/**
	 * @param {string} packageId
	 * @return {boolean}
	 */
	isSimplePackage( packageId ) {
		const tab = this.root.querySelector( `[data-extras-package-tab="${ packageId }"]` );

		return tab?.getAttribute( 'data-extras-package-simple' ) === 'true';
	}

	/**
	 * @param {string} packageId
	 */
	showSimplePackage( packageId ) {
		this.activePackageId = packageId;
		this.config = null;

		this.packageTabs.forEach( ( button ) => {
			const isActive = button.getAttribute( 'data-extras-package-tab' ) === packageId;
			button.classList.toggle( 'is-active', isActive );
			button.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
		} );

		this.layout?.classList.add( 'extras-calculator__layout--simple' );
		this.panel?.classList.add( 'hidden' );
		this.panel?.setAttribute( 'hidden', '' );
		this.routeWrap?.classList.add( 'hidden' );
		this.routeWrap?.setAttribute( 'hidden', '' );

		if ( this.routeList ) {
			this.routeList.innerHTML = '';
		}

		if ( this.body ) {
			this.body.innerHTML = '';
		}
	}

	resetState() {
		this.selectedExtras = {};
		this.quantities = {};

		if ( Array.isArray( this.config?.extras ) ) {
			this.config.extras.forEach( ( extra ) => {
				this.selectedExtras[ extra.id ] = false;
			} );
		}

		if ( Array.isArray( this.config?.quantityExtras ) ) {
			this.config.quantityExtras.forEach( ( extra ) => {
				this.quantities[ extra.id ] = 0;
			} );
		}
	}

	bindPageEvents() {
		this.packageTabs.forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const packageId = button.getAttribute( 'data-extras-package-tab' ) || '';

				if ( ! packageId || packageId === this.activePackageId ) {
					return;
				}

				this.selectPackage( packageId );
			} );
		} );
	}

	/**
	 * @param {string} packageId
	 */
	selectPackage( packageId ) {
		if ( this.isSimplePackage( packageId ) ) {
			this.showSimplePackage( packageId );
			return;
		}

		const config = this.configs[ packageId ];

		if ( ! config ) {
			return;
		}

		this.activePackageId = packageId;
		this.config = config;
		this.activeRouteId = config.defaultRouteId || '';

		this.packageTabs.forEach( ( button ) => {
			const isActive = button.getAttribute( 'data-extras-package-tab' ) === packageId;
			button.classList.toggle( 'is-active', isActive );
			button.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
		} );

		this.layout?.classList.remove( 'extras-calculator__layout--simple' );
		this.panel?.classList.remove( 'hidden' );
		this.panel?.removeAttribute( 'hidden' );

		this.resetState();
		this.updateRouteTabs();
		this.render();
	}

	/**
	 * @param {string} routeId
	 */
	setRoute( routeId ) {
		this.activeRouteId = routeId;
		this.updateRouteTabs();
		this.render();
	}

	updateRouteTabs() {
		if ( ! this.routeWrap || ! this.routeList ) {
			return;
		}

		const routes = Array.isArray( this.config?.routes ) ? this.config.routes : [];
		const showRoutes = !! this.config?.routeChoice && routes.length > 1;

		this.routeWrap.classList.toggle( 'hidden', ! showRoutes );
		this.routeWrap.toggleAttribute( 'hidden', ! showRoutes );

		if ( ! showRoutes ) {
			this.routeList.innerHTML = '';
			return;
		}

		this.routeList.innerHTML = routes
			.map( ( route ) => {
				const routeId = route.id || '';
				const isActive = routeId === this.activeRouteId;
				const tabNumber = String( route.number || '' ).trim();
				const tabName = String( route.name || '' ).trim();
				const tabLabel = `${ tabNumber } ${ tabName }`.trim();

				return `
					<button
						type="button"
						class="extras-calculator__route-tab${ isActive ? ' is-active' : '' }"
						data-extras-route-tab="${ routeId }"
						aria-pressed="${ isActive ? 'true' : 'false' }"
					>${ tabLabel }</button>
				`;
			} )
			.join( '' );

		this.routeList.querySelectorAll( '[data-extras-route-tab]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const routeId = button.getAttribute( 'data-extras-route-tab' ) || this.activeRouteId;
				this.setRoute( routeId );
			} );
		} );
	}

	render() {
		if ( ! this.body || ! this.config ) {
			return;
		}

		const totals = calculateTotals(
			this.config,
			this.activeRouteId,
			this.selectedExtras,
			this.quantities
		);

		const engine = new InlineCharterCalculator( document.createElement( 'article' ), document.createElement( 'div' ), this.config );
		engine.body = this.body;
		engine.selectedExtras = this.selectedExtras;
		engine.quantities = this.quantities;

		this.body.innerHTML = `
			${ engine.buildPriceBreakdownSection( totals ) }
			${ engine.buildExtrasSection() }
			${ engine.buildQuantitySection() }
			${ engine.buildTotalsSection( totals ) }
		`;

		this.body.querySelectorAll( '[data-charter-extra-input]' ).forEach( ( input ) => {
			input.addEventListener( 'change', ( event ) => {
				const target = event.target;

				if ( ! ( target instanceof HTMLInputElement ) ) {
					return;
				}

				this.selectedExtras[ target.value ] = target.checked;
				this.render();
			} );
		} );

		this.body.querySelectorAll( '[data-charter-qty-minus]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const id = button.getAttribute( 'data-charter-qty-minus' ) || '';
				this.updateQuantity( id, -1 );
			} );
		} );

		this.body.querySelectorAll( '[data-charter-qty-plus]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const id = button.getAttribute( 'data-charter-qty-plus' ) || '';
				this.updateQuantity( id, 1 );
			} );
		} );
	}

	/**
	 * @param {string} id
	 * @param {number} delta
	 */
	updateQuantity( id, delta ) {
		const maxGuests = Number( this.config?.maxGuests ) || 10;
		const current = Number( this.quantities[ id ] ) || 0;
		this.quantities[ id ] = Math.max( 0, Math.min( maxGuests, current + delta ) );
		this.render();
	}
}

/**
 * Initialize the Extras page package calculator.
 */
export function initExtrasPageCalculator() {
	const configNode = document.getElementById( 'seahivez-extras-calculator-config' );
	const root = document.querySelector( EXTRAS_PAGE_SELECTOR );

	if ( ! configNode || ! ( root instanceof HTMLElement ) ) {
		return;
	}

	let configs = {};

	try {
		configs = JSON.parse( configNode.textContent || '{}' );
	} catch {
		return;
	}

	new ExtrasPageCalculator( root, configs );
}

/**
 * Initialize inline charter calculators on homepage cards.
 */
export function initCharterCalculator() {
	const configNode = document.getElementById( 'seahivez-charter-calculator-config' );

	if ( ! configNode ) {
		return;
	}

	let configs = {};

	try {
		configs = JSON.parse( configNode.textContent || '{}' );
	} catch {
		return;
	}

	document.querySelectorAll( CARD_SELECTOR ).forEach( ( charterCard ) => {
		const packageKey = charterCard.getAttribute( 'data-package-key' ) || '';
		const config = configs[ packageKey ];
		const article = charterCard.closest( '[data-experience-package]' );

		if ( ! config || ! ( article instanceof HTMLElement ) ) {
			return;
		}

		new InlineCharterCalculator( article, charterCard, config );
	} );
}
