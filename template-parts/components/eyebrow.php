<?php
/**
 * Eyebrow tipográfico do design system.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $text
 *   @type string $variant accent|muted
 *   @type string $class
 *   @type string $tag
 * }
 */

$args    = isset($args) && is_array($args) ? $args : [];
$text    = (string) ($args['text'] ?? '');
$variant = (string) ($args['variant'] ?? 'accent');
$tag     = preg_replace('/[^a-z0-9]/', '', strtolower((string) ($args['tag'] ?? 'span'))) ?: 'span';
$class   = 'idc-eyebrow idc-eyebrow--' . sanitize_html_class($variant);
if (!empty($args['class'])) {
	$class .= ' ' . esc_attr((string) $args['class']);
}
if ($text === '') {
	return;
}
printf(
	'<%1$s class="%2$s">%3$s</%1$s>',
	$tag, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelist tag
	esc_attr($class),
	esc_html($text)
);
