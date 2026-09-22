<?php
/**
 * Card de post do blog (home preview + arquivo).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type bool   $show_cat  Exibir badge de categoria. Default true.
 *   @type bool   $show_meta Exibir data + "Ler mais". Default true.
 *   @type string $heading   Tag do título (h2|h3). Default h3.
 * }
 */

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
?>
<a class="idc-blog-card" href="<?php the_permalink(); ?>"<?php echo $data_cat !== '' ? ' data-cat="' . esc_attr($data_cat) . '"' : ''; ?>>
	<div class="idc-blog-card__media">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
		<?php endif; ?>
	</div>
	<div class="idc-blog-card__body">
		<?php if ($cat !== '' || $show_meta) : ?>
			<div class="idc-blog-card__topline">
				<?php if ($cat !== '') : ?>
					<span class="idc-blog-card__cat"><?php echo esc_html($cat); ?></span>
				<?php endif; ?>
				<?php if ($show_meta) : ?>
					<span class="idc-blog-card__date"><?php echo esc_html(get_the_date('d M Y')); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<<?php echo esc_attr($heading); ?> class="idc-blog-card__title"><?php the_title(); ?></<?php echo esc_attr($heading); ?>>
		<p class="idc-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
		<?php if ($show_meta) : ?>
			<div class="idc-blog-card__meta">
				<span class="idc-blog-card__more">
					<?php esc_html_e('Ler mais', 'instituto-dr-chao'); ?>
					<img class="idc-blog-card__more-icon" src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-16.svg')); ?>" alt="" width="16" height="16" decoding="async" aria-hidden="true">
				</span>
			</div>
		<?php endif; ?>
	</div>
</a>
