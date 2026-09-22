<?php
/**
 * FAQ accordion (stub interativo).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title = (string) ($args['title'] ?? '');
if ($title === '' && function_exists('get_field') && get_field('idc_faq_title')) {
	$title = (string) get_field('idc_faq_title');
}
if ($title === '') {
	$title = __('Dúvidas frequentes', 'instituto-dr-chao');
}
$items = $args['items'] ?? null;

if (!is_array($items) || $items === []) {
	$acf_items = function_exists('get_field') ? get_field('idc_faq_items') : null;
	$items     = is_array($acf_items) && $acf_items !== [] ? $acf_items : idc_default_specialty_faq();
}

if ($items === []) {
	return;
}
?>
<section class="idc-specialty__faq" id="faq" aria-labelledby="idc-faq-title">
	<div class="idc-container">
		<h2 id="idc-faq-title" class="idc-specialty__faq-title"><?php echo esc_html($title); ?></h2>
		<div class="idc-accordion" data-idc-accordion>
			<?php foreach ($items as $index => $item) :
				$question = (string) ($item['question'] ?? '');
				$answer   = (string) ($item['answer'] ?? '');
				if ($question === '') {
					continue;
				}
				$id = 'idc-faq-' . ($index + 1);
				?>
				<div class="idc-accordion__item">
					<h3 class="idc-accordion__heading">
						<button
							type="button"
							class="idc-accordion__trigger"
							id="<?php echo esc_attr($id); ?>-trigger"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr($id); ?>-panel"
							data-idc-accordion-trigger
						>
							<span><?php echo esc_html($question); ?></span>
							<img class="idc-accordion__chevron" src="<?php echo esc_url(idc_asset('assets/icons/chevron-down.svg')); ?>" alt="" width="8" height="5" decoding="async" aria-hidden="true">
						</button>
					</h3>
					<div
						class="idc-accordion__panel"
						id="<?php echo esc_attr($id); ?>-panel"
						role="region"
						aria-labelledby="<?php echo esc_attr($id); ?>-trigger"
						hidden
					>
						<p><?php echo esc_html($answer); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
