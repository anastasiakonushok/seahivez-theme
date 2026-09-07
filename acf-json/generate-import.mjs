/**
 * Generate ACF Pro import-ready JSON files (Tools → Export As JSON format).
 *
 * Output:
 *   acf-import/acf-page-fields.json      — Homepage (Flexible Content)
 *   acf-import/acf-theme-settings.json   — Header / Footer / Social
 *   acf-import/acf-faq-page.json         — FAQ template
 *   acf-import/acf-yacht-page.json       — The Yacht template
 *   acf-import/acf-contact-page.json     — Contact template
 *   acf-import/acf-gallery-page.json     — Gallery template
 *   acf-import/acf-booking-page.json     — Booking template
 *   acf-import/acf-experiences-page.json — Experiences template
 *   acf-import/acf-extras-page.json      — Extras template
 *   acf-import/acf-news-page.json        — News / Posts page
 *   acf-import/acf-export-all.json       — all field groups
 *
 * Usage: node acf-json/generate-import.mjs
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { buildPageFieldGroups } from './page-groups-data.mjs';

const themeDir = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const importDir = path.join(themeDir, 'acf-import');
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
};

function fieldBase(extra = {}) {
	return {
		required: 0,
		conditional_logic: 0,
		wrapper: { width: '', class: '', id: '' },
		...extra,
	};
}

function textField(key, label, name, extra = {}) {
	return fieldBase({ key, label, name, type: 'text', default_value: '', maxlength: '', placeholder: '', prepend: '', append: '', ...extra });
}

function textareaField(key, label, name, extra = {}) {
	return fieldBase({ key, label, name, type: 'textarea', default_value: '', maxlength: '', rows: 4, placeholder: '', new_lines: '', ...extra });
}

function emailField(key, label, name, extra = {}) {
	return fieldBase({ key, label, name, type: 'email', default_value: '', placeholder: '', prepend: '', append: '', ...extra });
}

function urlField(key, label, name, extra = {}) {
	return fieldBase({ key, label, name, type: 'url', default_value: '', placeholder: '', ...extra });
}

function linkField(key, label, name, extra = {}) {
	return fieldBase({ key, label, name, type: 'link', return_format: 'array', ...extra });
}

function imageField(key, label, name, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'image',
		return_format: 'array',
		preview_size: 'medium',
		library: 'all',
		min_width: '',
		min_height: '',
		min_size: '',
		max_width: '',
		max_height: '',
		max_size: '',
		mime_types: '',
		...extra,
	});
}

function iconField(key, label, name, extra = {}) {
	return imageField(key, label, name, {
		mime_types: 'svg',
		preview_size: 'thumbnail',
		instructions: 'Choose an SVG from assets/images/icons/ or upload your own.',
		...extra,
	});
}

function tabField(key, label, extra = {}) {
	return fieldBase({ key, label, name: '', type: 'tab', placement: 'top', endpoint: 0, ...extra });
}

function groupField(key, label, name, subFields, extra = {}) {
	return fieldBase({ key, label, name, type: 'group', layout: 'block', sub_fields: subFields, ...extra });
}

function repeaterField(key, label, name, subFields, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'repeater',
		layout: 'table',
		min: 0,
		max: 0,
		collapsed: '',
		button_label: 'Add Row',
		sub_fields: subFields,
		...extra,
	});
}

function selectField(key, label, name, choices, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'select',
		choices,
		default_value: false,
		return_format: 'value',
		multiple: 0,
		allow_null: 0,
		ui: 0,
		ajax: 0,
		placeholder: '',
		...extra,
	});
}

function checkboxField(key, label, name, choices, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'checkbox',
		choices,
		return_format: 'value',
		layout: 'horizontal',
		toggle: 0,
		...extra,
	});
}

function specItemSubFields(prefix) {
	return [
		iconField(`${prefix}_icon`, 'Icon', 'icon'),
		textField(`${prefix}_label`, 'Label', 'label'),
		textField(`${prefix}_value`, 'Value', 'value', {
			instructions: 'Leave empty when using Languages below.',
		}),
		checkboxField(
			`${prefix}_languages`,
			'Languages',
			'languages',
			{ en: 'EN', es: 'ES', de: 'DE' },
			{
				instructions: 'Optional. Shows flag chips instead of Value (e.g. for Languages row).',
			}
		),
	];
}

function wysiwygField(key, label, name, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'wysiwyg',
		default_value: '',
		tabs: 'all',
		toolbar: 'full',
		media_upload: 1,
		delay: 0,
		...extra,
	});
}

function numberField(key, label, name, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'number',
		default_value: '',
		min: '',
		max: '',
		step: '',
		placeholder: '',
		prepend: '',
		append: '',
		...extra,
	});
}

function galleryField(key, label, name, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'gallery',
		return_format: 'array',
		preview_size: 'medium',
		insert: 'append',
		library: 'all',
		min: '',
		max: '',
		min_width: '',
		min_height: '',
		min_size: '',
		max_width: '',
		max_height: '',
		max_size: '',
		mime_types: '',
		...extra,
	});
}

function trueFalseField(key, label, name, extra = {}) {
	return fieldBase({
		key,
		label,
		name,
		type: 'true_false',
		default_value: 0,
		ui: 1,
		ui_on_text: '',
		ui_off_text: '',
		message: '',
		...extra,
	});
}

function layoutBase(key, name, label, subFields, extra = {}) {
	return {
		key,
		name,
		label,
		display: 'block',
		sub_fields: subFields,
		min: '',
		max: '',
		...extra,
	};
}

const pageSectionLayouts = {
	layout_shvz_hero: layoutBase('layout_shvz_hero', 'hero', 'Hero', [
		textField('field_shvz_hero_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_hero_heading', 'Heading', 'heading'),
		textareaField('field_shvz_hero_description', 'Description', 'description', { rows: 3 }),
		linkField('field_shvz_hero_primary', 'Primary button', 'primary_button'),
		linkField('field_shvz_hero_secondary', 'Secondary button', 'secondary_button'),
		imageField('field_shvz_hero_image', 'Background image', 'image'),
		textField('field_shvz_hero_image_alt', 'Image alt', 'image_alt'),
	]),
	layout_shvz_specs_bar: layoutBase('layout_shvz_specs_bar', 'specs_bar', 'Quick specs bar', [
		repeaterField(
			'field_shvz_specs_items',
			'Items',
			'items',
			specItemSubFields('field_shvz_specs'),
			{ button_label: 'Add spec' }
		),
	]),
	layout_shvz_about: layoutBase('layout_shvz_about', 'about', 'About', [
		textField('field_shvz_about_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_about_heading', 'Heading', 'heading'),
		repeaterField(
			'field_shvz_about_paragraphs',
			'Paragraphs',
			'paragraphs',
			[textareaField('field_shvz_about_paragraph_text', 'Text', 'text', { rows: 3 })],
			{ layout: 'block', button_label: 'Add paragraph' }
		),
		linkField('field_shvz_about_link', 'Link', 'link'),
		imageField('field_shvz_about_image', 'Image', 'image'),
		textField('field_shvz_about_image_alt', 'Image alt', 'image_alt'),
	]),
	layout_shvz_specifications: layoutBase('layout_shvz_specifications', 'specifications', 'Specifications', [
		textField('field_shvz_specs_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_specs_heading', 'Heading', 'heading'),
		textareaField('field_shvz_specs_intro', 'Intro', 'intro', { rows: 3 }),
		repeaterField(
			'field_shvz_specs_groups',
			'Groups',
			'groups',
			[
				textField('field_shvz_specs_group_title', 'Title', 'title'),
				repeaterField(
					'field_shvz_specs_group_items',
					'Items',
					'items',
					specItemSubFields('field_shvz_specs_group'),
					{ button_label: 'Add item' }
				),
			],
			{ layout: 'block', button_label: 'Add group' }
		),
	]),
	layout_shvz_experiences: layoutBase('layout_shvz_experiences', 'experiences', 'Experiences', [
		textField('field_shvz_exp_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_exp_heading', 'Heading', 'heading'),
		repeaterField(
			'field_shvz_exp_items',
			'Packages',
			'items',
			[
				textField('field_shvz_exp_title', 'Title', 'title'),
				textField('field_shvz_exp_duration', 'Duration', 'duration'),
				textField('field_shvz_exp_time_slot', 'Time slot', 'time_slot'),
				textField('field_shvz_exp_price', 'Price', 'price'),
				textareaField('field_shvz_exp_description', 'Description', 'description', { rows: 3 }),
				imageField('field_shvz_exp_image', 'Image', 'image'),
				linkField('field_shvz_exp_link', 'Link', 'link'),
			],
			{ layout: 'block', button_label: 'Add package' }
		),
	]),
	layout_shvz_toys_extras: layoutBase('layout_shvz_toys_extras', 'toys_extras', 'Toys & Extras', [
		textField('field_shvz_extras_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_extras_heading', 'Heading', 'heading'),
		textareaField('field_shvz_extras_description', 'Description', 'description', { rows: 3 }),
		repeaterField(
			'field_shvz_extras_included',
			'Included items',
			'included',
			[
				iconField('field_shvz_extras_inc_icon', 'Icon', 'icon'),
				textField('field_shvz_extras_inc_title', 'Title', 'title'),
			],
			{ button_label: 'Add included item' }
		),
		repeaterField(
			'field_shvz_extras_paid',
			'Paid extras',
			'paid',
			[
				iconField('field_shvz_extras_paid_icon', 'Icon', 'icon'),
				textField('field_shvz_extras_paid_title', 'Title', 'title'),
				textField('field_shvz_extras_paid_price', 'Price', 'price'),
			],
			{ button_label: 'Add paid extra' }
		),
		repeaterField(
			'field_shvz_extras_amenities',
			'Amenities',
			'amenities',
			[textField('field_shvz_extras_amenity_text', 'Text', 'text')],
			{ button_label: 'Add amenity' }
		),
	]),
	layout_shvz_gallery: layoutBase('layout_shvz_gallery', 'gallery', 'Gallery', [
		textField('field_shvz_gallery_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_gallery_heading', 'Heading', 'heading'),
		textareaField('field_shvz_gallery_description', 'Description', 'description', { rows: 3 }),
		linkField('field_shvz_gallery_cta', 'CTA link', 'cta_link'),
		galleryField('field_shvz_gallery_images', 'Images', 'images'),
	]),
	layout_shvz_faq: layoutBase('layout_shvz_faq', 'faq', 'FAQ', [
		textField('field_shvz_faq_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_faq_heading', 'Heading', 'heading'),
		textareaField('field_shvz_faq_description', 'Description', 'description', { rows: 3 }),
		textField('field_shvz_faq_cta_heading', 'CTA heading', 'cta_heading'),
		linkField('field_shvz_faq_cta_link', 'CTA link', 'cta_link'),
		repeaterField(
			'field_shvz_faq_items',
			'Questions',
			'items',
			[
				textField('field_shvz_faq_question', 'Question', 'question'),
				wysiwygField('field_shvz_faq_answer', 'Answer', 'answer', { tabs: 'visual', toolbar: 'basic', media_upload: 0 }),
			],
			{ layout: 'block', button_label: 'Add question' }
		),
	]),
	layout_shvz_news: layoutBase('layout_shvz_news', 'news', 'News', [
		textField('field_shvz_news_eyebrow', 'Eyebrow', 'eyebrow'),
		textField('field_shvz_news_heading', 'Heading', 'heading'),
		textareaField('field_shvz_news_description', 'Description', 'description', { rows: 3 }),
		linkField('field_shvz_news_cta', 'CTA link', 'cta_link'),
		numberField('field_shvz_news_count', 'Posts count', 'posts_count', { default_value: 3, min: 1, max: 12 }),
	]),
	layout_shvz_location_cta: layoutBase(
		'layout_shvz_location_cta',
		'location_cta',
		'Location & Booking CTA',
		[
			textField('field_shvz_loc_heading', 'Heading', 'heading'),
			textareaField('field_shvz_loc_description', 'Description', 'description', { rows: 3 }),
			linkField('field_shvz_loc_cta', 'CTA button', 'cta_button'),
		],
		{
			instructions:
				'Phone, email and location are taken from Theme Settings → Social & Contact.',
		}
	),
	layout_shvz_content: layoutBase('layout_shvz_content', 'content', 'Content', [
		wysiwygField('field_shvz_content_body', 'Content', 'content'),
	]),
};

function fieldGroupBase(key, title, fields, location) {
	return {
		key,
		title,
		fields,
		location,
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
}

const pageContentGroup = fieldGroupBase(
	'group_shvz_page_content',
	'Homepage Content',
	[
		fieldBase({
			key: 'field_shvz_sections',
			label: 'Sections',
			name: 'sections',
			type: 'flexible_content',
			instructions: '',
			required: 0,
			conditional_logic: 0,
			wrapper: { width: '', class: '', id: '' },
			button_label: 'Add section',
			min: '',
			max: '',
			layouts: pageSectionLayouts,
		}),
	],
	[
		[{ param: 'page_type', operator: '==', value: 'front_page' }],
		[{ param: 'page_template', operator: '==', value: 'page-flexible.php' }],
	]
);

const themeSettingsGroup = fieldGroupBase(
	'group_shvz_theme_settings',
	'Theme Settings',
	[
		tabField('field_shvz_tab_header', 'Header'),
		groupField('field_shvz_header', 'Header', 'header', [
			imageField('field_shvz_header_logo_light', 'Logo (light)', 'logo_light'),
			imageField('field_shvz_header_logo_dark', 'Logo (dark)', 'logo_dark'),
			textField('field_shvz_header_logo_alt', 'Logo alt text', 'logo_alt'),
			linkField('field_shvz_header_cta', 'Header CTA button', 'cta_button'),
		]),
		tabField('field_shvz_tab_footer', 'Footer'),
		groupField('field_shvz_footer', 'Footer', 'footer', [
			textareaField('field_shvz_footer_description', 'Description', 'description', { rows: 3 }),
			textField('field_shvz_footer_book_heading', 'Book block heading', 'book_heading'),
			textareaField('field_shvz_footer_book_description', 'Book block description', 'book_description', { rows: 3 }),
			linkField('field_shvz_footer_book_cta', 'Book block button', 'book_cta'),
			textField('field_shvz_footer_copyright', 'Copyright override', 'copyright', {
				instructions: 'Leave empty to use default © year + site name.',
			}),
		], {
			instructions: 'Phone, email and address are taken from Social & Contact.',
		}),
		tabField('field_shvz_tab_social', 'Social & Contact'),
		groupField(
			'field_shvz_social_contact',
			'Social & Contact',
			'social_contact',
			[
				urlField('field_shvz_social_instagram_url', 'Instagram URL', 'instagram_url'),
				textField('field_shvz_social_instagram_handle', 'Instagram handle', 'instagram_handle'),
				textField('field_shvz_social_whatsapp_number', 'WhatsApp number', 'whatsapp_number'),
				urlField('field_shvz_social_whatsapp_url', 'WhatsApp URL (optional override)', 'whatsapp_url'),
				urlField('field_shvz_social_telegram_url', 'Telegram URL', 'telegram_url'),
				textField('field_shvz_social_phone', 'Phone', 'phone'),
				emailField('field_shvz_social_email', 'Email', 'email'),
				textField('field_shvz_social_address', 'Address / location', 'address'),
			],
			{
				instructions:
					'Shared contact details used in the header, footer, contact page, and booking CTAs across the site.',
			}
		),
	],
	[[{ param: 'options_page', operator: '==', value: 'theme-settings' }]]
);

const pageGroups = buildPageFieldGroups({
	fieldGroupBase,
	textField,
	textareaField,
	emailField,
	urlField,
	linkField,
	imageField,
	iconField,
	checkboxField,
	groupField,
	repeaterField,
	selectField,
	wysiwygField,
	galleryField,
	trueFalseField,
	numberField,
	specIconChoices,
	toyIconChoices,
});

const allGroups = [
	pageContentGroup,
	themeSettingsGroup,
	pageGroups.faqPageGroup,
	pageGroups.yachtPageGroup,
	pageGroups.contactPageGroup,
	pageGroups.galleryPageGroup,
	pageGroups.bookingPageGroup,
	pageGroups.experiencesPageGroup,
	pageGroups.extrasPageGroup,
	pageGroups.newsPageGroup,
];

if (!fs.existsSync(importDir)) {
	fs.mkdirSync(importDir, { recursive: true });
}

const importFiles = {
	'acf-home-page.json': [pageContentGroup],
	'acf-page-fields.json': [pageContentGroup],
	'acf-theme-settings.json': [themeSettingsGroup],
	'acf-faq-page.json': [pageGroups.faqPageGroup],
	'acf-yacht-page.json': [pageGroups.yachtPageGroup],
	'acf-contact-page.json': [pageGroups.contactPageGroup],
	'acf-gallery-page.json': [pageGroups.galleryPageGroup],
	'acf-booking-page.json': [pageGroups.bookingPageGroup],
	'acf-experiences-page.json': [pageGroups.experiencesPageGroup],
	'acf-extras-page.json': [pageGroups.extrasPageGroup],
	'acf-news-page.json': [pageGroups.newsPageGroup],
	'acf-export-all.json': allGroups,
};

for (const [filename, groups] of Object.entries(importFiles)) {
	const filePath = path.join(importDir, filename);
	fs.writeFileSync(filePath, `${JSON.stringify(groups, null, 4)}\r\n`);
	console.log(`Wrote ${filePath}`);
}

const jsonDir = path.join(themeDir, 'acf-json');
if (!fs.existsSync(jsonDir)) {
	fs.mkdirSync(jsonDir, { recursive: true });
}

for (const group of allGroups) {
	const filePath = path.join(jsonDir, `${group.key}.json`);
	fs.writeFileSync(filePath, `${JSON.stringify(group, null, 4)}\n`);
	console.log(`Wrote ${filePath}`);
}
