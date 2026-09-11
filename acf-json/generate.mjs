import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const modified = Math.floor(Date.now() / 1000);

const specIconChoices = {
	location: 'Location',
	guests: 'Guests',
	cabins: 'Cabins',
	crew: 'Crew',
	calendar: 'Calendar',
	speed: 'Speed',
	length: 'Length',
	beam: 'Beam',
	draft: 'Draft',
	engines: 'Engines',
	bathrooms: 'Bathrooms',
	languages: 'Languages',
};

const toyIconChoices = {
	snorkel: 'Snorkel Set',
	'paddle-board': 'Paddle Board',
	seabob: 'SeaBob',
	'jet-ski': 'Jet Ski',
	'efoil-air': 'Efoil Air',
	towel: 'Towel Service',
	water: 'Drinking Water',
	flippers: 'Flippers',
	swimming: 'Swimming',
	food: 'Food',
	drinks: 'Drinks',
	'children-menu': "Children's Menu",
};

const baseGroup = {
	menu_order: 0,
	position: 'normal',
	style: 'default',
	label_placement: 'top',
	instruction_placement: 'label',
	hide_on_screen: '',
	active: true,
	description: '',
	show_in_rest: 0,
	modified,
};

const themeSettings = {
	...baseGroup,
	key: 'group_shvz_theme_settings',
	title: 'Theme Settings',
	fields: [
		{ key: 'field_shvz_tab_header', label: 'Header', name: '', type: 'tab' },
		{
			key: 'field_shvz_header',
			label: 'Header',
			name: 'header',
			type: 'group',
			layout: 'block',
			sub_fields: [
				{ key: 'field_shvz_header_logo_light', label: 'Logo (light)', name: 'logo_light', type: 'image', return_format: 'array', preview_size: 'medium', library: 'all' },
				{ key: 'field_shvz_header_logo_dark', label: 'Logo (dark)', name: 'logo_dark', type: 'image', return_format: 'array', preview_size: 'medium', library: 'all' },
				{ key: 'field_shvz_header_logo_alt', label: 'Logo alt text', name: 'logo_alt', type: 'text' },
				{ key: 'field_shvz_header_cta', label: 'Header CTA button', name: 'cta_button', type: 'link' },
			],
		},
		{ key: 'field_shvz_tab_footer', label: 'Footer', name: '', type: 'tab' },
		{
			key: 'field_shvz_footer',
			label: 'Footer',
			name: 'footer',
			type: 'group',
			layout: 'block',
			sub_fields: [
				{ key: 'field_shvz_footer_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
				{ key: 'field_shvz_footer_address', label: 'Address', name: 'address', type: 'text' },
				{ key: 'field_shvz_footer_phone', label: 'Phone', name: 'phone', type: 'text' },
				{ key: 'field_shvz_footer_email', label: 'Email', name: 'email', type: 'email' },
				{ key: 'field_shvz_footer_book_heading', label: 'Book block heading', name: 'book_heading', type: 'text' },
				{ key: 'field_shvz_footer_book_description', label: 'Book block description', name: 'book_description', type: 'textarea', rows: 3 },
				{ key: 'field_shvz_footer_book_cta', label: 'Book block button', name: 'book_cta', type: 'link' },
				{ key: 'field_shvz_footer_copyright', label: 'Copyright override', name: 'copyright', type: 'text', instructions: 'Leave empty to use default © year + site name.' },
			],
		},
		{ key: 'field_shvz_tab_social', label: 'Social & Contact', name: '', type: 'tab' },
		{
			key: 'field_shvz_social_contact',
			label: 'Social & Contact',
			name: 'social_contact',
			type: 'group',
			layout: 'block',
			sub_fields: [
				{ key: 'field_shvz_social_instagram_url', label: 'Instagram URL', name: 'instagram_url', type: 'url' },
				{ key: 'field_shvz_social_instagram_handle', label: 'Instagram handle', name: 'instagram_handle', type: 'text' },
				{ key: 'field_shvz_social_whatsapp_number', label: 'WhatsApp number', name: 'whatsapp_number', type: 'text' },
				{ key: 'field_shvz_social_whatsapp_url', label: 'WhatsApp URL (optional override)', name: 'whatsapp_url', type: 'url' },
				{ key: 'field_shvz_social_telegram_url', label: 'Telegram URL', name: 'telegram_url', type: 'url' },
				{ key: 'field_shvz_social_phone', label: 'Phone', name: 'phone', type: 'text' },
				{ key: 'field_shvz_social_email', label: 'Email', name: 'email', type: 'email' },
				{ key: 'field_shvz_social_address', label: 'Address', name: 'address', type: 'text' },
			],
		},
	],
	location: [[{ param: 'options_page', operator: '==', value: 'theme-settings' }]],
};

const layouts = {
	layout_shvz_hero: {
		key: 'layout_shvz_hero',
		name: 'hero',
		label: 'Hero',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_hero_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_hero_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_hero_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{ key: 'field_shvz_hero_primary', label: 'Primary button', name: 'primary_button', type: 'link' },
			{ key: 'field_shvz_hero_secondary', label: 'Secondary button', name: 'secondary_button', type: 'link' },
			{ key: 'field_shvz_hero_image', label: 'Background image', name: 'image', type: 'image', return_format: 'array' },
			{ key: 'field_shvz_hero_image_alt', label: 'Image alt', name: 'image_alt', type: 'text' },
		],
	},
	layout_shvz_specs_bar: {
		key: 'layout_shvz_specs_bar',
		name: 'specs_bar',
		label: 'Quick specs bar',
		display: 'block',
		sub_fields: [
			{
				key: 'field_shvz_specs_items',
				label: 'Items',
				name: 'items',
				type: 'repeater',
				layout: 'table',
				button_label: 'Add spec',
				sub_fields: [
					{ key: 'field_shvz_specs_icon', label: 'Icon', name: 'icon', type: 'select', choices: specIconChoices },
					{ key: 'field_shvz_specs_label', label: 'Label', name: 'label', type: 'text' },
					{ key: 'field_shvz_specs_value', label: 'Value', name: 'value', type: 'text' },
				],
			},
		],
	},
	layout_shvz_about: {
		key: 'layout_shvz_about',
		name: 'about',
		label: 'About',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_about_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_about_heading', label: 'Heading', name: 'heading', type: 'text' },
			{
				key: 'field_shvz_about_paragraphs',
				label: 'Paragraphs',
				name: 'paragraphs',
				type: 'repeater',
				layout: 'block',
				button_label: 'Add paragraph',
				sub_fields: [{ key: 'field_shvz_about_paragraph_text', label: 'Text', name: 'text', type: 'textarea', rows: 3 }],
			},
			{ key: 'field_shvz_about_link', label: 'Link', name: 'link', type: 'link' },
			{ key: 'field_shvz_about_image', label: 'Image', name: 'image', type: 'image', return_format: 'array' },
			{ key: 'field_shvz_about_image_alt', label: 'Image alt', name: 'image_alt', type: 'text' },
		],
	},
	layout_shvz_specifications: {
		key: 'layout_shvz_specifications',
		name: 'specifications',
		label: 'Specifications',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_specs_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_specs_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_specs_intro', label: 'Intro', name: 'intro', type: 'textarea', rows: 3 },
			{
				key: 'field_shvz_specs_groups',
				label: 'Groups',
				name: 'groups',
				type: 'repeater',
				layout: 'block',
				button_label: 'Add group',
				sub_fields: [
					{ key: 'field_shvz_specs_group_title', label: 'Title', name: 'title', type: 'text' },
					{
						key: 'field_shvz_specs_group_items',
						label: 'Items',
						name: 'items',
						type: 'repeater',
						layout: 'table',
						button_label: 'Add item',
						sub_fields: [
							{ key: 'field_shvz_specs_group_icon', label: 'Icon', name: 'icon', type: 'select', choices: specIconChoices },
							{ key: 'field_shvz_specs_group_label', label: 'Label', name: 'label', type: 'text' },
							{ key: 'field_shvz_specs_group_value', label: 'Value', name: 'value', type: 'text' },
						],
					},
				],
			},
		],
	},
	layout_shvz_experiences: {
		key: 'layout_shvz_experiences',
		name: 'experiences',
		label: 'Experiences',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_exp_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_exp_heading', label: 'Heading', name: 'heading', type: 'text' },
			{
				key: 'field_shvz_exp_items',
				label: 'Packages',
				name: 'items',
				type: 'repeater',
				layout: 'block',
				button_label: 'Add package',
				sub_fields: [
					{ key: 'field_shvz_exp_title', label: 'Title', name: 'title', type: 'text' },
					{ key: 'field_shvz_exp_duration', label: 'Duration', name: 'duration', type: 'text' },
					{ key: 'field_shvz_exp_time_slot', label: 'Time slot', name: 'time_slot', type: 'text' },
					{ key: 'field_shvz_exp_price', label: 'Price', name: 'price', type: 'text' },
					{ key: 'field_shvz_exp_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
					{ key: 'field_shvz_exp_image', label: 'Image', name: 'image', type: 'image', return_format: 'array' },
					{ key: 'field_shvz_exp_link', label: 'Link', name: 'link', type: 'link' },
				],
			},
		],
	},
	layout_shvz_toys_extras: {
		key: 'layout_shvz_toys_extras',
		name: 'toys_extras',
		label: 'Toys & Extras',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_extras_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_extras_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_extras_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{
				key: 'field_shvz_extras_included',
				label: 'Included items',
				name: 'included',
				type: 'repeater',
				button_label: 'Add included item',
				sub_fields: [
					{ key: 'field_shvz_extras_inc_icon', label: 'Icon', name: 'icon', type: 'select', choices: toyIconChoices },
					{ key: 'field_shvz_extras_inc_title', label: 'Title', name: 'title', type: 'text' },
				],
			},
			{
				key: 'field_shvz_extras_paid',
				label: 'Paid extras',
				name: 'paid',
				type: 'repeater',
				button_label: 'Add paid extra',
				sub_fields: [
					{ key: 'field_shvz_extras_paid_icon', label: 'Icon', name: 'icon', type: 'select', choices: toyIconChoices },
					{ key: 'field_shvz_extras_paid_title', label: 'Title', name: 'title', type: 'text' },
					{ key: 'field_shvz_extras_paid_price', label: 'Price', name: 'price', type: 'text' },
				],
			},
			{
				key: 'field_shvz_extras_amenities',
				label: 'Amenities',
				name: 'amenities',
				type: 'repeater',
				button_label: 'Add amenity',
				sub_fields: [{ key: 'field_shvz_extras_amenity_text', label: 'Text', name: 'text', type: 'text' }],
			},
			{ key: 'field_shvz_extras_food_eyebrow', label: 'Food & drinks eyebrow', name: 'food_drinks_eyebrow', type: 'text' },
			{ key: 'field_shvz_extras_food_status', label: 'Food & drinks status', name: 'food_drinks_status', type: 'text' },
			{ key: 'field_shvz_extras_food_note', label: 'Food & drinks note', name: 'food_drinks_note', type: 'text' },
			{
				key: 'field_shvz_extras_food_items',
				label: 'Food & drinks items',
				name: 'food_drinks_items',
				type: 'repeater',
				button_label: 'Add food / drink item',
				sub_fields: [
					{
						key: 'field_shvz_extras_food_item_id',
						label: 'Item ID',
						name: 'item_id',
						type: 'select',
						choices: { food: 'Food', drinks: 'Drinks', children: "Children's Menu" },
						allow_null: 1,
					},
					{ key: 'field_shvz_extras_food_icon', label: 'Icon', name: 'icon', type: 'select', choices: toyIconChoices },
					{ key: 'field_shvz_extras_food_title', label: 'Title', name: 'title', type: 'text' },
					{ key: 'field_shvz_extras_food_price', label: 'Price', name: 'price', type: 'number', min: 0 },
					{ key: 'field_shvz_extras_food_unit', label: 'Unit', name: 'unit', type: 'text' },
					{ key: 'field_shvz_extras_food_description', label: 'Description', name: 'description', type: 'textarea', rows: 2 },
				],
			},
		],
	},
	layout_shvz_gallery: {
		key: 'layout_shvz_gallery',
		name: 'gallery',
		label: 'Gallery',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_gallery_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_gallery_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_gallery_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{ key: 'field_shvz_gallery_cta', label: 'CTA link', name: 'cta_link', type: 'link' },
			{ key: 'field_shvz_gallery_images', label: 'Images', name: 'images', type: 'gallery', return_format: 'array', preview_size: 'medium' },
		],
	},
	layout_shvz_faq: {
		key: 'layout_shvz_faq',
		name: 'faq',
		label: 'FAQ',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_faq_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_faq_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_faq_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{ key: 'field_shvz_faq_cta_heading', label: 'CTA heading', name: 'cta_heading', type: 'text' },
			{ key: 'field_shvz_faq_cta_link', label: 'CTA link', name: 'cta_link', type: 'link' },
			{
				key: 'field_shvz_faq_items',
				label: 'Questions',
				name: 'items',
				type: 'repeater',
				layout: 'block',
				button_label: 'Add question',
				sub_fields: [
					{ key: 'field_shvz_faq_question', label: 'Question', name: 'question', type: 'text' },
					{ key: 'field_shvz_faq_answer', label: 'Answer', name: 'answer', type: 'wysiwyg', tabs: 'visual', toolbar: 'basic', media_upload: 0 },
				],
			},
		],
	},
	layout_shvz_news: {
		key: 'layout_shvz_news',
		name: 'news',
		label: 'News',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_news_eyebrow', label: 'Eyebrow', name: 'eyebrow', type: 'text' },
			{ key: 'field_shvz_news_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_news_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{ key: 'field_shvz_news_cta', label: 'CTA link', name: 'cta_link', type: 'link' },
			{ key: 'field_shvz_news_count', label: 'Posts count', name: 'posts_count', type: 'number', default_value: 3, min: 1, max: 12 },
		],
	},
	layout_shvz_location_cta: {
		key: 'layout_shvz_location_cta',
		name: 'location_cta',
		label: 'Location & Booking CTA',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_loc_heading', label: 'Heading', name: 'heading', type: 'text' },
			{ key: 'field_shvz_loc_description', label: 'Description', name: 'description', type: 'textarea', rows: 3 },
			{ key: 'field_shvz_loc_cta', label: 'CTA button', name: 'cta_button', type: 'link' },
			{ key: 'field_shvz_loc_location', label: 'Location', name: 'location', type: 'text' },
			{ key: 'field_shvz_loc_phone', label: 'Phone', name: 'phone', type: 'text' },
			{ key: 'field_shvz_loc_email', label: 'Email', name: 'email', type: 'email' },
		],
	},
	layout_shvz_content: {
		key: 'layout_shvz_content',
		name: 'content',
		label: 'Content',
		display: 'block',
		sub_fields: [
			{ key: 'field_shvz_content_body', label: 'Content', name: 'content', type: 'wysiwyg', tabs: 'all', toolbar: 'full', media_upload: 1 },
		],
	},
};

const pageContent = {
	...baseGroup,
	key: 'group_shvz_page_content',
	title: 'Page Content',
	fields: [
		{
			key: 'field_shvz_sections',
			label: 'Sections',
			name: 'sections',
			type: 'flexible_content',
			button_label: 'Add section',
			layouts,
		},
	],
	location: [
		[{ param: 'page_type', operator: '==', value: 'front_page' }],
		[{ param: 'page_template', operator: '==', value: 'page-flexible.php' }],
	],
};

for (const [key, group] of Object.entries({ group_shvz_theme_settings: themeSettings, group_shvz_page_content: pageContent })) {
	const file = path.join(__dirname, `${key}.json`);
	fs.writeFileSync(file, `${JSON.stringify(group, null, 4)}\n`);
	console.log(`Wrote ${file}`);
}
