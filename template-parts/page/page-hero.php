<?php
/**
 * Hero genérico de páginas institucionais.
 *
 * Args:
 * - layout: centered | split | overlay | default (esquerda)
 * - image / image_alt: foto do hero split/overlay
 * - centered: bool legado (equivale a layout=centered)
 * - breadcrumb_items: lista intermediária [{label, url}]
 * - cta_label / origem: CTA opcional no hero split (botão WhatsApp)
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$eyebrow           = (string) ($args['eyebrow'] ?? '');
$title_html        = (string) ($args['title_html'] ?? '');
$title_before      = (string) ($args['title_before'] ?? '');
$title_accent      = (string) ($args['title_accent'] ?? '');
$title_after       = (string) ($args['title_after'] ?? '');
$lead              = (string) ($args['lead'] ?? '');
$breadcrumb        = (string) ($args['breadcrumb_label'] ?? '');
$breadcrumb_items  = $args['breadcrumb_items'] ?? [];
$image             = (string) ($args['image'] ?? '');
$image_alt         = (string) ($args['image_alt'] ?? '');
$cta_label         = (string) ($args['cta_label'] ?? '');
$origem            = (string) ($args['origem'] ?? '');
$show_decor        = !empty($args['decor']);
$modifier          = (string) ($args['modifier'] ?? '');

if (!is_array($breadcrumb_items)) {
	$breadcrumb_items = [];
}

$layout = (string) ($args['layout'] ?? '');
if ($layout === '') {
	if (!empty($args['centered'])) {
		$layout = 'centered';
	} elseif ($image !== '') {
		$layout = 'split';
	} else {
		$layout = 'centered';
	}
}

$hero_class = 'idc-page-hero';
if ($layout === 'centered') {
	$hero_class .= ' idc-page-hero--centered';
} elseif ($layout === 'split') {
	$hero_class .= ' idc-page-hero--split';
} elseif ($layout === 'overlay') {
	$hero_class .= ' idc-page-hero--overlay';
} elseif ($layout === 'default') {
	$hero_class .= ' idc-page-hero--default';
}

if ($modifier !== '') {
	$hero_class .= ' idc-page-hero--' . sanitize_html_class($modifier);
}

// Decor orgânico: split sempre; hub/default/blog sob demanda.
if ($layout === 'split' || $show_decor) {
	$show_decor = true;
}

/**
 * Render do bloco de título + lead (+ CTA opcional).
 *
 * @param bool $with_cta
 */
$render_copy = static function (bool $with_cta) use (
	$eyebrow,
	$title_html,
	$title_before,
	$title_accent,
	$title_after,
	$lead,
	$cta_label,
	$origem
): void {
	?>
	<div class="idc-page-hero__inner">
		<?php if ($eyebrow !== '') : ?>
			<p class="idc-eyebrow idc-eyebrow--accent idc-page-hero__eyebrow"><?php echo esc_html($eyebrow); ?></p>
		<?php endif; ?>

		<h1 id="idc-page-hero-title" class="idc-page-hero__title">
			<?php if ($title_html !== '') : ?>
				<?php echo wp_kses_post($title_html); ?>
			<?php else : ?>
				<?php echo esc_html($title_before); ?>
				<?php if ($title_accent !== '') : ?>
					<span class="idc-page-hero__title-accent"><?php echo esc_html($title_accent); ?></span>
				<?php endif; ?>
				<?php echo esc_html($title_after); ?>
			<?php endif; ?>
		</h1>

		<?php if ($lead !== '') : ?>
			<p class="idc-page-hero__lead"><?php echo nl2br(esc_html($lead)); ?></p>
		<?php endif; ?>

		<?php if ($with_cta && $cta_label !== '') : ?>
			<div class="idc-page-hero__actions">
				<?php
				get_template_part('template-parts/components/button', null, [
					'label'    => $cta_label,
					'variant'  => 'primary',
					'origem'   => $origem,
					'external' => true,
					'arrow'    => true,
				]);
				?>
			</div>
		<?php endif; ?>
	</div>
	<?php
};
?>
<section class="<?php echo esc_attr($hero_class); ?>" aria-labelledby="idc-page-hero-title">
	<?php if ($show_decor) : ?>
		<span class="idc-page-hero__decor" aria-hidden="true"></span>
	<?php endif; ?>

	<?php if ($layout === 'overlay' && $image !== '') : ?>
		<div class="idc-page-hero__overlay-bg" aria-hidden="true">
			<img
				src="<?php echo esc_url($image); ?>"
				alt=""
				width="1280"
				height="612"
				decoding="async"
			>
		</div>
		<div class="idc-page-hero__overlay-scrim" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="idc-container">
		<?php if ($breadcrumb !== '') : ?>
			<?php
			get_template_part('template-parts/components/breadcrumb', null, [
				'current' => $breadcrumb,
				'items'   => $breadcrumb_items,
			]);
			?>
		<?php endif; ?>

		<?php if ($layout === 'split' && $image !== '') : ?>
			<div class="idc-page-hero__split">
				<?php $render_copy(true); ?>

				<figure class="idc-page-hero__media">
					<img
						src="<?php echo esc_url($image); ?>"
						alt="<?php echo esc_attr($image_alt !== '' ? $image_alt : $breadcrumb); ?>"
						width="640"
						height="480"
						decoding="async"
					>
				</figure>
			</div>
		<?php else : ?>
			<?php $render_copy(false); ?>
		<?php endif; ?>
	</div>
</section>
