<?php
/**
 * Cabeçalho de seção (título + lead opcional).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $eyebrow
 *   @type string $title
 *   @type string $lead
 *   @type string $title_id
 *   @type string $class
 * }
 */

$args     = isset($args) && is_array($args) ? $args : [];
$eyebrow  = (string) ($args['eyebrow'] ?? '');
$title    = (string) ($args['title'] ?? '');
$lead     = (string) ($args['lead'] ?? '');
$title_id = (string) ($args['title_id'] ?? '');
$class    = 'idc-section-header' . (!empty($args['class']) ? ' ' . esc_attr((string) $args['class']) : '');

if ($title === '' && $eyebrow === '' && $lead === '') {
	return;
}
?>
<header class="<?php echo esc_attr($class); ?>">
	<?php if ($eyebrow !== '') : ?>
		<?php get_template_part('template-parts/components/eyebrow', null, ['text' => $eyebrow]); ?>
	<?php endif; ?>
	<?php if ($title !== '') : ?>
		<h2
			<?php echo $title_id !== '' ? 'id="' . esc_attr($title_id) . '" ' : ''; ?>
			class="idc-section-header__title"
		><?php echo esc_html($title); ?></h2>
	<?php endif; ?>
	<?php if ($lead !== '') : ?>
		<p class="idc-section-header__lead"><?php echo esc_html($lead); ?></p>
	<?php endif; ?>
</header>
