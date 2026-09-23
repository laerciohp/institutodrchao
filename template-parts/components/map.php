<?php
/**
 * Mapa de localização — Google Maps (iframe) + card de vidro Figma 133:2909.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $title    Título do card (default: Instituto Dr. Chao).
 *   @type string $subtitle Texto curto sob o título (default: Vila Guilherme, São Paulo).
 *   @type string $endereco Endereço completo (link do Maps / fallback do embed).
 *   @type string $embed    URL do embed (opcional; senão usa opção idc_map_embed).
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

$default_embed = 'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed';
$embed = trim((string) ($args['embed'] ?? ''));
if ($embed === '') {
	$embed = (string) idc_option('idc_map_embed', $default_embed);
}
if ($embed === '') {
	$embed = $default_embed;
}

$pin = is_readable(IDC_THEME_DIR . '/assets/icons/icon-map-pin-figma.svg')
	? idc_asset('assets/icons/icon-map-pin-figma.svg')
	: idc_asset('assets/icons/icon-map-pin.svg');

$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(str_replace(["\r", "\n"], ' ', $endereco));
?>
<div
	class="<?php echo esc_attr($class); ?>"
	aria-label="<?php esc_attr_e('Mapa de localização', 'instituto-dr-chao'); ?>"
>
	<iframe
		class="idc-map__frame"
		src="<?php echo esc_url($embed); ?>"
		loading="lazy"
		referrerpolicy="no-referrer-when-downgrade"
		allowfullscreen
		title="<?php echo esc_attr(sprintf(/* translators: %s clinic name */ __('Mapa — %s', 'instituto-dr-chao'), $title)); ?>"
	></iframe>
	<a
		class="idc-map__card"
		href="<?php echo esc_url($maps_url); ?>"
		target="_blank"
		rel="noopener noreferrer"
	>
		<img class="idc-map__card-pin" src="<?php echo esc_url($pin); ?>" alt="" width="20" height="25" decoding="async">
		<strong class="idc-map__card-title"><?php echo esc_html($title); ?></strong>
		<span class="idc-map__card-addr"><?php echo esc_html($subtitle); ?></span>
	</a>
</div>
