<?php
/**
 * Card de post do blog (home preview + arquivo).
 *
 * Figma Home 136:13076 — variant home:
 *   foto full-bleed 192px + badge categoria overlay; body: título, excerpt, data | Ler mais
 *
 * Figma Blog 133:2566 — variant archive:
 *   foto inset 16px 352×224; body: cat + data, título, excerpt, Ler mais →
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $variant  home|archive. Default archive.
 *   @type bool   $show_cat Exibir categoria. Default true.
 *   @type bool   $show_meta Exibir data / Ler mais. Default true.
 *   @type string $heading  Tag do título (h2|h3). Default h3.
 * }
 */

$variant   = (string) (($args['variant'] ?? 'archive'));
if (!in_array($variant, ['home', 'archive'], true)) {
	$variant = 'archive';
}

$show_cat  = !array_key_exists('show_cat', $args ?? []) || !empty($args['show_cat']);
$show_meta = !array_key_exists('show_meta', $args ?? []) || !empty($args['show_meta']);
$heading   = (string) (($args['heading'] ?? 'h3'));
if (!in_array($heading, ['h2', 'h3'], true)) {
	$heading = 'h3';
}

$cats     = get_the_category();
$cat      = ($show_cat && $cats) ? $cats[0]->name : '';
$cat_ids  = array_map(static fn($c) => (string) $c->term_id, $cats ?: []);
$data_cat = implode(' ', $cat_ids);
$date     = get_the_date('d M Y');
$is_home  = $variant === 'home';

$classes = 'idc-blog-card idc-blog-card--' . $variant;
?>
<a class="<?php echo esc_attr($classes); ?>" href="<?php the_permalink(); ?>"<?php echo $data_cat !== '' ? ' data-cat="' . esc_attr($data_cat) . '"' : ''; ?>>
	<div class="idc-blog-card__media">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
		<?php else : ?>
			<?php
			$fallback = idc_blog_figma_fallback_image((string) get_post_field('post_name', get_the_ID()), (int) get_the_ID());
			?>
			<img src="<?php echo esc_url($fallback); ?>" alt="" loading="lazy" decoding="async" width="640" height="360">
		<?php endif; ?>
		<?php if ($is_home && $cat !== '') : ?>
			<span class="idc-blog-card__cat idc-blog-card__cat--overlay"><?php echo esc_html($cat); ?></span>
		<?php endif; ?>
	</div>

	<div class="idc-blog-card__body">
		<?php if (!$is_home && ($cat !== '' || $show_meta)) : ?>
			<div class="idc-blog-card__topline">
				<?php if ($cat !== '') : ?>
					<span class="idc-blog-card__cat"><?php echo esc_html($cat); ?></span>
				<?php endif; ?>
				<?php if ($show_meta) : ?>
					<span class="idc-blog-card__date"><?php echo esc_html($date); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<<?php echo esc_attr($heading); ?> class="idc-blog-card__title"><?php the_title(); ?></<?php echo esc_attr($heading); ?>>

		<p class="idc-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $is_home ? 18 : 22)); ?></p>

		<?php if ($show_meta) : ?>
			<div class="idc-blog-card__meta">
				<?php if ($is_home) : ?>
					<span class="idc-blog-card__date"><?php echo esc_html($date); ?></span>
					<span class="idc-blog-card__more"><?php esc_html_e('Ler mais', 'instituto-dr-chao'); ?></span>
				<?php else : ?>
					<span class="idc-blog-card__more">
						<?php esc_html_e('Ler mais', 'instituto-dr-chao'); ?>
						<img class="idc-blog-card__more-icon" src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-16.svg')); ?>" alt="" width="10" height="10" decoding="async" aria-hidden="true">
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</a>
