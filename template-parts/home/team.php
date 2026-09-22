<?php
/**
 * Corpo clínico preview — CPT idc_profissional + intro ACF.
 *
 * @package Instituto_Dr_Chao
 */

$title = (string) idc_option('idc_team_title', 'Nosso Corpo Clínico');
$lead  = (string) idc_option('idc_team_lead', 'Profissionais dedicados à sua recuperação.');
$cta_l = (string) idc_option('idc_team_cta_label', 'Conheça toda a equipe');
$cta_u = (string) idc_option('idc_team_cta_url', home_url('/#corpo-clinico'));
$count = (int) idc_option('idc_team_count', 3);

$query = new WP_Query([
	'post_type'      => 'idc_profissional',
	'posts_per_page' => max(1, $count),
	'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
	'post_status'    => 'publish',
]);
?>
<section class="idc-team" id="corpo-clinico" aria-labelledby="idc-team-title">
	<div class="idc-container">
		<header class="idc-team__header">
			<div>
				<h2 id="idc-team-title" class="idc-team__title"><?php echo esc_html($title); ?></h2>
				<p class="idc-team__lead"><?php echo esc_html($lead); ?></p>
			</div>
			<?php if ($cta_l !== '') : ?>
				<a class="idc-team__cta" href="<?php echo esc_url($cta_u); ?>">
					<?php echo esc_html($cta_l); ?>
					<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow.svg')); ?>" alt="" width="12" height="12" decoding="async">
				</a>
			<?php endif; ?>
		</header>

		<?php if ($query->have_posts()) : ?>
			<div class="idc-team__grid">
				<?php
				while ($query->have_posts()) :
					$query->the_post();
					$crm  = function_exists('get_field') ? (string) get_field('idc_crm') : '';
					$spec = function_exists('get_field') ? (string) get_field('idc_especialidade_txt') : '';
					?>
					<article class="idc-team-card">
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
						<div class="idc-team-card__bio"><?php echo wp_kses_post(wp_trim_words(get_the_content(), 55)); ?></div>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p class="idc-team__lead"><?php esc_html_e('Cadastre profissionais em Corpo clínico no painel para exibi-los aqui.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</div>
</section>
