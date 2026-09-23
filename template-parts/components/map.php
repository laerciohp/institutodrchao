<?php
/**
 * Mapa de localização — Figma 133:2909 (imagem + card de vidro).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $title   Título do card (default: Instituto Dr. Chao).
 *   @type string $subtitle Texto curto sob o título (default: Vila Guilherme, São Paulo).
 *   @type string $endereco Endereço completo (usado no link do Maps).
 *   @type string $class    Classes extras no wrapper.
 * }
 */

$args = isset($args) && is_array($args) ? $args : [];

$title = (string) ($args['title'] ?? __('Instituto Dr. Chao', 'instituto-dr-chao'));
$subtitle = (string) ($args['subtitle'] ?? __('Vila Guilherme, São Paulo', 'instituto-dr-chao'));
$endereco = (string) ($args['endereco'] ?? (string) idc_option(
	'idc_endereco',
	"Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003"
));
$class = 'idc-map' . (!empty($args['class']) ? ' ' . (string) $args['class'] : '');

$map_img = idc_asset('assets/images/map-sao-paulo.png');
$pin     = is_readable(IDC_THEME_DIR . '/assets/icons/icon-map-pin-figma.svg')
	? idc_asset('assets/icons/icon-map-pin-figma.svg')
	: idc_asset('assets/icons/icon-map-pin.svg');

$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(str_replace(["\r", "\n"], ' ', $endereco));
?>
<a
	class="<?php echo esc_attr($class); ?>"
	href="<?php echo esc_url($maps_url); ?>"
	target="_blank"
	rel="noopener noreferrer"
	aria-label="<?php echo esc_attr(sprintf(/* translators: %s clinic name */ __('Abrir %s no Google Maps', 'instituto-dr-chao'), $title)); ?>"
>
	<span class="idc-map__media" aria-hidden="true">
		<img class="idc-map__img" src="<?php echo esc_url($map_img); ?>" alt="" width="1216" height="384" loading="lazy" decoding="async">
	</span>
	<span class="idc-map__card">
		<img class="idc-map__card-pin" src="<?php echo esc_url($pin); ?>" alt="" width="20" height="25" decoding="async">
		<strong class="idc-map__card-title"><?php echo esc_html($title); ?></strong>
		<span class="idc-map__card-addr"><?php echo esc_html($subtitle); ?></span>
	</span>
</a>
