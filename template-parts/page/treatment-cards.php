<?php
/**
 * Cards de tratamentos — Ortopedia Regenerativa.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$eyebrow = (string) ($args['eyebrow'] ?? __('TRATAMENTOS', 'instituto-dr-chao'));
$title   = (string) ($args['title'] ?? __('Caminhos terapêuticos', 'instituto-dr-chao'));
$lead    = (string) ($args['lead'] ?? __('Priorizamos opções conservadoras e regenerativas; a cirurgia entra quando realmente necessária.', 'instituto-dr-chao'));

$cards = $args['cards'] ?? null;
if (!is_array($cards) || $cards === []) {
	$cards = idc_default_ortopedia_treatments();
}
?>
<section class="idc-treatment-cards idc-specialty__block" aria-labelledby="idc-treatment-cards-title">
	<span class="idc-specialty__decor idc-specialty__decor--organic" aria-hidden="true"></span>
	<div class="idc-container">
		<header class="idc-specialty__block-header">
			<?php if ($eyebrow !== '') : ?>
				<p class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($eyebrow); ?></p>
			<?php endif; ?>
			<h2 id="idc-treatment-cards-title" class="idc-specialty__block-title"><?php echo esc_html($title); ?></h2>
			<?php if ($lead !== '') : ?>
				<p class="idc-specialty__block-lead"><?php echo esc_html($lead); ?></p>
			<?php endif; ?>
		</header>

		<div class="idc-treatment-cards__grid">
			<?php foreach ($cards as $card) :
				$card_title = (string) ($card['title'] ?? '');
				$subtitle   = (string) ($card['subtitle'] ?? '');
				$text       = (string) ($card['text'] ?? '');
				$icon       = (string) ($card['icon'] ?? '');
				$link_url   = (string) ($card['link_url'] ?? '');
				$link_label = (string) ($card['link_label'] ?? __('Saiba mais', 'instituto-dr-chao'));
				if ($card_title === '') {
					continue;
				}
				?>
				<article class="idc-treatment-card">
					<?php if ($icon !== '') : ?>
						<div class="idc-treatment-card__icon">
							<img src="<?php echo esc_url($icon); ?>" alt="" width="40" height="40" decoding="async">
						</div>
					<?php endif; ?>
					<?php if ($subtitle !== '') : ?>
						<p class="idc-treatment-card__subtitle"><?php echo esc_html($subtitle); ?></p>
					<?php endif; ?>
					<h3 class="idc-treatment-card__title"><?php echo esc_html($card_title); ?></h3>
					<?php if ($text !== '') : ?>
						<p class="idc-treatment-card__text"><?php echo esc_html($text); ?></p>
					<?php endif; ?>
					<?php if ($link_url !== '') : ?>
						<a class="idc-treatment-card__link" href="<?php echo esc_url($link_url); ?>">
							<?php echo esc_html($link_label); ?>
							<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-accent.svg')); ?>" alt="" width="12" height="12" decoding="async">
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
