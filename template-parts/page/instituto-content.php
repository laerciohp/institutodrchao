<?php
/**
 * Conteúdo — O Instituto (essência + imagem + 4 diferenciais).
 *
 * @package Instituto_Dr_Chao
 */

$essencia_eyebrow = function_exists('get_field') && get_field('idc_instituto_essencia_eyebrow')
	? (string) get_field('idc_instituto_essencia_eyebrow')
	: 'NOSSA ESSÊNCIA';

$essencia_title = function_exists('get_field') && get_field('idc_instituto_essencia_title')
	? (string) get_field('idc_instituto_essencia_title')
	: 'Cuidado que une experiência, ciência e acolhimento';

$essencia_lead = function_exists('get_field') && get_field('idc_instituto_essencia_lead')
	? (string) get_field('idc_instituto_essencia_lead')
	: '';

$cards = function_exists('get_field') ? get_field('idc_instituto_cards') : null;
if (!is_array($cards) || $cards === []) {
	$cards = idc_default_instituto_cards();
}

$image     = function_exists('get_field') ? get_field('idc_instituto_image') : null;
$image_url = idc_image_url($image, idc_asset('assets/images/pages/instituto-interior.jpg'));
$image_alt = idc_image_alt($image, __('Interior do Instituto Dr. Chao', 'instituto-dr-chao'));
?>
<section class="idc-instituto" aria-labelledby="idc-instituto-essencia-title">
	<div class="idc-container">
		<div class="idc-instituto__grid">
			<figure class="idc-instituto__media">
				<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" width="576" height="600" decoding="async">
			</figure>

			<div class="idc-instituto__content">
				<header class="idc-instituto__intro">
					<p class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($essencia_eyebrow !== '' ? $essencia_eyebrow : 'NOSSA ESSÊNCIA'); ?></p>
					<h2 id="idc-instituto-essencia-title" class="idc-instituto__title"><?php echo esc_html($essencia_title !== '' ? $essencia_title : 'Cuidado que une experiência, ciência e acolhimento'); ?></h2>
					<?php if ($essencia_lead !== '') : ?>
						<p class="idc-instituto__lead"><?php echo esc_html($essencia_lead); ?></p>
					<?php endif; ?>
				</header>

				<div class="idc-instituto__cards">
					<?php foreach ($cards as $card) :
						$title = (string) ($card['title'] ?? '');
						$text  = (string) ($card['text'] ?? '');
						$icon  = idc_image_url($card['icon'] ?? null, is_string($card['icon'] ?? null) ? (string) $card['icon'] : '');
						?>
						<article class="idc-instituto__card">
							<?php if ($icon !== '') : ?>
								<div class="idc-instituto__card-icon">
									<img src="<?php echo esc_url($icon); ?>" alt="" width="18" height="18" decoding="async">
								</div>
							<?php endif; ?>
							<h3 class="idc-instituto__card-title"><?php echo esc_html($title); ?></h3>
							<p class="idc-instituto__card-text"><?php echo esc_html($text); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
