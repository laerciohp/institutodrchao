<?php
/**
 * Conteúdo — O Instituto (essência + imagem).
 *
 * @package Instituto_Dr_Chao
 */

$essencia_eyebrow = function_exists('get_field') && get_field('idc_instituto_essencia_eyebrow')
	? (string) get_field('idc_instituto_essencia_eyebrow')
	: 'NOSSA ESSÊNCIA';

$essencia_title = function_exists('get_field') && get_field('idc_instituto_essencia_title')
	? (string) get_field('idc_instituto_essencia_title')
	: 'O que nos move';

$essencia_lead = function_exists('get_field') && get_field('idc_instituto_essencia_lead')
	? (string) get_field('idc_instituto_essencia_lead')
	: 'Unimos precisão médica e acolhimento humano para que cada paciente recupere o ritmo natural da sua vida.';

$cards = function_exists('get_field') ? get_field('idc_instituto_cards') : null;
if (!is_array($cards) || $cards === []) {
	$cards = idc_default_instituto_cards();
}

$image     = function_exists('get_field') ? get_field('idc_instituto_image') : null;
$image_url = idc_image_url($image, idc_asset('assets/images/pages/instituto-interior.png'));
$image_alt = idc_image_alt($image, __('Interior do Instituto Dr. Chao', 'instituto-dr-chao'));
?>
<section class="idc-instituto" aria-labelledby="idc-instituto-essencia-title">
	<div class="idc-container">
		<header class="idc-instituto__intro">
			<p class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($essencia_eyebrow); ?></p>
			<h2 id="idc-instituto-essencia-title" class="idc-instituto__title"><?php echo esc_html($essencia_title); ?></h2>
			<p class="idc-instituto__lead"><?php echo esc_html($essencia_lead); ?></p>
		</header>

		<div class="idc-instituto__grid">
			<div class="idc-instituto__cards">
				<?php foreach ($cards as $card) :
					$title = (string) ($card['title'] ?? '');
					$text  = (string) ($card['text'] ?? '');
					$icon  = idc_image_url($card['icon'] ?? null, is_string($card['icon'] ?? null) ? (string) $card['icon'] : '');
					?>
					<article class="idc-instituto__card">
						<?php if ($icon !== '') : ?>
							<div class="idc-instituto__card-icon">
								<img src="<?php echo esc_url($icon); ?>" alt="" width="32" height="32" decoding="async">
							</div>
						<?php endif; ?>
						<h3 class="idc-instituto__card-title"><?php echo esc_html($title); ?></h3>
						<p class="idc-instituto__card-text"><?php echo esc_html($text); ?></p>
					</article>
				<?php endforeach; ?>
			</div>

			<figure class="idc-instituto__media">
				<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" width="560" height="420" decoding="async">
			</figure>
		</div>
	</div>
</section>
