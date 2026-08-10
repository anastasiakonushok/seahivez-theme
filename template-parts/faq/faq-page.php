<?php
/**
 * Dedicated FAQ page — reuses homepage accordion JS via data attributes.
 *
 * @package seahivez-theme
 */

$groups       = seahivez_get_faq_page_groups();
$whatsapp_url = seahivez_get_whatsapp_url();
$global_index = 0;
?>

<section class="faq-page section-spacing bg-warm-white" aria-labelledby="faq-page-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php esc_html_e( 'FAQ', 'seahivez-theme' ); ?></p>
			<h2 id="faq-page-heading" class="section-heading mt-3">
				<?php esc_html_e( 'Frequently asked questions', 'seahivez-theme' ); ?>
			</h2>
		</div>

		<div class="mt-12 space-y-12">
			<?php foreach ( $groups as $group ) : ?>
				<?php if ( empty( $group['items'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>

				<div class="reveal">
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
				<div class="faq-accordion reveal" data-faq-accordion>
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

		<div class="mt-14 reveal border-t border-gray-200 pt-10">
			<p class="text-lg font-medium text-navy-900"><?php esc_html_e( 'Still have a question?', 'seahivez-theme' ); ?></p>
			<div class="mt-4 flex flex-wrap items-center gap-4">
				<?php if ( $whatsapp_url ) : ?>
					<a class="btn-primary" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Chat with us on WhatsApp', 'seahivez-theme' ); ?>
					</a>
				<?php endif; ?>
				<a class="link-arrow" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php esc_html_e( 'Contact us', 'seahivez-theme' ); ?>
					<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
