<?php
/**
 * Card de depoimento.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $quote
 *   @type string $name
 *   @type string $role
 *   @type string $photo
 *   @type int    $rating
 *   @type string $class
 * }
 */

$args   = isset($args) && is_array($args) ? $args : [];
$quote  = trim((string) ($args['quote'] ?? ''));
$quote  = preg_replace('/^[“"„«]\s*|\s*[”"»]$/u', '', $quote) ?? $quote;
$name   = (string) ($args['name'] ?? '');
$role   = (string) ($args['role'] ?? '');
$photo  = (string) ($args['photo'] ?? '');
$rating = max(1, min(5, (int) ($args['rating'] ?? 5)));
$class  = 'idc-testimonial' . (!empty($args['class']) ? ' ' . esc_attr((string) $args['class']) : '');

$initials = '';
if ($name !== '') {
	$parts = preg_split('/\s+/', $name) ?: [];
	$initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
}
?>
<blockquote class="<?php echo esc_attr($class); ?>">
	<div class="idc-testimonial__stars" aria-label="<?php echo esc_attr(sprintf(/* translators: %d stars */ __('%d de 5 estrelas', 'instituto-dr-chao'), $rating)); ?>">
		<?php for ($i = 0; $i < $rating; $i++) : ?>
			<img src="<?php echo esc_url(idc_asset('assets/icons/star-sm.svg')); ?>" alt="" width="20" height="19" decoding="async">
		<?php endfor; ?>
	</div>
	<?php if ($quote !== '') : ?>
		<p class="idc-testimonial__quote">“<?php echo esc_html($quote); ?>”</p>
	<?php endif; ?>
	<footer class="idc-testimonial__author">
		<div class="idc-testimonial__avatar"<?php echo $photo === '' && $initials !== '' ? ' data-initials="' . esc_attr($initials) . '"' : ''; ?>>
			<?php if ($photo !== '') : ?>
				<img src="<?php echo esc_url($photo); ?>" alt="" loading="lazy" decoding="async" width="48" height="48">
			<?php endif; ?>
		</div>
		<div>
			<?php if ($name !== '') : ?>
				<p class="idc-testimonial__name"><?php echo esc_html($name); ?></p>
			<?php endif; ?>
			<?php if ($role !== '') : ?>
				<p class="idc-testimonial__role"><?php echo esc_html($role); ?></p>
			<?php endif; ?>
		</div>
	</footer>
</blockquote>
