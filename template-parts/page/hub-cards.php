<?php
/**
 * Cards do hub Especialidades (3 colunas).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$cards = $args['cards'] ?? null;

if (!is_array($cards) || $cards === []) {
	$acf_cards = function_exists('get_field') ? get_field('idc_hub_cards') : null;
	$cards     = is_array($acf_cards) && $acf_cards !== [] ? $acf_cards : idc_default_hub_cards();
}
?>
<section class="idc-hub-cards" aria-label="<?php esc_attr_e('Nossas especialidades', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-hub-cards__grid">
			<?php foreach ($cards as $card) :
				$title = (string) ($card['title'] ?? '');
				$text  = (string) ($card['text'] ?? '');
				$label = (string) ($card['link_label'] ?? __('Saiba mais', 'instituto-dr-chao'));
				$url   = (string) ($card['link_url'] ?? '');
				$tone  = sanitize_html_class((string) ($card['tone'] ?? 'sand'));
				$image = idc_image_url($card['image'] ?? null, is_string($card['image'] ?? null) ? (string) $card['image'] : '');
				$alt   = idc_image_alt($card['image'] ?? null, $title);
				?>
				<article class="idc-hub-card idc-hub-card--<?php echo esc_attr($tone); ?>">
					<?php if ($image !== '') : ?>
						<figure class="idc-hub-card__media">
							<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($alt); ?>" width="360" height="240" decoding="async">
						</figure>
					<?php endif; ?>
					<div class="idc-hub-card__body">
						<h2 class="idc-hub-card__title"><?php echo esc_html($title); ?></h2>
						<p class="idc-hub-card__text"><?php echo esc_html($text); ?></p>
						<?php if ($label !== '' && $url !== '') : ?>
							<a class="idc-hub-card__link" href="<?php echo esc_url($url); ?>">
								<?php echo esc_html($label); ?>
								<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-accent.svg')); ?>" alt="" width="12" height="12" decoding="async">
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
