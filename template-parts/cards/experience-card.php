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
 *     @type string $cta_label         CTA label.
 *     @type bool   $is_homepage       Homepage card with separated price footer.
 *     @type string $toys_extras_url   Anchor URL for Toys & Extras link.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'duration'        => '',
		'time_slot'       => '',
		'title'           => '',
		'price'           => '',
		'description'     => '',
		'image'           => '',
		'url'             => '',
		'cta_label'       => __( 'View package', 'seahivez-theme' ),
		'is_homepage'     => false,
		'toys_extras_url' => '#toys-extras',
	)
);

if ( empty( $args['title'] ) ) {
	return;
}

$price_label   = seahivez_format_extra_price_label( $args['price'], false );
$is_homepage   = ! empty( $args['is_homepage'] );
$article_class = 'experience-card group flex flex-col overflow-hidden border border-slate-200 bg-white transition-colors duration-300 hover:border-slate-300';

if ( $is_homepage ) {
	$article_class .= ' experience-card--home h-full';
} else {
	$article_class .= ' h-full';
}

$package_key = ! empty( $args['package_key'] ) ? (string) $args['package_key'] : '';
?>

<article
	class="<?php echo esc_attr( $article_class ); ?>"
	<?php if ( $is_homepage && $package_key ) : ?>
		data-experience-package="<?php echo esc_attr( $package_key ); ?>"
	<?php endif; ?>
>
	<?php if ( ! empty( $args['image'] ) ) : ?>
		<div class="experience-card__media overflow-hidden<?php echo $is_homepage ? ' aspect-[3/2]' : ' aspect-[4/3]'; ?>">
			<img
				class="experience-card__image h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
				src="<?php echo esc_url( $args['image'] ); ?>"
				alt="<?php echo esc_attr( $args['title'] ); ?>"
				loading="lazy"
				decoding="async"
			>
		</div>
	<?php endif; ?>

	<div class="experience-card__body flex flex-col p-6 lg:p-7<?php echo $is_homepage ? ' flex-1 min-h-0' : ''; ?>">
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
			<p class="experience-card__description mt-4 text-base leading-snug text-slate-600">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $is_homepage && ! empty( $args['charter_card'] ) && is_array( $args['charter_card'] ) ) : ?>
			<?php
			get_template_part(
				'template-parts/cards/experience-card-home-summary',
				null,
				$args['charter_card']
			);
			?>
		<?php elseif ( ! empty( $args['charter_details'] ) && is_array( $args['charter_details'] ) ) : ?>
			<?php
			get_template_part(
				'template-parts/cards/experience-card-charter-details',
				null,
				$args['charter_details']
			);
			?>
		<?php endif; ?>

		<div class="experience-card__footer w-full shrink-0<?php echo $is_homepage ? ' mt-auto border-t border-slate-200 pt-4' : ' mt-auto pt-4'; ?>">
			<?php if ( $is_homepage && $package_key ) : ?>
				<div class="experience-card__price-stack" data-charter-price-stack>
					<p class="experience-card__price-label text-xs font-medium uppercase tracking-[0.1em] text-slate-500">
						<?php esc_html_e( 'Base charter', 'seahivez-theme' ); ?>
					</p>

					<?php if ( $price_label ) : ?>
						<p class="experience-card__price-base mt-1 hidden text-lg font-medium leading-none tracking-tight text-slate-600" data-charter-base-price hidden>
							<?php echo esc_html( $price_label ); ?>
						</p>
					<?php endif; ?>

					<div class="experience-card__fuel-adjustment mt-2 flex hidden items-baseline justify-between gap-3 text-sm text-navy-900" data-charter-fuel-row hidden>
						<span><?php esc_html_e( 'Fuel', 'seahivez-theme' ); ?></span>
						<span class="shrink-0 font-medium tabular-nums" data-charter-fuel-value></span>
					</div>

					<p class="experience-card__price experience-card__price--total mt-2 text-2xl font-medium leading-none tracking-tight text-navy-900 md:text-[26px]" data-charter-total-price>
						<?php echo esc_html( $price_label ); ?>
					</p>
				</div>
			<?php elseif ( $is_homepage ) : ?>
				<p class="experience-card__price-label text-xs font-medium uppercase tracking-[0.1em] text-slate-500">
					<?php esc_html_e( 'Base charter', 'seahivez-theme' ); ?>
				</p>

				<?php if ( $price_label ) : ?>
					<p class="experience-card__price mt-1 text-2xl font-medium leading-none tracking-tight text-navy-900 md:text-[26px]">
						<?php echo esc_html( $price_label ); ?>
					</p>
				<?php endif; ?>
			<?php elseif ( $price_label ) : ?>
				<p class="experience-card__price text-2xl font-semibold leading-none tracking-tight text-navy-900">
					<?php echo esc_html( $price_label ); ?>
				</p>
			<?php endif; ?>

			<?php if ( ! empty( $args['url'] ) ) : ?>
				<?php if ( $is_homepage ) : ?>
					<a class="experience-card__cta-btn btn-primary mt-4 inline-flex w-full justify-center" href="<?php echo esc_url( $args['url'] ); ?>">
						<span><?php echo esc_html( $args['cta_label'] ); ?></span>
						<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
					</a>
				<?php else : ?>
					<a class="link-arrow experience-card__cta mt-4 inline-flex text-sm font-semibold uppercase tracking-wide" href="<?php echo esc_url( $args['url'] ); ?>">
						<?php echo esc_html( $args['cta_label'] ); ?>
						<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $is_homepage && $package_key ) : ?>
				<a
					class="experience-card__toys-link mt-3 inline-flex py-1 text-xs font-medium uppercase tracking-[0.08em] text-slate-500 transition-colors duration-300 hover:text-gold-dark"
					href="<?php echo esc_url( $args['toys_extras_url'] ); ?>"
				>
					<?php esc_html_e( 'See toys & extras', 'seahivez-theme' ); ?>
					<span aria-hidden="true"> ↓</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
