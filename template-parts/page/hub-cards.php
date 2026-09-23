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

$theme_fallbacks = [
	idc_asset('assets/images/pages/hub-ortopedia.jpg'),
	idc_asset('assets/images/pages/hub-fisio.jpg'),
	idc_asset('assets/images/pages/hub-integrativa.jpg'),
];
?>
<section class="idc-hub-cards" aria-label="<?php esc_attr_e('Nossas especialidades', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-hub-cards__grid">
			<?php foreach ($cards as $index => $card) :
				$title = (string) ($card['title'] ?? '');
				$text  = (string) ($card['text'] ?? '');
				$label = (string) ($card['link_label'] ?? __('Saiba mais', 'instituto-dr-chao'));
				$url   = (string) ($card['link_url'] ?? '');
				$tone  = sanitize_html_class((string) ($card['tone'] ?? 'sand'));
				$fallback = $theme_fallbacks[(int) $index] ?? $theme_fallbacks[0];
				$image = idc_image_url($card['image'] ?? null, is_string($card['image'] ?? null) ? (string) $card['image'] : $fallback);
				if ($image === '') {
					$image = $fallback;
				}
				$alt = idc_image_alt($card['image'] ?? null, $title);
				get_template_part('template-parts/components/hub-card', null, [
					'title'      => $title,
					'text'       => $text,
					'link_label' => $label,
					'link_url'   => $url,
					'tone'       => $tone,
					'image'      => $image,
					'image_alt'  => $alt,
				]);
			endforeach; ?>
		</div>
	</div>
</section>
