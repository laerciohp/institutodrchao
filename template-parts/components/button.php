<?php
/**
 * Botão CTA reutilizável.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$label   = $args['label'] ?? __('Agendar Consulta', 'instituto-dr-chao');
$href    = $args['href'] ?? idc_whatsapp_url_for_context($args['origem'] ?? '');
$variant = $args['variant'] ?? 'primary'; // primary | navy | outline
$class   = 'idc-btn idc-btn--' . sanitize_html_class($variant);
if (!empty($args['class'])) {
	$class .= ' ' . esc_attr($args['class']);
}
$target  = !empty($args['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
?>
<a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url($href); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo esc_html($label); ?>
</a>
