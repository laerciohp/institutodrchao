<?php
/**
 * Single — Profissional do corpo clínico.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$archive_url = get_post_type_archive_link('idc_profissional') ?: home_url('/corpo-clinico/');
$origem      = 'profissional';
?>

<main id="main" class="site-main site-main--page">
	<?php if (have_posts()) : ?>
		<?php
		while (have_posts()) :
			the_post();

			$crm  = function_exists('get_field')
				? (string) get_field('idc_crm')
				: (string) get_post_meta(get_the_ID(), 'idc_crm', true);
			$spec = function_exists('get_field')
				? (string) get_field('idc_especialidade_txt')
				: (string) get_post_meta(get_the_ID(), 'idc_especialidade_txt', true);
			$slug   = (string) get_post_field('post_name', get_the_ID());
			$origem = 'profissional-' . $slug;
			?>
			<article <?php post_class('idc-profissional'); ?>>
				<div class="idc-container">
					<?php
					get_template_part('template-parts/components/breadcrumb', null, [
						'items' => [
							[
								'label' => __('Corpo Clínico', 'instituto-dr-chao'),
								'url'   => $archive_url,
							],
						],
						'current' => get_the_title(),
					]);
					?>

					<div class="idc-profissional__layout">
						<figure class="idc-profissional__photo">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('large'); ?>
							<?php endif; ?>
						</figure>

						<div class="idc-profissional__content">
							<header class="idc-profissional__header">
								<p class="idc-eyebrow idc-eyebrow--accent"><?php esc_html_e('CORPO CLÍNICO', 'instituto-dr-chao'); ?></p>
								<h1 class="idc-profissional__name"><?php the_title(); ?></h1>
								<?php if ($spec !== '') : ?>
									<p class="idc-profissional__spec"><?php echo esc_html($spec); ?></p>
								<?php endif; ?>
								<?php if ($crm !== '') : ?>
									<p class="idc-profissional__crm"><?php echo esc_html($crm); ?></p>
								<?php endif; ?>
							</header>

							<div class="idc-profissional__bio">
								<?php the_content(); ?>
							</div>

							<div class="idc-profissional__actions">
								<?php
								get_template_part('template-parts/components/button', null, [
									'label'    => __('Agendar Consulta', 'instituto-dr-chao'),
									'variant'  => 'primary',
									'origem'   => $origem,
									'external' => true,
								]);
								?>
								<a class="idc-profissional__back" href="<?php echo esc_url($archive_url); ?>">
									<?php esc_html_e('Ver toda a equipe', 'instituto-dr-chao'); ?>
									<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow.svg')); ?>" alt="" width="12" height="12" decoding="async">
								</a>
							</div>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => $origem,
		'align'  => 'center',
	]);
	?>
</main>

<?php
get_footer();
