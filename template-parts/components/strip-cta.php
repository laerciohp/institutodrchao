<?php
/**
 * Faixa CTA terracota com WhatsApp.
 *
 * Args:
 * - title, lead, label, origem, decor
 * - align: 'start'|'center' (default start)
 * - secondary_label / secondary_url: link texto opcional
 * - lead: string vazia oculta o parágrafo (não cai no default)
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title            = array_key_exists('title', $args) ? (string) $args['title'] : '';
$lead_provided    = array_key_exists('lead', $args);
$lead             = $lead_provided ? (string) $args['lead'] : '';
$label            = (string) ($args['label'] ?? '');
$origem           = (string) ($args['origem'] ?? '');
$decor            = !empty($args['decor']);
$align            = (string) ($args['align'] ?? 'start');
if ($align !== 'center') {
	$align = 'start';
}
$secondary_label  = array_key_exists('secondary_label', $args) ? (string) $args['secondary_label'] : '';
$secondary_url    = array_key_exists('secondary_url', $args) ? (string) $args['secondary_url'] : '';
$secondary_from_args = array_key_exists('secondary_label', $args) || array_key_exists('secondary_url', $args);

if ($title === '' && function_exists('get_field') && get_field('idc_strip_title')) {
	$title = (string) get_field('idc_strip_title');
}
if (!$lead_provided && function_exists('get_field') && get_field('idc_strip_lead')) {
	$lead = (string) get_field('idc_strip_lead');
}
if ($label === '' && function_exists('get_field') && get_field('idc_strip_label')) {
	$label = (string) get_field('idc_strip_label');
}
if (!$secondary_from_args && function_exists('get_field')) {
	$acf_sec_label = get_field('idc_strip_secondary_label');
	$acf_sec_url   = get_field('idc_strip_secondary_url');
	if ($acf_sec_label) {
		$secondary_label = (string) $acf_sec_label;
	}
	if ($acf_sec_url) {
		$secondary_url = (string) $acf_sec_url;
	}
}

$title = $title !== '' ? $title : __('Pronto para dar o primeiro passo?', 'instituto-dr-chao');
if (!$lead_provided) {
	$lead = $lead !== '' ? $lead : __('Agende sua avaliação e fale com nossa equipe pelo WhatsApp.', 'instituto-dr-chao');
}
$label = $label !== '' ? $label : __('Fale via WhatsApp', 'instituto-dr-chao');

// Resolve relative / hash URLs.
if ($secondary_url !== '' && !preg_match('#^(https?:)?//|^mailto:|^tel:|^/#|^#', $secondary_url)) {
	$secondary_url = home_url('/' . ltrim($secondary_url, '/'));
} elseif ($secondary_url !== '' && str_starts_with($secondary_url, '/') && !str_starts_with($secondary_url, '//')) {
	$secondary_url = home_url($secondary_url);
}
$strip_class = 'idc-strip-cta'
	. ($align === 'center' ? ' idc-strip-cta--center' : '');
?>
<section class="<?php echo esc_attr($strip_class); ?>" aria-labelledby="idc-strip-cta-title">
	<?php if ($decor) : ?>
		<span class="idc-strip-cta__decor" aria-hidden="true"></span>
	<?php endif; ?>
	<div class="idc-container">
		<div class="idc-strip-cta__inner">
			<div class="idc-strip-cta__content">
				<h2 id="idc-strip-cta-title" class="idc-strip-cta__title"><?php echo esc_html($title); ?></h2>
				<?php if ($lead !== '') : ?>
					<p class="idc-strip-cta__lead"><?php echo esc_html($lead); ?></p>
				<?php endif; ?>
			</div>
			<div class="idc-strip-cta__actions">
				<a
					class="idc-btn idc-btn--primary idc-btn--strip-light idc-strip-cta__btn"
					href="<?php echo esc_url(idc_whatsapp_url_for_context($origem)); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<img
						class="idc-strip-cta__btn-icon"
						src="<?php echo esc_url(idc_asset('assets/icons/icon-whatsapp.svg')); ?>"
						alt=""
						width="20"
						height="20"
						decoding="async"
					>
					<span><?php echo esc_html($label); ?></span>
				</a>
				<?php if ($secondary_label !== '' && $secondary_url !== '') : ?>
					<a class="idc-strip-cta__secondary" href="<?php echo esc_url($secondary_url); ?>">
						<?php echo esc_html($secondary_label); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
