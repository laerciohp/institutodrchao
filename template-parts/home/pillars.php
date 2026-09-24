<?php
/**
 * Três pilares (bento) — Figma 133:352.
 *
 * @package Instituto_Dr_Chao
 */

$eyebrow = (string) idc_option('idc_pillars_eyebrow', 'NOSSA ABORDAGEM');
$before  = (string) idc_option('idc_pillars_title_before', 'Três pilares de');
$accent  = (string) idc_option('idc_pillars_title_accent', 'cuidado');
$after   = (string) idc_option('idc_pillars_title_after', '');
$lead    = (string) idc_option(
	'idc_pillars_lead',
	'Não olhamos apenas para o sintoma. Atuamos com ortopedia de precisão, reabilitação física e medicina integrativa para restaurar sua qualidade de vida.'
);

$cards = idc_option('idc_pillars_cards', []);
if (!is_array($cards) || $cards === []) {
	$cards = idc_default_pillars_cards();
}
?>
<section class="idc-pillars" aria-labelledby="idc-pillars-title">
	<div class="idc-container">
		<header class="idc-pillars__intro">
			<p class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($eyebrow); ?></p>
			<h2 id="idc-pillars-title" class="idc-pillars__title">
				<?php echo esc_html($before); ?>
				<span class="idc-pillars__title-accent"><?php echo esc_html($accent); ?></span><?php
				if ($after !== '') {
					echo esc_html(' ' . $after);
				}
				?>
			</h2>
			<p class="idc-pillars__lead"><?php echo esc_html($lead); ?></p>
		</header>

		<div class="idc-pillars__grid">
			<?php foreach ($cards as $card) :
				$layout = $card['layout'] ?? 'narrow';
				$tone   = $card['tone'] ?? 'sand';
				$title  = (string) ($card['title'] ?? '');
				$text   = (string) ($card['text'] ?? '');
				$label  = (string) ($card['link_label'] ?? '');
				$url    = (string) ($card['link_url'] ?? '');
				$wa     = !empty($card['use_whatsapp']);
				$icon   = idc_image_url($card['icon'] ?? null, is_string($card['icon'] ?? null) ? (string) $card['icon'] : '');
				$image  = idc_image_url($card['image'] ?? null, is_string($card['image'] ?? null) ? (string) $card['image'] : '');

				if ($wa) {
					$url = idc_whatsapp_url_for_context('home-pilares');
				}

				$mod = 'idc-pillar--' . sanitize_html_class($layout);
				$mod .= ' idc-pillar--tone-' . sanitize_html_class($tone);
				?>
				<article class="idc-pillar <?php echo esc_attr($mod); ?>">
					<?php if ($layout === 'cta') : ?>
						<?php /* Figma 133:408 Decorative/Info Tile — cream + raiz orgânica, texto escuro */ ?>
						<span class="idc-pillar__cta-decor" aria-hidden="true"></span>
						<div class="idc-pillar__cta-inner">
							<h3 class="idc-pillar__title"><?php echo esc_html($title); ?></h3>
							<p class="idc-pillar__text"><?php echo esc_html($text); ?></p>
							<?php if ($label !== '') : ?>
								<?php
								get_template_part('template-parts/components/button', null, [
									'label'    => $label,
									'variant'  => 'navy',
									'href'     => $url,
									'external' => $wa,
									'class'    => 'idc-btn--sm idc-pillar__cta-btn',
								]);
								?>
							<?php endif; ?>
						</div>
					<?php elseif ($layout === 'primary') : ?>
						<div class="idc-pillar__body">
							<?php if ($icon !== '') : ?>
								<div class="idc-pillar__icon"><img src="<?php echo esc_url($icon); ?>" alt="" width="22" height="24" decoding="async"></div>
							<?php endif; ?>
							<h3 class="idc-pillar__title"><?php echo esc_html($title); ?></h3>
							<p class="idc-pillar__text"><?php echo esc_html($text); ?></p>
							<?php if ($label !== '' && $url !== '') : ?>
								<a class="idc-pillar__link idc-pillar__link--accent" href="<?php echo esc_url($url); ?>">
									<?php echo esc_html($label); ?>
									<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-accent.svg')); ?>" alt="" width="12" height="12" decoding="async">
								</a>
							<?php endif; ?>
						</div>
						<?php if ($image !== '') : ?>
							<figure class="idc-pillar__media">
								<img src="<?php echo esc_url($image); ?>" alt="" width="336" height="328" decoding="async" loading="lazy">
							</figure>
						<?php endif; ?>
					<?php else : ?>
						<?php if ($icon !== '') : ?>
							<div class="idc-pillar__icon"><img src="<?php echo esc_url($icon); ?>" alt="" width="22" height="24" decoding="async"></div>
						<?php endif; ?>
						<h3 class="idc-pillar__title"><?php echo esc_html($title); ?></h3>
						<p class="idc-pillar__text"><?php echo esc_html($text); ?></p>
						<?php if ($label !== '' && $url !== '') : ?>
							<a class="idc-pillar__link" href="<?php echo esc_url($url); ?>">
								<?php echo esc_html($label); ?>
								<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow.svg')); ?>" alt="" width="12" height="12" decoding="async">
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
