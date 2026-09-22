<?php
/**
 * Hero genérico de páginas institucionais.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$eyebrow         = (string) ($args['eyebrow'] ?? '');
$title_html      = (string) ($args['title_html'] ?? '');
$title_before    = (string) ($args['title_before'] ?? '');
$title_accent    = (string) ($args['title_accent'] ?? '');
$title_after     = (string) ($args['title_after'] ?? '');
$lead            = (string) ($args['lead'] ?? '');
$breadcrumb      = (string) ($args['breadcrumb_label'] ?? '');
$centered        = !empty($args['centered']);
$hero_class      = 'idc-page-hero' . ($centered ? ' idc-page-hero--centered' : '');
?>
<section class="<?php echo esc_attr($hero_class); ?>" aria-labelledby="idc-page-hero-title">
	<div class="idc-container">
		<?php if ($breadcrumb !== '') : ?>
			<?php get_template_part('template-parts/components/breadcrumb', null, ['current' => $breadcrumb]); ?>
		<?php endif; ?>

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
		</div>
	</div>
</section>
