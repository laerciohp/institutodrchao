<?php
/**
 * Card do archive de tratamentos.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$post_id = (int) ($args['post_id'] ?? get_the_ID());
$title   = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$thumb   = get_the_post_thumbnail_url($post_id, 'medium_large');
$url     = get_permalink($post_id);
$terms   = get_the_terms($post_id, 'idc_especialidade');
$term    = (is_array($terms) && $terms !== [] && !is_wp_error($terms)) ? $terms[0] : null;
?>
<article class="idc-hub-card idc-tratamento-card">
	<a class="idc-tratamento-card__media-link" href="<?php echo esc_url($url); ?>">
		<?php if ($thumb) : ?>
			<div class="idc-hub-card__media">
				<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" decoding="async">
			</div>
		<?php else : ?>
			<div class="idc-hub-card__media idc-hub-card__media--placeholder" aria-hidden="true"></div>
		<?php endif; ?>
	</a>
	<div class="idc-hub-card__body">
		<?php if ($term instanceof WP_Term) : ?>
			<p class="idc-tratamento-card__tag"><?php echo esc_html($term->name); ?></p>
		<?php endif; ?>
		<h2 class="idc-hub-card__title">
			<a href="<?php echo esc_url($url); ?>"><?php echo esc_html($title); ?></a>
		</h2>
		<?php if ($excerpt !== '') : ?>
			<p class="idc-hub-card__text"><?php echo esc_html($excerpt); ?></p>
		<?php endif; ?>
		<a class="idc-hub-card__link" href="<?php echo esc_url($url); ?>">
			<?php esc_html_e('Saiba mais', 'instituto-dr-chao'); ?>
		</a>
	</div>
</article>
