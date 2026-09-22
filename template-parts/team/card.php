<?php
/**
 * Card de profissional (home / archive).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$excerpt  = !empty($args['excerpt']);
$class    = trim('idc-team-card ' . (string) ($args['class'] ?? ''));
$carousel = !empty($args['carousel']);

$crm  = function_exists('get_field')
	? (string) get_field('idc_crm')
	: (string) get_post_meta(get_the_ID(), 'idc_crm', true);
$spec = function_exists('get_field')
	? (string) get_field('idc_especialidade_txt')
	: (string) get_post_meta(get_the_ID(), 'idc_especialidade_txt', true);
?>
<article class="<?php echo esc_attr($class); ?>"<?php echo $carousel ? ' data-idc-carousel-slide' : ''; ?>>
	<a class="idc-team-card__link" href="<?php the_permalink(); ?>">
		<figure class="idc-team-card__photo">
			<?php if (has_post_thumbnail()) : ?>
				<?php the_post_thumbnail('large', ['loading' => 'lazy']); ?>
			<?php endif; ?>
		</figure>
		<h3 class="idc-team-card__name"><?php the_title(); ?></h3>
		<?php if ($spec !== '') : ?>
			<p class="idc-team-card__spec"><?php echo esc_html($spec); ?></p>
		<?php endif; ?>
		<?php if ($crm !== '') : ?>
			<p class="idc-team-card__crm"><?php echo esc_html($crm); ?></p>
		<?php endif; ?>
		<?php if ($excerpt) : ?>
			<div class="idc-team-card__bio"><?php echo wp_kses_post(wp_trim_words(get_the_content(), 55)); ?></div>
		<?php endif; ?>
	</a>
</article>
