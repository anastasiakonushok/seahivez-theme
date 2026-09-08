<?php
/**
 * Schema.org JSON-LD structured data.
 *
 * @package seahivez-theme
 */

/**
 * Whether structured data output is enabled.
 *
 * @return bool
 */
function seahivez_schema_is_enabled() {
	/**
	 * Filter Schema.org JSON-LD output.
	 *
	 * @param bool $enabled Whether schema markup is printed.
	 */
	return (bool) apply_filters( 'seahivez_schema_enabled', ! is_admin() );
}

/**
 * Build a canonical entity ID for @id references.
 *
 * @param string $fragment Entity fragment, e.g. organization.
 * @return string
 */
function seahivez_schema_entity_id( $fragment ) {
	return trailingslashit( home_url( '/' ) ) . '#' . sanitize_key( $fragment );
}

/**
 * Social profile URLs for sameAs.
 *
 * @return array<int, string>
 */
function seahivez_schema_get_same_as() {
	$contact = seahivez_get_social_contact_data();
	$urls    = array(
		seahivez_get_instagram_url(),
		seahivez_get_whatsapp_url(),
		! empty( $contact['telegram_url'] ) ? $contact['telegram_url'] : '',
	);

	$urls = array_values(
		array_filter(
			array_unique( array_map( 'esc_url_raw', $urls ) )
		)
	);

	return $urls;
}

/**
 * Organization node.
 *
 * @return array<string, mixed>
 */
function seahivez_schema_get_organization() {
	$logo     = seahivez_get_logo_assets();
	$contact  = seahivez_get_social_contact_data();
	$footer   = function_exists( 'seahivez_get_footer_settings' ) ? seahivez_get_footer_settings() : array();
	$logo_url = ! empty( $logo['dark'] ) ? $logo['dark'] : ( ! empty( $logo['light'] ) ? $logo['light'] : '' );

	$organization = array(
		'@type' => 'Organization',
		'@id'   => seahivez_schema_entity_id( 'organization' ),
		'name'  => get_bloginfo( 'name', 'display' ),
		'url'   => home_url( '/' ),
	);

	if ( $logo_url ) {
		$organization['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		);
	}

	if ( ! empty( $footer['description'] ) ) {
		$organization['description'] = wp_strip_all_tags( (string) $footer['description'] );
	}

	$same_as = seahivez_schema_get_same_as();

	if ( ! empty( $same_as ) ) {
		$organization['sameAs'] = $same_as;
	}

	if ( ! empty( $contact['email'] ) || ! empty( $contact['phone'] ) ) {
		$organization['contactPoint'] = array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer service',
			'availableLanguage' => array( 'English', 'Spanish', 'German' ),
		);

		if ( ! empty( $contact['email'] ) ) {
			$organization['contactPoint']['email'] = sanitize_email( $contact['email'] );
		}

		if ( ! empty( $contact['phone'] ) ) {
			$organization['contactPoint']['telephone'] = preg_replace( '/\s+/', ' ', trim( (string) $contact['phone'] ) );
		}
	}

	return $organization;
}

/**
 * Local business node for the charter operation.
 *
 * @return array<string, mixed>
 */
function seahivez_schema_get_local_business() {
	$contact = seahivez_get_social_contact_data();
	$footer  = function_exists( 'seahivez_get_footer_settings' ) ? seahivez_get_footer_settings() : array();
	$port    = seahivez_get_port_location();
	$logo    = seahivez_get_logo_assets();

	$business = array(
		'@type'       => array( 'LocalBusiness', 'ProfessionalService' ),
		'@id'         => seahivez_schema_entity_id( 'localbusiness' ),
		'name'        => get_bloginfo( 'name', 'display' ),
		'url'         => home_url( '/' ),
		'priceRange'  => '€€€',
		'areaServed'  => array(
			'@type' => 'AdministrativeArea',
			'name'  => 'Mallorca, Spain',
		),
		'geo'         => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $port['lat'],
			'longitude' => $port['lng'],
		),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'addressLocality' => "S'Arenal",
			'addressRegion'   => 'Mallorca',
			'addressCountry'  => 'ES',
		),
		'parentOrganization' => array(
			'@id' => seahivez_schema_entity_id( 'organization' ),
		),
	);

	if ( ! empty( $footer['description'] ) ) {
		$business['description'] = wp_strip_all_tags( (string) $footer['description'] );
	}

	if ( ! empty( $logo['dark'] ) ) {
		$business['image'] = $logo['dark'];
	}

	if ( ! empty( $contact['phone'] ) ) {
		$business['telephone'] = preg_replace( '/\s+/', ' ', trim( (string) $contact['phone'] ) );
	}

	if ( ! empty( $contact['email'] ) ) {
		$business['email'] = sanitize_email( $contact['email'] );
	}

	return $business;
}

/**
 * WebSite node.
 *
 * @return array<string, mixed>
 */
function seahivez_schema_get_website() {
	return array(
		'@type'     => 'WebSite',
		'@id'       => seahivez_schema_entity_id( 'website' ),
		'url'       => home_url( '/' ),
		'name'      => get_bloginfo( 'name', 'display' ),
		'inLanguage'=> get_bloginfo( 'language' ),
		'publisher' => array(
			'@id' => seahivez_schema_entity_id( 'organization' ),
		),
	);
}

/**
 * Current web page node.
 *
 * @return array<string, mixed>|null
 */
function seahivez_schema_get_web_page() {
	if ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_singular() ) {
		$url = get_permalink();
	} elseif ( is_home() ) {
		$url = function_exists( 'seahivez_get_posts_page_url' ) ? seahivez_get_posts_page_url() : home_url( '/news/' );
	} else {
		return null;
	}

	if ( ! $url ) {
		return null;
	}

	$page = array(
		'@type'    => is_front_page() ? 'WebPage' : ( is_singular( 'post' ) ? 'WebPage' : 'WebPage' ),
		'@id'      => trailingslashit( $url ) . '#webpage',
		'url'      => $url,
		'name'     => wp_get_document_title(),
		'isPartOf' => array(
			'@id' => seahivez_schema_entity_id( 'website' ),
		),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	if ( is_front_page() ) {
		$page['about'] = array(
			'@id' => seahivez_schema_entity_id( 'localbusiness' ),
		);
	}

	$description = '';

	if ( is_singular() ) {
		$description = get_the_excerpt();
	}

	if ( '' === $description && function_exists( 'seahivez_get_footer_settings' ) ) {
		$footer      = seahivez_get_footer_settings();
		$description = ! empty( $footer['description'] ) ? (string) $footer['description'] : '';
	}

	if ( $description ) {
		$page['description'] = wp_strip_all_tags( $description );
	}

	return $page;
}

/**
 * Package service node for package singles.
 *
 * @return array<string, mixed>|null
 */
function seahivez_schema_get_package_service() {
	if ( ! is_singular( 'package' ) || ! function_exists( 'seahivez_get_package_card_data' ) ) {
		return null;
	}

	$card = seahivez_get_package_card_data( get_the_ID() );
	$url  = get_permalink();

	$service = array(
		'@type'       => 'Service',
		'@id'         => trailingslashit( $url ) . '#service',
		'name'        => $card['title'],
		'description' => wp_strip_all_tags( $card['description'] ),
		'url'         => $url,
		'provider'    => array(
			'@id' => seahivez_schema_entity_id( 'localbusiness' ),
		),
		'areaServed'  => array(
			'@type' => 'AdministrativeArea',
			'name'  => 'Mallorca, Spain',
		),
	);

	if ( ! empty( $card['image'] ) ) {
		$service['image'] = $card['image'];
	}

	if ( ! empty( $card['price'] ) && is_numeric( $card['price'] ) ) {
		$service['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (string) $card['price'],
			'priceCurrency' => 'EUR',
			'url'           => $url,
			'availability'  => 'https://schema.org/InStock',
		);
	}

	return $service;
}

/**
 * BlogPosting node for news articles.
 *
 * @return array<string, mixed>|null
 */
function seahivez_schema_get_blog_posting() {
	if ( ! is_singular( 'post' ) ) {
		return null;
	}

	$post_id = get_the_ID();
	$url     = get_permalink( $post_id );

	$article = array(
		'@type'            => 'BlogPosting',
		'@id'              => trailingslashit( $url ) . '#article',
		'headline'         => get_the_title( $post_id ),
		'url'              => $url,
		'datePublished'    => get_the_date( DATE_W3C, $post_id ),
		'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
		'inLanguage'       => get_bloginfo( 'language' ),
		'mainEntityOfPage' => array(
			'@id' => trailingslashit( $url ) . '#webpage',
		),
		'author'           => array(
			'@type' => 'Organization',
			'@id'   => seahivez_schema_entity_id( 'organization' ),
		),
		'publisher'        => array(
			'@id' => seahivez_schema_entity_id( 'organization' ),
		),
	);

	$excerpt = get_the_excerpt( $post_id );

	if ( $excerpt ) {
		$article['description'] = wp_strip_all_tags( $excerpt );
	}

	$image = get_the_post_thumbnail_url( $post_id, 'full' );

	if ( $image ) {
		$article['image'] = array(
			'@type' => 'ImageObject',
			'url'   => $image,
		);
	}

	return $article;
}

/**
 * FAQPage node for the FAQ template.
 *
 * @return array<string, mixed>|null
 */
function seahivez_schema_get_faq_page() {
	if ( ! is_page_template( 'page-faq.php' ) || ! function_exists( 'seahivez_get_faq_page_groups' ) ) {
		return null;
	}

	$groups    = seahivez_get_faq_page_groups();
	$entities  = array();

	foreach ( $groups as $group ) {
		if ( empty( $group['items'] ) || ! is_array( $group['items'] ) ) {
			continue;
		}

		foreach ( $group['items'] as $item ) {
			$question = isset( $item['question'] ) ? wp_strip_all_tags( (string) $item['question'] ) : '';
			$answer   = isset( $item['answer'] ) ? wp_strip_all_tags( (string) $item['answer'] ) : '';

			if ( '' === $question || '' === $answer ) {
				continue;
			}

			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $answer,
				),
			);
		}
	}

	if ( empty( $entities ) ) {
		return null;
	}

	$url = function_exists( 'seahivez_get_faq_page_url' ) ? seahivez_get_faq_page_url() : get_permalink();

	return array(
		'@type'      => 'FAQPage',
		'@id'        => trailingslashit( $url ) . '#faq',
		'url'        => $url,
		'mainEntity' => $entities,
	);
}

/**
 * Build the JSON-LD graph for the current request.
 *
 * @return array<string, mixed>
 */
function seahivez_schema_get_graph() {
	$graph = array(
		seahivez_schema_get_organization(),
		seahivez_schema_get_local_business(),
		seahivez_schema_get_website(),
	);

	$web_page = seahivez_schema_get_web_page();

	if ( $web_page ) {
		$graph[] = $web_page;
	}

	$package_service = seahivez_schema_get_package_service();

	if ( $package_service ) {
		$graph[] = $package_service;
	}

	$blog_posting = seahivez_schema_get_blog_posting();

	if ( $blog_posting ) {
		$graph[] = $blog_posting;
	}

	$faq_page = seahivez_schema_get_faq_page();

	if ( $faq_page ) {
		$graph[] = $faq_page;
	}

	/**
	 * Filter Schema.org graph nodes before output.
	 *
	 * @param array<int, array<string, mixed>> $graph Structured data nodes.
	 */
	$graph = apply_filters( 'seahivez_schema_graph', $graph );

	return array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( array_filter( $graph ) ),
	);
}

/**
 * Print Schema.org JSON-LD in the document head.
 */
function seahivez_render_schema_org_markup() {
	if ( ! seahivez_schema_is_enabled() ) {
		return;
	}

	$data = seahivez_schema_get_graph();

	if ( empty( $data['@graph'] ) ) {
		return;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'seahivez_render_schema_org_markup', 20 );
