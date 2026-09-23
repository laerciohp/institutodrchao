<?php
/**
 * Cards de tratamentos com accordion — Ortopedia Regenerativa (Figma).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title = (string) ($args['title'] ?? __('Tratamentos regenerativos', 'instituto-dr-chao'));

$cards = $args['cards'] ?? null;
if (!is_array($cards) || $cards === []) {
	$cards = idc_default_ortopedia_treatments();
}
?>
<section class="idc-treatment-faq idc-specialty__block" aria-labelledby="idc-treatment-faq-title">
	<span class="idc-specialty__decor idc-specialty__decor--organic" aria-hidden="true"></span>
	<div class="idc-container">
		<header class="idc-specialty__block-header idc-specialty__block-header--center">
			<h2 id="idc-treatment-faq-title" class="idc-specialty__block-title"><?php echo esc_html($title); ?></h2>
			<span class="idc-specialty__block-rule" aria-hidden="true"></span>
		</header>

		<div class="idc-treatment-faq__grid">
			<?php foreach ($cards as $card_index => $card) :
				$card_title = (string) ($card['title'] ?? '');
				$intro      = (string) ($card['intro'] ?? ($card['text'] ?? ''));
				$icon       = (string) ($card['icon'] ?? '');
				$faqs       = $card['faqs'] ?? [];
				if ($card_title === '') {
					continue;
				}
				if ($icon === '') {
					if (stripos($card_title, 'Viscos') !== false) {
						$icon = idc_asset('assets/icons/tx-viscos.svg');
					} elseif (stripos($card_title, 'Ozoni') !== false) {
						$icon = idc_asset('assets/icons/tx-ozonio.svg');
					}
				}
				if (!is_array($faqs)) {
					$faqs = [];
				}

				$static_faqs    = [];
				$accordion_faqs = [];
				foreach ($faqs as $item) {
					$question = (string) ($item['question'] ?? '');
					if ($question === '') {
						continue;
					}
					if (
						count($static_faqs) < 3
						&& preg_match('/o que é|quando considerar|como funciona/iu', $question)
					) {
						$static_faqs[] = $item;
					} else {
						$accordion_faqs[] = $item;
					}
				}

				$prefix = 'idc-tx-' . ($card_index + 1);
				?>
				<article class="idc-treatment-faq-card">
					<?php if ($icon !== '') : ?>
						<div class="idc-treatment-faq-card__icon" aria-hidden="true">
							<img src="<?php echo esc_url($icon); ?>" alt="" width="48" height="48" decoding="async">
						</div>
					<?php endif; ?>
					<h3 class="idc-treatment-faq-card__title"><?php echo esc_html($card_title); ?></h3>
					<?php if ($intro !== '') : ?>
						<p class="idc-treatment-faq-card__intro"><?php echo esc_html($intro); ?></p>
					<?php endif; ?>

					<?php foreach ($static_faqs as $item) :
						$question = (string) ($item['question'] ?? '');
						$answer   = (string) ($item['answer'] ?? '');
						?>
						<div class="idc-treatment-faq-card__section">
							<h4 class="idc-treatment-faq-card__section-title"><?php echo esc_html($question); ?></h4>
							<?php if ($answer !== '') : ?>
								<p class="idc-treatment-faq-card__section-text"><?php echo esc_html($answer); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>

					<?php if ($accordion_faqs !== []) : ?>
						<div class="idc-accordion idc-accordion--in-card" data-idc-accordion>
							<?php foreach ($accordion_faqs as $faq_index => $item) :
								$question = (string) ($item['question'] ?? '');
								$answer   = (string) ($item['answer'] ?? '');
								if ($question === '') {
									continue;
								}
								$id = $prefix . '-faq-' . ($faq_index + 1);
								?>
								<div class="idc-accordion__item">
									<h4 class="idc-accordion__heading">
										<button
											type="button"
											class="idc-accordion__trigger"
											id="<?php echo esc_attr($id); ?>-trigger"
											aria-expanded="false"
											aria-controls="<?php echo esc_attr($id); ?>-panel"
											data-idc-accordion-trigger
										>
											<span><?php echo esc_html($question); ?></span>
											<img class="idc-accordion__chevron" src="<?php echo esc_url(idc_asset('assets/icons/chevron-down.svg')); ?>" alt="" width="8" height="5" decoding="async" aria-hidden="true">
										</button>
									</h4>
									<div
										class="idc-accordion__panel"
										id="<?php echo esc_attr($id); ?>-panel"
										role="region"
										aria-labelledby="<?php echo esc_attr($id); ?>-trigger"
										hidden
									>
										<p><?php echo esc_html($answer); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
