<?php
/**
 * Homepage FAQ section.
 *
 * @package seahivez-theme
 */

$header = seahivez_get_home_faq_header();
$items  = seahivez_get_home_faq_items();

if ( empty( $items ) ) {
	return;
}

$whatsapp_url = seahivez_get_whatsapp_url();
$contact_url  = ! empty( $header['contact_url'] ) ? $header['contact_url'] : home_url( '/contact/' );
?>

<section class="faq section-spacing bg-warm-white" id="faq" aria-labelledby="faq-heading">
	<div class="site-container">
		<div class="faq__layout grid gap-10 lg:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] lg:gap-16 xl:gap-20">
			<div class="faq__intro reveal">
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
				<h2 id="faq-heading" class="section-heading mt-3">
					<?php echo esc_html( $header['heading'] ); ?>
				</h2>
				<p class="type-body mt-4 max-w-md">
					<?php echo esc_html( $header['description'] ); ?>
				</p>

				<div class="faq__cta mt-8 hidden lg:block">
					<p class="faq__cta-label"><?php echo esc_html( $header['cta_heading'] ); ?></p>
					<div class="faq__cta-actions mt-4 flex flex-wrap items-center gap-4">
						<a class="link-arrow" href="<?php echo esc_url( $contact_url ); ?>">
							<?php echo esc_html( $header['cta_label'] ); ?>
							<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
						</a>

						<?php if ( $whatsapp_url ) : ?>
							<a
								class="faq__whatsapp social-link social-link--compact"
								href="<?php echo esc_url( $whatsapp_url ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e( 'WhatsApp', 'seahivez-theme' ); ?>"
							>
								<?php seahivez_render_social_icon( 'whatsapp' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="faq__list reveal reveal-delay-1">
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
			</div>

			<div class="faq__cta faq__cta--mobile reveal lg:hidden">
				<p class="faq__cta-label"><?php echo esc_html( $header['cta_heading'] ); ?></p>
				<div class="faq__cta-actions mt-4 flex flex-wrap items-center gap-4">
					<a class="link-arrow" href="<?php echo esc_url( $contact_url ); ?>">
						<?php echo esc_html( $header['cta_label'] ); ?>
						<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
					</a>

					<?php if ( $whatsapp_url ) : ?>
						<a
							class="faq__whatsapp social-link social-link--compact"
							href="<?php echo esc_url( $whatsapp_url ); ?>"
							target="_blank"
							rel="noopener noreferrer"
							aria-label="<?php esc_attr_e( 'WhatsApp', 'seahivez-theme' ); ?>"
						>
							<?php seahivez_render_social_icon( 'whatsapp' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
