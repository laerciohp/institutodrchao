<?php
/**
 * Card do hub de especialidades.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $title
 *   @type string $text
 *   @type string $link_label
 *   @type string $link_url
 *   @type string $image
 *   @type string $image_alt
 *   @type string $tone sand|peach|cream
 * }
 */

$args       = isset($args) && is_array($args) ? $args : [];
$title      = (string) ($args['title'] ?? '');
$text       = (string) ($args['text'] ?? '');
$link_label = (string) ($args['link_label'] ?? __('Saiba mais', 'instituto-dr-chao'));
$link_url   = (string) ($args['link_url'] ?? '');
$image      = (string) ($args['image'] ?? '');
$image_alt  = (string) ($args['image_alt'] ?? $title);
$tone       = (string) ($args['tone'] ?? 'sand');
?>
<article class="idc-hub-card idc-hub-card--<?php echo esc_attr($tone); ?>">
	<?php if ($image !== '') : ?>
		<figure class="idc-hub-card__media">
			<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>" width="360" height="192" decoding="async" loading="lazy">
		</figure>
	<?php endif; ?>
	<div class="idc-hub-card__body">
		<?php if ($title !== '') : ?>
			<h2 class="idc-hub-card__title"><?php echo esc_html($title); ?></h2>
		<?php endif; ?>
		<?php if ($text !== '') : ?>
			<p class="idc-hub-card__text"><?php echo esc_html($text); ?></p>
		<?php endif; ?>
		<?php if ($link_label !== '' && $link_url !== '') : ?>
			<a class="idc-hub-card__link" href="<?php echo esc_url($link_url); ?>">
				<?php echo esc_html($link_label); ?>
				<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-accent.svg')); ?>" alt="" width="12" height="12" decoding="async">
			</a>
		<?php endif; ?>
	</div>
</article>
