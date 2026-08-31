<?php
/**
 * Dedicated FAQ page — reuses homepage accordion JS via data attributes.
 *
 * @package seahivez-theme
 */

$header      = seahivez_get_home_faq_header();
$groups      = seahivez_get_faq_page_groups();
$contact_url = ! empty( $header['contact_url'] ) ? $header['contact_url'] : home_url( '/contact/' );
$global_index = 0;
?>

<section class="faq-page section-spacing bg-warm-white" aria-labelledby="faq-page-heading">
	<div class="site-container">
		<div class="faq__layout grid gap-10 lg:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] lg:gap-16 xl:gap-20">
			<div class="faq__intro reveal">
				<h2 id="faq-page-heading" class="section-heading">
					<?php echo esc_html( $header['heading'] ); ?>
				</h2>
				<p class="type-body mt-4 max-w-md">
					<?php echo esc_html( $header['description'] ); ?>
				</p>

				<div class="faq__cta mt-8 hidden lg:block">
					<p class="faq__cta-label"><?php echo esc_html( $header['cta_heading'] ); ?></p>
					<div class="faq__cta-actions mt-4 flex flex-wrap items-center gap-3">
						<a class="faq__contact link-arrow" href="<?php echo esc_url( $contact_url ); ?>">
							<?php echo esc_html( $header['cta_label'] ); ?>
							<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
						</a>

						<?php
						get_template_part(
							'template-parts/components/social-links',
							null,
							array(
								'class' => 'faq__social',
							)
						);
						?>
					</div>
				</div>
			</div>

			<div class="faq__list reveal reveal-delay-1 space-y-12">
				<?php foreach ( $groups as $group ) : ?>
					<?php if ( empty( $group['items'] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>

					<div>
						<h3 class="spec-group__title"><?php echo esc_html( $group['title'] ); ?></h3>
						<div class="faq-accordion mt-6" data-faq-accordion>
							<?php foreach ( $group['items'] as $item ) : ?>
								<?php
								get_template_part(
									'template-parts/cards/faq-item',
									null,
									array(
										'index'    => 100 + $global_index,
										'question' => $item['question'],
										'answer'   => $item['answer'],
										'open'     => 0 === $global_index,
									)
								);
								++$global_index;
								?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>

				<?php
				// Fallback: if grouping filtered everything oddly, show full list.
				if ( 0 === $global_index ) :
					$items = seahivez_get_home_faq_items();
					?>
					<div class="faq-accordion" data-faq-accordion>
						<?php foreach ( $items as $index => $item ) : ?>
							<?php
							get_template_part(
								'template-parts/cards/faq-item',
								null,
								array(
									'index'    => $index,
									'question' => $item['question'],
									'answer'   => $item['answer'],
									'open'     => 0 === $index,
								)
							);
							?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="faq__cta faq__cta--mobile reveal lg:hidden">
				<p class="faq__cta-label"><?php echo esc_html( $header['cta_heading'] ); ?></p>
				<div class="faq__cta-actions mt-4 flex flex-wrap items-center gap-3">
					<a class="faq__contact link-arrow" href="<?php echo esc_url( $contact_url ); ?>">
						<?php echo esc_html( $header['cta_label'] ); ?>
						<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
					</a>

					<?php
					get_template_part(
						'template-parts/components/social-links',
						null,
						array(
							'class' => 'faq__social',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
