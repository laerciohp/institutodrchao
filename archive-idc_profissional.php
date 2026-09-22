<?php
/**
 * Archive — Corpo clínico.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$lead_default = 'No Instituto Dr. Chao, os membros da equipe de todos os setores abraçam a missão de oferecer um atendimento repleto de cordialidade, acolhimento e empatia. Entendemos que a pessoa que nos procura carrega uma história única, e é nossa responsabilidade escutá-la com atenção e cuidado.';

$eyebrow = (string) idc_option('idc_team_archive_eyebrow', __('CORPO CLÍNICO', 'instituto-dr-chao'));
$title  = (string) idc_option('idc_team_archive_title', __('Conheça nossa equipe', 'instituto-dr-chao'));
$lead   = (string) idc_option('idc_team_lead', $lead_default);
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $title,
		'lead'             => $lead,
		'breadcrumb_label' => __('Corpo Clínico', 'instituto-dr-chao'),
		'centered'         => true,
	]);
	?>

	<section class="idc-team-archive" aria-label="<?php esc_attr_e('Lista do corpo clínico', 'instituto-dr-chao'); ?>">
		<div class="idc-container">
			<?php if (have_posts()) : ?>
				<div class="idc-team-archive__grid">
					<?php
					while (have_posts()) :
						the_post();
						get_template_part('template-parts/team/card', null, [
							'excerpt' => true,
						]);
					endwhile;
					?>
				</div>
			<?php else : ?>
				<p class="idc-team-archive__empty">
					<?php esc_html_e('Nenhum profissional cadastrado no momento.', 'instituto-dr-chao'); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'corpo-clinico',
		'align'  => 'center',
	]);
	?>
</main>

<?php
get_footer();
