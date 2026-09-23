<?php
/**
 * Hero da Home — Figma 133:309.
 *
 * @package Instituto_Dr_Chao
 */

$eyebrow   = (string) idc_option('idc_hero_eyebrow', 'INSTITUTO DR. CHAO');
$since     = (string) idc_option('idc_hero_since', 'DESDE 1987');
$before    = (string) idc_option('idc_hero_title_before', 'Tratamos a');
$accent    = (string) idc_option('idc_hero_title_accent', 'origem da dor.');
$after     = (string) idc_option('idc_hero_title_after', 'Você sente a mudança.');
$lead      = (string) idc_option(
	'idc_hero_lead',
	"Existimos para que ninguém seja definido pela sua dor.\nCombinamos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação e bem-estar contínuo."
);
$cta_pri   = (string) idc_option('idc_hero_cta_primary', 'Agendar Consulta');
$cta_sec   = (string) idc_option('idc_hero_cta_secondary', 'Conheça os tratamentos');
$cta_sec_u = (string) idc_option('idc_hero_cta_secondary_url', home_url('/especialidades/'));
$image     = idc_option('idc_hero_image', null);

$image_url = idc_asset('assets/images/hero-photo.png');
$image_alt = __('Paciente em ambiente acolhedor do Instituto Dr. Chao', 'instituto-dr-chao');

if (is_array($image) && !empty($image['url'])) {
	$image_url = $image['url'];
	$image_alt = $image['alt'] ?: $image_alt;
} elseif (!is_readable(IDC_THEME_DIR . '/assets/images/hero-photo.png')) {
	$image_url = idc_asset('assets/images/hero-photo.jpg');
}
?>
<section class="idc-hero" aria-labelledby="idc-hero-title">
	<div class="idc-hero__bg" aria-hidden="true">
		<img src="<?php echo esc_url(idc_asset('assets/images/hero-bg.svg')); ?>" alt="" decoding="async">
	</div>

	<div class="idc-container">
		<div class="idc-hero__grid">
			<div class="idc-hero__content">
				<div class="idc-hero__meta">
					<span class="idc-eyebrow idc-eyebrow--accent"><?php echo esc_html($eyebrow); ?></span>
					<span class="idc-hero__divider" aria-hidden="true"></span>
					<span class="idc-eyebrow idc-eyebrow--muted"><?php echo esc_html($since); ?></span>
				</div>

				<h1 id="idc-hero-title" class="idc-hero__title">
					<?php echo esc_html($before); ?>
					<span class="idc-hero__title-accent"><?php echo esc_html($accent); ?></span>
					<?php echo esc_html($after); ?>
				</h1>

				<p class="idc-hero__lead"><?php echo nl2br(esc_html($lead)); ?></p>

				<div class="idc-hero__actions">
					<?php
					get_template_part('template-parts/components/button', null, [
						'label'    => $cta_pri,
						'variant'  => 'primary',
						'origem'   => 'home',
						'external' => true,
					]);
					get_template_part('template-parts/components/button', null, [
						'label'   => $cta_sec,
						'variant' => 'outline',
						'href'    => $cta_sec_u,
					]);
					?>
				</div>
			</div>

			<figure class="idc-hero__media">
				<img
					src="<?php echo esc_url($image_url); ?>"
					alt="<?php echo esc_attr($image_alt); ?>"
					width="590"
					height="590"
					decoding="async"
					fetchpriority="high"
				>
			</figure>
		</div>
	</div>
</section>
