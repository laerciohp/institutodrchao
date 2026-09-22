<?php
/**
 * Grade 3×3 — Medicina Integrativa (Figma 133:1887).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title = (string) ($args['title'] ?? __('Tratamentos Integrativos', 'instituto-dr-chao'));

$items = $args['items'] ?? null;
if (!is_array($items) || $items === []) {
	$items = idc_default_integrativa_grid();
}
?>
<section class="idc-integrativa-grid idc-specialty__block" aria-labelledby="idc-integrativa-grid-title">
	<div class="idc-container">
		<header class="idc-specialty__block-header idc-specialty__block-header--center">
			<h2 id="idc-integrativa-grid-title" class="idc-specialty__block-title"><?php echo esc_html($title); ?></h2>
			<span class="idc-specialty__block-rule" aria-hidden="true"></span>
		</header>

		<div class="idc-integrativa-grid__bento">
			<?php foreach ($items as $item) :
				$item_title = (string) ($item['title'] ?? '');
				$text       = (string) ($item['text'] ?? '');
				$icon       = (string) ($item['icon'] ?? '');
				if ($item_title === '') {
					continue;
				}
				?>
				<article class="idc-integrativa-card">
					<?php if ($icon !== '') : ?>
						<div class="idc-integrativa-card__mark">
							<img src="<?php echo esc_url($icon); ?>" alt="" width="18" height="18" decoding="async">
						</div>
					<?php else : ?>
						<span class="idc-integrativa-card__dot" aria-hidden="true"></span>
					<?php endif; ?>
					<h3 class="idc-integrativa-card__title"><?php echo esc_html($item_title); ?></h3>
					<?php if ($text !== '') : ?>
						<p class="idc-integrativa-card__text"><?php echo esc_html($text); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
