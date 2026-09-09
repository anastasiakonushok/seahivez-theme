<?php
/**
 * ACF local field group registration.
 *
 * Synced to acf-json/ when saved in admin.
 *
 * @package seahivez-theme
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group(
	array(
		'key'                   => 'group_shvz_theme_settings',
		'title'                 => 'Theme Settings',
		'fields'                => array(
			array(
				'key'   => 'field_shvz_tab_header',
				'label' => 'Header',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'        => 'field_shvz_header',
				'label'      => 'Header',
				'name'       => 'header',
				'type'       => 'group',
				'layout'     => 'block',
				'sub_fields' => array(
					array(
						'key'           => 'field_shvz_header_logo_light',
						'label'         => 'Logo (light)',
						'name'          => 'logo_light',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'           => 'field_shvz_header_logo_dark',
						'label'         => 'Logo (dark)',
						'name'          => 'logo_dark',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'   => 'field_shvz_header_logo_alt',
						'label' => 'Logo alt text',
						'name'  => 'logo_alt',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_shvz_header_cta',
						'label' => 'Header CTA button',
						'name'  => 'cta_button',
						'type'  => 'link',
					),
				),
			),
			array(
				'key'   => 'field_shvz_tab_footer',
				'label' => 'Footer',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'        => 'field_shvz_footer',
				'label'      => 'Footer',
				'name'       => 'footer',
				'type'       => 'group',
				'layout'     => 'block',
				'sub_fields' => array(
					array(
						'key'   => 'field_shvz_footer_description',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 3,
					),
					array(
						'key'   => 'field_shvz_footer_book_heading',
						'label' => 'Book block heading',
						'name'  => 'book_heading',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_shvz_footer_book_description',
						'label' => 'Book block description',
						'name'  => 'book_description',
						'type'  => 'textarea',
						'rows'  => 3,
					),
					array(
						'key'   => 'field_shvz_footer_book_cta',
						'label' => 'Book block button',
						'name'  => 'book_cta',
						'type'  => 'link',
					),
					array(
						'key'   => 'field_shvz_footer_copyright',
						'label' => 'Copyright override',
						'name'  => 'copyright',
						'type'  => 'text',
						'instructions' => 'Leave empty to use default © year + site name.',
					),
				),
			),
			array(
				'key'   => 'field_shvz_tab_social',
				'label' => 'Social & Contact',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'        => 'field_shvz_social_contact',
				'label'      => 'Social & Contact',
				'name'       => 'social_contact',
				'type'       => 'group',
				'layout'     => 'block',
				'sub_fields' => array(
					array(
						'key'          => 'field_shvz_social_links',
						'label'        => 'Social links',
						'name'         => 'social_links',
						'type'         => 'repeater',
						'layout'       => 'block',
						'button_label' => 'Add social link',
						'instructions' => 'Header, footer, contact, FAQ and other social rows use this list site-wide.',
						'sub_fields'   => array(
							array(
								'key'           => 'field_shvz_social_links_icon',
								'label'         => 'Icon',
								'name'          => 'icon',
								'type'          => 'select',
								'required'      => 1,
								'choices'       => array(
									'instagram' => 'Instagram',
									'whatsapp'  => 'WhatsApp',
									'telegram'  => 'Telegram',
								),
								'return_format' => 'value',
							),
							array(
								'key'      => 'field_shvz_social_links_url',
								'label'    => 'URL',
								'name'     => 'url',
								'type'     => 'url',
								'required' => 1,
							),
							array(
								'key'          => 'field_shvz_social_links_label',
								'label'        => 'Label (optional)',
								'name'         => 'label',
								'type'         => 'text',
								'instructions' => 'Accessibility label. Defaults to the icon name.',
							),
							array(
								'key'   => 'field_shvz_social_links_subtitle',
								'label' => 'Subtitle (optional)',
								'name'  => 'subtitle',
								'type'  => 'text',
							),
						),
					),
					array(
						'key'          => 'field_shvz_social_instagram_handle',
						'label'        => 'Instagram handle',
						'name'         => 'instagram_handle',
						'type'         => 'text',
						'instructions' => 'Optional display handle (e.g. @seahivez).',
					),
					array(
						'key'          => 'field_shvz_social_whatsapp_number',
						'label'        => 'WhatsApp number',
						'name'         => 'whatsapp_number',
						'type'         => 'text',
						'instructions' => 'Used when a WhatsApp link is built from the phone number.',
					),
					array(
						'key'   => 'field_shvz_social_phone',
						'label' => 'Phone',
						'name'  => 'phone',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_shvz_social_email',
						'label' => 'Email',
						'name'  => 'email',
						'type'  => 'email',
					),
					array(
						'key'   => 'field_shvz_social_address',
						'label' => 'Address',
						'name'  => 'address',
						'type'  => 'text',
					),
				),
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'theme-settings',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	)
);

/**
 * Build flexible layout field definitions.
 *
 * @return array<string, array<string, mixed>>
 */
function seahivez_acf_get_page_section_layouts() {
	return array(
		'layout_shvz_hero' => array(
			'key'        => 'layout_shvz_hero',
			'name'       => 'hero',
			'label'      => 'Hero',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_hero_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_hero_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_hero_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_shvz_hero_primary', 'label' => 'Primary button', 'name' => 'primary_button', 'type' => 'link' ),
				array( 'key' => 'field_shvz_hero_secondary', 'label' => 'Secondary button', 'name' => 'secondary_button', 'type' => 'link' ),
				array( 'key' => 'field_shvz_hero_image', 'label' => 'Background image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array' ),
				array( 'key' => 'field_shvz_hero_image_alt', 'label' => 'Image alt', 'name' => 'image_alt', 'type' => 'text' ),
			),
		),
		'layout_shvz_specs_bar' => array(
			'key'        => 'layout_shvz_specs_bar',
			'name'       => 'specs_bar',
			'label'      => 'Quick specs bar',
			'display'    => 'block',
			'sub_fields' => array(
				array(
					'key'          => 'field_shvz_specs_items',
					'label'        => 'Items',
					'name'         => 'items',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Add spec',
					'sub_fields'   => seahivez_get_acf_spec_item_sub_fields( 'field_shvz_specs' ),
				),
			),
		),
		'layout_shvz_about' => array(
			'key'        => 'layout_shvz_about',
			'name'       => 'about',
			'label'      => 'About',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_about_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_about_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array(
					'key'          => 'field_shvz_about_paragraphs',
					'label'        => 'Paragraphs',
					'name'         => 'paragraphs',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add paragraph',
					'sub_fields'   => array(
						array( 'key' => 'field_shvz_about_paragraph_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
					),
				),
				array( 'key' => 'field_shvz_about_link', 'label' => 'Link', 'name' => 'link', 'type' => 'link' ),
				array( 'key' => 'field_shvz_about_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array' ),
				array( 'key' => 'field_shvz_about_image_alt', 'label' => 'Image alt', 'name' => 'image_alt', 'type' => 'text' ),
			),
		),
		'layout_shvz_specifications' => array(
			'key'        => 'layout_shvz_specifications',
			'name'       => 'specifications',
			'label'      => 'Specifications',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_specs_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_specs_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_specs_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
				array(
					'key'          => 'field_shvz_specs_groups',
					'label'        => 'Groups',
					'name'         => 'groups',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add group',
					'sub_fields'   => array(
						array( 'key' => 'field_shvz_specs_group_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
						array(
							'key'          => 'field_shvz_specs_group_items',
							'label'        => 'Items',
							'name'         => 'items',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Add item',
							'sub_fields'   => seahivez_get_acf_spec_item_sub_fields( 'field_shvz_specs_group' ),
						),
					),
				),
			),
		),
		'layout_shvz_experiences' => array(
			'key'        => 'layout_shvz_experiences',
			'name'       => 'experiences',
			'label'      => 'Experiences',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_exp_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_exp_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array(
					'key'           => 'field_shvz_exp_packages',
					'label'         => 'Packages',
					'name'          => 'packages',
					'type'          => 'relationship',
					'post_type'     => array( 'package' ),
					'return_format' => 'object',
					'filters'       => array( 'search', 'post_type' ),
					'elements'      => array( 'featured_image' ),
				),
			),
		),
		'layout_shvz_toys_extras' => array(
			'key'        => 'layout_shvz_toys_extras',
			'name'       => 'toys_extras',
			'label'      => 'Toys & Extras',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_extras_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_extras_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_extras_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array(
					'key'          => 'field_shvz_extras_included',
					'label'        => 'Included items',
					'name'         => 'included',
					'type'         => 'repeater',
					'button_label' => 'Add included item',
					'sub_fields'   => array(
						seahivez_get_acf_icon_field_schema( 'field_shvz_extras_inc_icon' ),
						array( 'key' => 'field_shvz_extras_inc_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					),
				),
				array(
					'key'          => 'field_shvz_extras_paid',
					'label'        => 'Paid extras',
					'name'         => 'paid',
					'type'         => 'repeater',
					'button_label' => 'Add paid extra',
					'sub_fields'   => array(
						seahivez_get_acf_icon_field_schema( 'field_shvz_extras_paid_icon' ),
						array( 'key' => 'field_shvz_extras_paid_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
						array( 'key' => 'field_shvz_extras_paid_price', 'label' => 'Price', 'name' => 'price', 'type' => 'text' ),
					),
				),
				array(
					'key'          => 'field_shvz_extras_amenities',
					'label'        => 'Amenities',
					'name'         => 'amenities',
					'type'         => 'repeater',
					'button_label' => 'Add amenity',
					'sub_fields'   => array(
						array( 'key' => 'field_shvz_extras_amenity_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text' ),
					),
				),
			),
		),
		'layout_shvz_gallery' => array(
			'key'        => 'layout_shvz_gallery',
			'name'       => 'gallery',
			'label'      => 'Gallery',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_gallery_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_gallery_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_gallery_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_shvz_gallery_cta', 'label' => 'CTA link', 'name' => 'cta_link', 'type' => 'link' ),
				array(
					'key'           => 'field_shvz_gallery_images',
					'label'         => 'Images',
					'name'          => 'images',
					'type'          => 'gallery',
					'return_format' => 'array',
					'preview_size'  => 'medium',
				),
			),
		),
		'layout_shvz_faq' => array(
			'key'        => 'layout_shvz_faq',
			'name'       => 'faq',
			'label'      => 'FAQ',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_faq_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_faq_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_faq_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_shvz_faq_cta_heading', 'label' => 'CTA heading', 'name' => 'cta_heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_faq_cta_link', 'label' => 'CTA link', 'name' => 'cta_link', 'type' => 'link' ),
				array(
					'key'          => 'field_shvz_faq_items',
					'label'        => 'Questions',
					'name'         => 'items',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add question',
					'sub_fields'   => array(
						array( 'key' => 'field_shvz_faq_question', 'label' => 'Question', 'name' => 'question', 'type' => 'text' ),
						array( 'key' => 'field_shvz_faq_answer', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ),
					),
				),
			),
		),
		'layout_shvz_news' => array(
			'key'        => 'layout_shvz_news',
			'name'       => 'news',
			'label'      => 'News',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_news_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
				array( 'key' => 'field_shvz_news_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_news_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_shvz_news_cta', 'label' => 'CTA link', 'name' => 'cta_link', 'type' => 'link' ),
				array( 'key' => 'field_shvz_news_count', 'label' => 'Posts count', 'name' => 'posts_count', 'type' => 'number', 'default_value' => 3, 'min' => 1, 'max' => 12 ),
			),
		),
		'layout_shvz_location_cta' => array(
			'key'        => 'layout_shvz_location_cta',
			'name'       => 'location_cta',
			'label'      => 'Location & Booking CTA',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_loc_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
				array( 'key' => 'field_shvz_loc_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_shvz_loc_cta', 'label' => 'CTA button', 'name' => 'cta_button', 'type' => 'link' ),
			),
			'instructions' => 'Phone, email and location are taken from Theme Settings → Social & Contact.',
		),
		'layout_shvz_content' => array(
			'key'        => 'layout_shvz_content',
			'name'       => 'content',
			'label'      => 'Content',
			'display'    => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_shvz_content_body', 'label' => 'Content', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ),
			),
		),
	);
}

acf_add_local_field_group(
	array(
		'key'                   => 'group_shvz_page_content',
		'title'                 => 'Page Content',
		'fields'                => array(
			array(
				'key'          => 'field_shvz_sections',
				'label'        => 'Sections',
				'name'         => 'sections',
				'type'         => 'flexible_content',
				'button_label' => 'Add section',
				'layouts'      => seahivez_acf_get_page_section_layouts(),
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-flexible.php',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	)
);
