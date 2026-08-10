<?php
/**
 * Language flag helpers for specifications and future ACF fields.
 *
 * Maps language identifiers (en, es, de) to flag assets and display codes.
 *
 * @package seahivez-theme
 */

/**
 * Registry of allowed language identifiers.
 *
 * Keys are ACF-ready slugs. Values include code, label, and flag filename.
 *
 * @return array<string, array{code: string, label: string, flag: string}>
 */
function seahivez_get_allowed_languages() {
	return array(
		'en' => array(
			'code'  => 'EN',
			'label' => __( 'English', 'seahivez-theme' ),
			'flag'  => 'gb.svg',
		),
		'es' => array(
			'code'  => 'ES',
			'label' => __( 'Spanish', 'seahivez-theme' ),
			'flag'  => 'es.svg',
		),
		'de' => array(
			'code'  => 'DE',
			'label' => __( 'German', 'seahivez-theme' ),
			'flag'  => 'de.svg',
		),
	);
}

/**
 * Resolve a language slug to a flag image URI.
 *
 * @param string $language_code Language identifier (en, es, de).
 * @return string|false
 */
function seahivez_get_language_flag_uri( $language_code ) {
	$language_code = sanitize_key( $language_code );
	$allowed       = seahivez_get_allowed_languages();

	if ( ! isset( $allowed[ $language_code ] ) ) {
		return false;
	}

	$relative = 'assets/images/flags/' . $allowed[ $language_code ]['flag'];
	$path     = get_theme_file_path( $relative );

	if ( ! file_exists( $path ) ) {
		return false;
	}

	return get_theme_file_uri( $relative );
}

/**
 * Normalize language identifiers into display-ready items.
 *
 * Accepts ACF-style identifiers (en, es, de) or a comma-separated string.
 *
 * @param array|string $languages Language identifiers.
 * @return array<int, array{code: string, label: string, flag_url: string}>
 */
function seahivez_normalize_languages( $languages ) {
	if ( is_string( $languages ) ) {
		$languages = preg_split( '/[\s,]+/', $languages, -1, PREG_SPLIT_NO_EMPTY );
	}

	if ( ! is_array( $languages ) ) {
		return array();
	}

	$allowed = seahivez_get_allowed_languages();
	$items   = array();

	foreach ( $languages as $language ) {
		$slug = sanitize_key( (string) $language );

		// Allow EN/ES/DE codes as well as en/es/de.
		if ( ! isset( $allowed[ $slug ] ) ) {
			$slug = strtolower( $slug );
		}

		if ( ! isset( $allowed[ $slug ] ) ) {
			continue;
		}

		$flag_url = seahivez_get_language_flag_uri( $slug );

		if ( ! $flag_url ) {
			continue;
		}

		$items[] = array(
			'code'     => $allowed[ $slug ]['code'],
			'label'    => $allowed[ $slug ]['label'],
			'flag_url' => $flag_url,
		);
	}

	return $items;
}

/**
 * Echo language flag chips markup.
 *
 * @param array|string $languages Language identifiers.
 * @return void
 */
function seahivez_render_language_chips( $languages ) {
	$items = seahivez_normalize_languages( $languages );

	if ( empty( $items ) ) {
		return;
	}
	?>
	<ul class="language-chips" role="list">
		<?php foreach ( $items as $item ) : ?>
			<li class="language-chip">
				<img
					class="language-chip__flag"
					src="<?php echo esc_url( $item['flag_url'] ); ?>"
					alt=""
					width="24"
					height="16"
					loading="lazy"
					decoding="async"
					aria-hidden="true"
				>
				<span class="language-chip__code"><?php echo esc_html( $item['code'] ); ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $item['label'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}
