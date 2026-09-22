<?php
/**
 * Trust bar — Figma Home (rating Google + métricas).
 *
 * @package Instituto_Dr_Chao
 */

$items = idc_option('idc_trust_items', []);
if (!is_array($items) || $items === []) {
	$items = idc_default_trust_items();
}
?>
<section class="idc-trust" aria-label="<?php esc_attr_e('Indicadores de confiança', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-trust__grid">
			<?php foreach ($items as $item) :
				$type  = (string) ($item['type'] ?? 'number');
				$value = (string) ($item['value'] ?? '');
				$label = (string) ($item['label'] ?? '');
				?>
				<div class="idc-trust__item<?php echo $type === 'rating' ? ' idc-trust__item--rating' : ''; ?>">
					<?php if ($type === 'rating') : ?>
						<div class="idc-trust__rating" aria-hidden="true">
							<span class="idc-trust__rating-value"><?php echo esc_html($value !== '' ? $value : '4.9'); ?></span>
							<span class="idc-trust__stars">
								<?php for ($i = 0; $i < 5; $i++) : ?>
									<img src="<?php echo esc_url(idc_asset('assets/icons/star.svg')); ?>" alt="" width="20" height="19" decoding="async">
								<?php endfor; ?>
							</span>
						</div>
					<?php elseif ($type === 'stars') : ?>
						<div class="idc-trust__stars" aria-hidden="true">
							<?php for ($i = 0; $i < 5; $i++) : ?>
								<img src="<?php echo esc_url(idc_asset('assets/icons/star.svg')); ?>" alt="" width="20" height="19" decoding="async">
							<?php endfor; ?>
						</div>
					<?php else : ?>
						<p class="idc-trust__value"><?php echo esc_html($value); ?></p>
					<?php endif; ?>
					<p class="idc-trust__label"><?php echo esc_html($label); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
