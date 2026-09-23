<?php
/**
 * Diferenciais — Figma 133:421.
 *
 * @package Instituto_Dr_Chao
 */

$eyebrow = (string) idc_option('idc_why_eyebrow', 'DIFERENCIAIS');
$title   = (string) idc_option('idc_why_title', 'Por que escolher o Instituto Dr. Chao?');
$lead    = (string) idc_option('idc_why_lead', 'Unimos a precisão da medicina moderna com o acolhimento humano em um só lugar.');
$image   = idc_option('idc_why_image', null);
$img_fallback = is_readable(IDC_THEME_DIR . '/assets/images/why-choose.png')
	? idc_asset('assets/images/why-choose.png')
	: idc_asset('assets/images/why-choose.jpg');
$img_url = idc_image_url($image, $img_fallback);
$img_alt = idc_image_alt($image, __('Reabilitação assistida no Instituto Dr. Chao', 'instituto-dr-chao'));

$items = idc_option('idc_why_items', []);
if (!is_array($items) || $items === []) {
	$items = idc_default_why_items();
}
?>
<section class="idc-why" aria-labelledby="idc-why-title">
	<div class="idc-container">
		<div class="idc-why__grid">
			<figure class="idc-why__media">
				<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" width="576" height="576" loading="lazy" decoding="async">
			</figure>

			<div class="idc-why__content">
				<header class="idc-why__header">
					<p class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($eyebrow); ?></p>
					<h2 id="idc-why-title" class="idc-why__title"><?php echo esc_html($title); ?></h2>
					<p class="idc-why__lead"><?php echo esc_html($lead); ?></p>
				</header>

				<div class="idc-why__items">
					<?php foreach ($items as $item) :
						$icon = idc_image_url($item['icon'] ?? null, is_string($item['icon'] ?? null) ? (string) $item['icon'] : '');
						?>
						<div class="idc-why__item">
							<?php if ($icon !== '') : ?>
								<div class="idc-why__item-icon">
									<img src="<?php echo esc_url($icon); ?>" alt="" width="64" height="64" decoding="async">
								</div>
							<?php endif; ?>
							<h3 class="idc-why__item-title"><?php echo esc_html((string) ($item['title'] ?? '')); ?></h3>
							<p class="idc-why__item-text"><?php echo esc_html((string) ($item['text'] ?? '')); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
