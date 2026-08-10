<?php
/**
 * Experience package card.
 *
 * Hierarchy: title → duration meta → time slot → description → price → CTA.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $duration    Duration / type meta.
 *     @type string $time_slot   Schedule times only.
 *     @type string $title       Card title.
 *     @type string $price       Price amount.
 *     @type string $description Short description.
 *     @type string $image       Image URL.
 *     @type string $url         Link URL.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'duration'    => '',
		'time_slot'   => '',
		'title'       => '',
		'price'       => '',
		'description' => '',
		'image'       => '',
		'url'         => '',
	)
);

if ( empty( $args['title'] ) ) {
	return;
}

$price_label = seahivez_format_extra_price_label( $args['price'], false );
?>

<article class="experience-card group flex h-full flex-col overflow-hidden border border-slate-200 bg-white transition-colors duration-300 hover:border-slate-300">
	<?php if ( ! empty( $args['image'] ) ) : ?>
		<div class="experience-card__media aspect-[4/3] overflow-hidden">
			<img
				class="experience-card__image h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
				src="<?php echo esc_url( $args['image'] ); ?>"
				alt="<?php echo esc_attr( $args['title'] ); ?>"
				loading="lazy"
				decoding="async"
			>
		</div>
	<?php endif; ?>

	<div class="experience-card__body flex flex-1 flex-col p-6 lg:p-7">
		<h3 class="experience-card__title mb-4 text-xl font-semibold leading-tight text-navy-900 lg:text-[22px]">
			<?php echo esc_html( $args['title'] ); ?>
		</h3>

		<?php if ( ! empty( $args['duration'] ) || ! empty( $args['time_slot'] ) ) : ?>
			<div class="experience-card__meta space-y-1.5">
				<?php if ( ! empty( $args['duration'] ) ) : ?>
					<p class="experience-card__duration text-sm font-medium uppercase tracking-wider text-slate-500">
						<?php echo esc_html( $args['duration'] ); ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $args['time_slot'] ) ) : ?>
					<p class="experience-card__time-slot flex items-center gap-2 text-sm text-slate-500">
						<svg class="experience-card__time-slot-icon h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<circle cx="12" cy="12" r="8"/>
							<path stroke-linecap="round" d="M12 8v4l2.5 2.5"/>
						</svg>
						<span><?php echo esc_html( $args['time_slot'] ); ?></span>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="experience-card__description mt-5 text-base leading-relaxed text-slate-600">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>

		<div class="experience-card__footer mt-auto pt-6">
			<?php if ( $price_label ) : ?>
				<p class="experience-card__price text-2xl font-semibold leading-none tracking-tight text-navy-900">
					<?php echo esc_html( $price_label ); ?>
				</p>
			<?php endif; ?>

			<?php if ( ! empty( $args['url'] ) ) : ?>
				<a class="link-arrow experience-card__cta mt-4 inline-flex text-sm font-semibold uppercase tracking-wide" href="<?php echo esc_url( $args['url'] ); ?>">
					<?php esc_html_e( 'Book this experience', 'seahivez-theme' ); ?>
					<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
