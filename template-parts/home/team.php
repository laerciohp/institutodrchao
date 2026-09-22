<?php
/**
 * Corpo clínico preview — CPT idc_profissional + intro ACF (carrossel).
 *
 * @package Instituto_Dr_Chao
 */

$title = (string) idc_option('idc_team_title', 'Nosso Corpo Clínico');
$lead  = (string) idc_option(
	'idc_team_lead',
	'No Instituto Dr. Chao, a equipe de todos os setores abraça a missão de oferecer atendimento com cordialidade, acolhimento e empatia. Entendemos que cada pessoa carrega uma história única — e escutá-la com atenção é nossa responsabilidade.'
);
$cta_l = (string) idc_option('idc_team_cta_label', 'Conheça toda a equipe');
$cta_u = (string) idc_option(
	'idc_team_cta_url',
	get_post_type_archive_link('idc_profissional') ?: home_url('/corpo-clinico/')
);
$count = (int) idc_option('idc_team_count', 7);

$query = new WP_Query([
	'post_type'      => 'idc_profissional',
	'posts_per_page' => max(1, $count),
	'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
	'post_status'    => 'publish',
]);
$found = (int) $query->post_count;
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
			<div class="idc-team__carousel" data-idc-carousel data-idc-autoplay="0">
				<div class="idc-team__viewport">
					<div class="idc-team__grid idc-team__track" data-idc-carousel-track>
						<?php
						while ($query->have_posts()) :
							$query->the_post();
							get_template_part('template-parts/team/card', null, [
								'excerpt'  => true,
								'class'    => 'idc-team__slide',
								'carousel' => true,
							]);
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>

				<?php if ($found > 1) : ?>
					<div class="idc-carousel__controls">
						<div class="idc-carousel__dots" data-idc-carousel-dots role="tablist" aria-label="<?php esc_attr_e('Navegação da equipe', 'instituto-dr-chao'); ?>"></div>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p class="idc-team__lead"><?php esc_html_e('Cadastre profissionais em Corpo clínico no painel para exibi-los aqui.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</div>
</section>
