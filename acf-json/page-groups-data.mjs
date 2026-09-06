/**
 * ACF field group definitions for interior page templates.
 * Imported by generate-import.mjs
 */

export function buildPageFieldGroups({
	fieldGroupBase,
	textField,
	textareaField,
	emailField,
	urlField,
	linkField,
	imageField,
	iconField,
	groupField,
	repeaterField,
	selectField,
	wysiwygField,
	galleryField,
	trueFalseField,
	specIconChoices,
	toyIconChoices,
}) {
	const template = (file) => [[{ param: 'page_template', operator: '==', value: file }]];

	function heroGroup(prefix) {
		return groupField(`${prefix}_hero`, 'Hero', 'hero', [
			textField(`${prefix}_hero_eyebrow`, 'Eyebrow', 'eyebrow'),
			textField(`${prefix}_hero_heading`, 'Heading', 'heading'),
			textareaField(`${prefix}_hero_description`, 'Description', 'description', { rows: 3 }),
			imageField(`${prefix}_hero_image`, 'Background image', 'image'),
			textField(`${prefix}_hero_image_alt`, 'Image alt', 'image_alt'),
			trueFalseField(`${prefix}_hero_compact`, 'Compact height', 'compact'),
		]);
	}

	function bookingCtaGroup(prefix) {
		return groupField(
			`${prefix}_booking_cta`,
			'Booking CTA',
			'booking_cta',
			[
				textField(`${prefix}_cta_heading`, 'Heading', 'heading'),
				textareaField(`${prefix}_cta_description`, 'Description', 'description', { rows: 3 }),
				linkField(`${prefix}_cta_button`, 'CTA button', 'cta_button'),
			],
			{
				instructions:
					'Phone, email and location are taken from Theme Settings → Social & Contact.',
			}
		);
	}

	function specGroupItemsRepeater(prefix) {
		return repeaterField(
			`${prefix}_spec_group_items`,
			'Items',
			'items',
			[
				iconField(`${prefix}_spec_icon`, 'Icon', 'icon'),
				textField(`${prefix}_spec_label`, 'Label', 'label'),
				textField(`${prefix}_spec_value`, 'Value', 'value'),
			],
			{ button_label: 'Add item' }
		);
	}

	function specGroupsRepeater(prefix) {
		return repeaterField(
			`${prefix}_spec_groups`,
			'Groups',
			'groups',
			[
				textField(`${prefix}_spec_group_title`, 'Title', 'title'),
				specGroupItemsRepeater(`${prefix}_group`),
			],
			{ layout: 'block', button_label: 'Add group' }
		);
	}

	function experiencePackagesRepeater(prefix) {
		return repeaterField(
			`${prefix}_packages`,
			'Packages',
			'packages',
			[
				textField(`${prefix}_pkg_title`, 'Title', 'title'),
				textField(`${prefix}_pkg_duration`, 'Duration', 'duration'),
				textField(`${prefix}_pkg_time_slot`, 'Time slot', 'time_slot'),
				textField(`${prefix}_pkg_price`, 'Price', 'price'),
				textareaField(`${prefix}_pkg_description`, 'Description', 'description', { rows: 3 }),
				imageField(`${prefix}_pkg_image`, 'Image', 'image'),
				linkField(`${prefix}_pkg_link`, 'Link', 'link'),
			],
			{ layout: 'block', button_label: 'Add package' }
		);
	}

	const faqPageGroup = fieldGroupBase(
		'group_shvz_faq_page',
		'FAQ Page',
		[
			heroGroup('field_shvz_faqp'),
			groupField('field_shvz_faqp_intro', 'FAQ intro (sidebar)', 'faq_intro', [
				textField('field_shvz_faqp_intro_heading', 'Heading', 'heading'),
				textareaField('field_shvz_faqp_intro_description', 'Description', 'description', { rows: 3 }),
				textField('field_shvz_faqp_intro_cta_heading', 'CTA heading', 'cta_heading'),
				linkField('field_shvz_faqp_intro_cta_link', 'CTA link', 'cta_link'),
			]),
			repeaterField(
				'field_shvz_faqp_groups',
				'FAQ groups',
				'faq_groups',
				[
					textField('field_shvz_faqp_group_title', 'Group title', 'title'),
					repeaterField(
						'field_shvz_faqp_group_items',
						'Questions',
						'items',
						[
							textField('field_shvz_faqp_question', 'Question', 'question'),
							wysiwygField('field_shvz_faqp_answer', 'Answer', 'answer', { tabs: 'visual', toolbar: 'basic', media_upload: 0 }),
						],
						{ layout: 'block', button_label: 'Add question' }
					),
				],
				{ layout: 'block', button_label: 'Add group' }
			),
			bookingCtaGroup('field_shvz_faqp'),
		],
		template('page-faq.php')
	);

	const yachtPageGroup = fieldGroupBase(
		'group_shvz_yacht_page',
		'The Yacht Page',
		[
			heroGroup('field_shvz_yacht'),
			groupField('field_shvz_yacht_intro', 'Intro', 'intro', [
				textField('field_shvz_yacht_intro_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_yacht_intro_heading', 'Heading', 'heading'),
				repeaterField(
					'field_shvz_yacht_intro_paragraphs',
					'Paragraphs',
					'paragraphs',
					[textareaField('field_shvz_yacht_intro_text', 'Text', 'text', { rows: 3 })],
					{ layout: 'block', button_label: 'Add paragraph' }
				),
				imageField('field_shvz_yacht_intro_image', 'Image', 'image'),
				textField('field_shvz_yacht_intro_image_alt', 'Image alt', 'image_alt'),
			]),
			groupField('field_shvz_yacht_specifications', 'Specifications', 'specifications', [
				textField('field_shvz_yacht_specs_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_yacht_specs_heading', 'Heading', 'heading'),
				specGroupsRepeater('field_shvz_yacht'),
			]),
			repeaterField(
				'field_shvz_yacht_layout_sections',
				'Layout sections',
				'layout_sections',
				[
					textField('field_shvz_yacht_layout_id', 'Section ID', 'section_id'),
					textField('field_shvz_yacht_layout_eyebrow', 'Eyebrow', 'eyebrow'),
					textField('field_shvz_yacht_layout_heading', 'Heading', 'heading'),
					textareaField('field_shvz_yacht_layout_description', 'Description', 'description', { rows: 3 }),
					textField('field_shvz_yacht_layout_list_heading', 'List heading', 'list_heading'),
					imageField('field_shvz_yacht_layout_plan', 'Deck plan image', 'plan_image'),
					textField('field_shvz_yacht_layout_plan_alt', 'Plan alt', 'plan_alt'),
					selectField('field_shvz_yacht_layout_plan_frame', 'Plan frame', 'plan_frame', { light: 'Light', dark: 'Dark' }),
					selectField('field_shvz_yacht_layout_background', 'Background class', 'background', {
						'bg-warm-white': 'Warm white',
						'bg-sand-50': 'Sand',
					}),
					repeaterField(
						'field_shvz_yacht_layout_features',
						'Features',
						'features',
						[textField('field_shvz_yacht_layout_feature_text', 'Text', 'text')],
						{ button_label: 'Add feature' }
					),
				],
				{ layout: 'block', button_label: 'Add layout section' }
			),
			repeaterField(
				'field_shvz_yacht_editorial',
				'Editorial sections',
				'editorial_sections',
				[
					textField('field_shvz_yacht_editorial_eyebrow', 'Eyebrow', 'eyebrow'),
					textField('field_shvz_yacht_editorial_heading', 'Heading', 'heading'),
					textareaField('field_shvz_yacht_editorial_description', 'Description', 'description', { rows: 3 }),
					imageField('field_shvz_yacht_editorial_image', 'Image', 'image'),
					textField('field_shvz_yacht_editorial_image_alt', 'Image alt', 'image_alt'),
					trueFalseField('field_shvz_yacht_editorial_reverse', 'Reverse layout', 'reverse'),
				],
				{ layout: 'block', button_label: 'Add editorial block' }
			),
			groupField('field_shvz_yacht_crew', 'Crew', 'crew', [
				textField('field_shvz_yacht_crew_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_yacht_crew_heading', 'Heading', 'heading'),
				textareaField('field_shvz_yacht_crew_description', 'Description', 'description', { rows: 3 }),
				repeaterField(
					'field_shvz_yacht_crew_members',
					'Crew members',
					'members',
					[
						textField('field_shvz_yacht_crew_role', 'Role', 'role'),
						textareaField('field_shvz_yacht_crew_member_description', 'Description', 'description', { rows: 3 }),
					],
					{ layout: 'block', button_label: 'Add crew member' }
				),
			]),
			groupField('field_shvz_yacht_gallery', 'Gallery', 'gallery', [
				textField('field_shvz_yacht_gallery_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_yacht_gallery_heading', 'Heading', 'heading'),
				textareaField('field_shvz_yacht_gallery_description', 'Description', 'description', { rows: 3 }),
				linkField('field_shvz_yacht_gallery_cta', 'CTA link', 'cta_link'),
				galleryField('field_shvz_yacht_gallery_images', 'Images', 'images'),
			]),
			bookingCtaGroup('field_shvz_yacht'),
		],
		template('page-the-yacht.php')
	);

	const contactPageGroup = fieldGroupBase(
		'group_shvz_contact_page',
		'Contact Page',
		[
			heroGroup('field_shvz_contact'),
			groupField('field_shvz_contact_details', 'Contact details', 'contact_details', [
				textField('field_shvz_contact_details_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_contact_details_heading', 'Heading', 'heading'),
				textField('field_shvz_contact_details_social_heading', 'Social heading', 'social_heading'),
			]),
			groupField('field_shvz_contact_form', 'Contact form', 'contact_form', [
				textField('field_shvz_contact_form_heading', 'Heading', 'heading'),
				textareaField('field_shvz_contact_form_description', 'Description', 'description', { rows: 3 }),
				textField('field_shvz_contact_form_booking_label', 'Booking link label', 'booking_link_label'),
			]),
			groupField('field_shvz_contact_map', 'Map', 'contact_map', [
				textField('field_shvz_contact_map_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_contact_map_heading', 'Heading', 'heading'),
				textField('field_shvz_contact_map_lat', 'Latitude', 'lat'),
				textField('field_shvz_contact_map_lng', 'Longitude', 'lng'),
				textField('field_shvz_contact_map_label', 'Map label', 'label'),
				textField('field_shvz_contact_map_place', 'Place', 'place'),
				urlField('field_shvz_contact_map_maps_url', 'Google Maps URL', 'maps_url'),
			]),
		],
		template('page-contact.php')
	);

	const galleryPageGroup = fieldGroupBase(
		'group_shvz_gallery_page',
		'Gallery Page',
		[
			heroGroup('field_shvz_galleryp'),
			galleryField('field_shvz_galleryp_images', 'Gallery images', 'gallery_images'),
			bookingCtaGroup('field_shvz_galleryp'),
		],
		template('page-gallery.php')
	);

	const bookingPageGroup = fieldGroupBase(
		'group_shvz_booking_page',
		'Booking Page',
		[
			heroGroup('field_shvz_booking'),
			groupField('field_shvz_booking_widget', 'Booking widget', 'booking_widget', [
				textField('field_shvz_booking_widget_heading', 'Heading', 'heading'),
				textareaField('field_shvz_booking_widget_description', 'Description', 'description', { rows: 3 }),
			]),
			groupField('field_shvz_booking_sidebar', 'Sidebar', 'booking_sidebar', [
				textField('field_shvz_booking_packages_heading', 'Packages heading', 'packages_heading'),
				textField('field_shvz_booking_steps_heading', 'Steps heading', 'steps_heading'),
				textField('field_shvz_booking_whatsapp_label', 'WhatsApp label', 'whatsapp_label'),
			]),
			repeaterField(
				'field_shvz_booking_steps',
				'Booking steps',
				'booking_steps',
				[
					textField('field_shvz_booking_step_title', 'Title', 'title'),
					textareaField('field_shvz_booking_step_description', 'Description', 'description', { rows: 2 }),
				],
				{ layout: 'block', button_label: 'Add step' }
			),
		],
		template('page-booking.php')
	);

	const experiencesPageGroup = fieldGroupBase(
		'group_shvz_experiences_page',
		'Experiences Page',
		[
			heroGroup('field_shvz_expp'),
			repeaterField(
				'field_shvz_expp_included',
				'Included in every package',
				'included_items',
				[textField('field_shvz_expp_included_text', 'Text', 'text')],
				{ button_label: 'Add item' }
			),
			experiencePackagesRepeater('field_shvz_expp'),
			bookingCtaGroup('field_shvz_expp'),
		],
		template('page-experiences.php')
	);

	const extrasPageGroup = fieldGroupBase(
		'group_shvz_extras_page',
		'Extras Page',
		[
			heroGroup('field_shvz_extrasp'),
			groupField('field_shvz_extrasp_content', 'Page content', 'extras_content', [
				textField('field_shvz_extrasp_eyebrow', 'Eyebrow', 'eyebrow'),
				textField('field_shvz_extrasp_heading', 'Heading', 'heading'),
				textareaField('field_shvz_extrasp_description', 'Description', 'description', { rows: 3 }),
				textField('field_shvz_extrasp_included_heading', 'Included heading', 'included_heading'),
				textField('field_shvz_extrasp_included_helper', 'Included helper', 'included_helper'),
				textField('field_shvz_extrasp_paid_heading', 'Paid heading', 'paid_heading'),
				textField('field_shvz_extrasp_paid_helper', 'Paid helper', 'paid_helper'),
				repeaterField(
					'field_shvz_extrasp_included',
					'Included items',
					'included',
					[
						iconField('field_shvz_extrasp_inc_icon', 'Icon', 'icon'),
						textField('field_shvz_extrasp_inc_title', 'Title', 'title'),
					],
					{ button_label: 'Add included item' }
				),
				repeaterField(
					'field_shvz_extrasp_paid',
					'Paid extras',
					'paid',
					[
						iconField('field_shvz_extrasp_paid_icon', 'Icon', 'icon'),
						textField('field_shvz_extrasp_paid_title', 'Title', 'title'),
						textField('field_shvz_extrasp_paid_price', 'Price', 'price'),
					],
					{ button_label: 'Add paid extra' }
				),
				repeaterField(
					'field_shvz_extrasp_amenities',
					'Amenities',
					'amenities',
					[textField('field_shvz_extrasp_amenity_text', 'Text', 'text')],
					{ button_label: 'Add amenity' }
				),
			]),
			bookingCtaGroup('field_shvz_extrasp'),
		],
		template('page-extras.php')
	);

	return {
		faqPageGroup,
		yachtPageGroup,
		contactPageGroup,
		galleryPageGroup,
		bookingPageGroup,
		experiencesPageGroup,
		extrasPageGroup,
	};
}
