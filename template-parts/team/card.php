<?php
/**
 * Card de profissional (home / archive) — abre modal em carrossel.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$excerpt  = !empty($args['excerpt']);
$class    = trim('idc-team-card ' . (string) ($args['class'] ?? ''));
$carousel = !empty($args['carousel']);
$post_id  = (int) get_the_ID();

$crm  = idc_profissional_meta($post_id, 'idc_crm');
$spec = idc_profissional_meta($post_id, 'idc_especialidade_txt');
$bio  = $excerpt ? idc_profissional_excerpt_html($post_id, 3) : '';
?>
<article
	class="<?php echo esc_attr($class); ?>"
	<?php echo $carousel ? ' data-idc-carousel-slide' : ''; ?>
	data-idc-team-id="<?php echo esc_attr((string) $post_id); ?>"
>
	<a
		class="idc-team-card__link"
		href="<?php the_permalink(); ?>"
		data-idc-team-modal-open="<?php echo esc_attr((string) $post_id); ?>"
		aria-haspopup="dialog"
	>
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
		<?php if ($bio !== '') : ?>
			<div class="idc-team-card__bio"><?php echo wp_kses_post($bio); ?></div>
			<span class="idc-team-card__more"><?php esc_html_e('Continuar lendo', 'instituto-dr-chao'); ?></span>
		<?php endif; ?>
	</a>
</article>
