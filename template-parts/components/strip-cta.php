<?php
/**
 * Faixa CTA terracota com WhatsApp.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title  = (string) ($args['title'] ?? '');
$lead   = (string) ($args['lead'] ?? '');
$label  = (string) ($args['label'] ?? '');
$origem = (string) ($args['origem'] ?? '');

if ($title === '' && function_exists('get_field') && get_field('idc_strip_title')) {
	$title = (string) get_field('idc_strip_title');
}
if ($lead === '' && function_exists('get_field') && get_field('idc_strip_lead')) {
	$lead = (string) get_field('idc_strip_lead');
}
if ($label === '' && function_exists('get_field') && get_field('idc_strip_label')) {
	$label = (string) get_field('idc_strip_label');
}

$title = $title !== '' ? $title : __('Pronto para dar o primeiro passo?', 'instituto-dr-chao');
$lead  = $lead !== '' ? $lead : __('Agende sua avaliação e fale com nossa equipe pelo WhatsApp.', 'instituto-dr-chao');
$label = $label !== '' ? $label : __('Agendar Consulta', 'instituto-dr-chao');
?>
<section class="idc-strip-cta" aria-labelledby="idc-strip-cta-title">
	<div class="idc-container">
		<div class="idc-strip-cta__inner">
			<div class="idc-strip-cta__content">
				<h2 id="idc-strip-cta-title" class="idc-strip-cta__title"><?php echo esc_html($title); ?></h2>
				<?php if ($lead !== '') : ?>
					<p class="idc-strip-cta__lead"><?php echo esc_html($lead); ?></p>
				<?php endif; ?>
			</div>
			<?php
			get_template_part('template-parts/components/button', null, [
				'label'    => $label,
				'variant'  => 'primary',
				'class'    => 'idc-btn--strip-light',
				'href'     => idc_whatsapp_url_for_context($origem),
				'external' => true,
			]);
			?>
		</div>
	</div>
</section>
