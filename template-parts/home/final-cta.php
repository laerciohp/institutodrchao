<?php
/**
 * CTA final — Figma 136:13127.
 *
 * @package Instituto_Dr_Chao
 */

$before = (string) idc_option('idc_cta_title_before', 'Recupere o ritmo');
$accent = (string) idc_option('idc_cta_title_accent', 'natural da sua vida.');
$lead   = (string) idc_option(
	'idc_cta_lead',
	'Agende sua avaliação e descubra um plano de tratamento criado especificamente para as suas necessidades, em um ambiente que respira tranquilidade.'
);
$pri = (string) idc_option('idc_cta_primary_label', 'Agendar Consulta');
$sec = (string) idc_option('idc_cta_secondary_label', 'Dúvidas Frequentes');
$sec_url = (string) idc_option('idc_cta_secondary_url', home_url('/contato/#faq'));
?>
<section class="idc-final-cta" aria-labelledby="idc-final-cta-title">
	<div class="idc-container">
		<div class="idc-final-cta__inner">
			<div class="idc-final-cta__mark" aria-hidden="true">
				<img src="<?php echo esc_url(idc_asset('assets/icons/cta-mark.svg')); ?>" alt="" width="102" height="102" decoding="async">
			</div>
			<h2 id="idc-final-cta-title" class="idc-final-cta__title">
				<?php echo esc_html($before); ?>
				<span class="idc-final-cta__title-accent"><?php echo esc_html(' ' . $accent); ?></span>
			</h2>
			<p class="idc-final-cta__lead"><?php echo esc_html($lead); ?></p>
			<div class="idc-final-cta__actions">
				<?php
				get_template_part('template-parts/components/button', null, [
					'label'    => $pri,
					'variant'  => 'primary',
					'class'    => 'idc-btn--cta-light',
					'origem'   => 'home-cta-final',
					'external' => true,
				]);
				get_template_part('template-parts/components/button', null, [
					'label'   => $sec,
					'variant' => 'navy',
					'class'   => 'idc-btn--cta-dark',
					'href'    => $sec_url,
				]);
				?>
			</div>
		</div>
	</div>
</section>
