<?php
/**
 * Conteúdo — Carreiras / Trabalhe Conosco (Figma 242:16918 / 242:17166).
 * Formulário full-width + mapa com card central.
 *
 * @package Instituto_Dr_Chao
 */

$form_title = function_exists('get_field') && get_field('idc_carreiras_form_title')
	? (string) get_field('idc_carreiras_form_title')
	: __('Envie uma mensagem', 'instituto-dr-chao');

/* Figma 242:16918 / 242:17166 — sem lead sob o H2 (ignora override ACF legado). */
$form_lead = '';

$areas_default = [
	['label' => __('Selecione', 'instituto-dr-chao'), 'value' => ''],
	['label' => __('Ortopedia', 'instituto-dr-chao'), 'value' => 'Ortopedia'],
	['label' => __('Fisioterapia', 'instituto-dr-chao'), 'value' => 'Fisioterapia'],
	['label' => __('Medicina Integrativa', 'instituto-dr-chao'), 'value' => 'Medicina Integrativa'],
	['label' => __('Recepção / Administrativo', 'instituto-dr-chao'), 'value' => 'Recepção / Administrativo'],
	['label' => __('Outras áreas', 'instituto-dr-chao'), 'value' => 'Outras áreas'],
];

$areas_acf = function_exists('get_field') ? get_field('idc_carreiras_areas') : null;
$areas     = $areas_default;
if (is_array($areas_acf) && $areas_acf !== []) {
	$areas = array_merge(
		[['label' => __('Selecione', 'instituto-dr-chao'), 'value' => '']],
		array_map(static function ($row): array {
			$label = (string) ($row['label'] ?? '');
			return ['label' => $label, 'value' => $label];
		}, $areas_acf)
	);
}

$label_nome     = (string) idc_page_field('idc_carreiras_label_nome', __('Nome completo', 'instituto-dr-chao'));
$label_email    = (string) idc_page_field('idc_carreiras_label_email', __('E-mail', 'instituto-dr-chao'));
$label_tel      = (string) idc_page_field('idc_carreiras_label_telefone', __('Telefone / WhatsApp', 'instituto-dr-chao'));
$label_area     = (string) idc_page_field('idc_carreiras_label_area', __('Área de atuação ou vaga desejada', 'instituto-dr-chao'));
$label_cv       = (string) idc_page_field('idc_carreiras_label_cv', __('Anexe seu currículo (PDF ou DOC, máx 5MB):', 'instituto-dr-chao'));
$label_mensagem = (string) idc_page_field('idc_carreiras_label_mensagem', __('Mensagem', 'instituto-dr-chao'));
$label_submit   = (string) idc_page_field('idc_carreiras_label_submit', __('Enviar candidatura', 'instituto-dr-chao'));
$label_choose   = __('Escolher arquivo', 'instituto-dr-chao');

$endereco = function_exists('get_field') && get_field('idc_contato_endereco')
	? (string) get_field('idc_contato_endereco')
	: (string) idc_option('idc_endereco', "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003");

$map_src = (string) idc_option(
	'idc_map_embed',
	'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed'
);
?>
<section class="idc-carreiras" aria-labelledby="idc-carreiras-form-title">
	<span class="idc-carreiras__decor" aria-hidden="true"></span>
	<div class="idc-container">
		<div class="idc-carreiras__inner" id="idc-carreiras-form">
			<h2 id="idc-carreiras-form-title" class="idc-carreiras__title"><?php echo esc_html($form_title); ?></h2>
			<?php if ($form_lead !== '') : ?>
				<p class="idc-carreiras__lead"><?php echo esc_html($form_lead); ?></p>
			<?php endif; ?>
			<?php echo idc_forms_feedback_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<form id="idc-carreiras-form" class="idc-form idc-carreiras__form" method="post" action="<?php echo esc_url(get_permalink()); ?>" enctype="multipart/form-data">
				<input type="hidden" name="idc_form_action" value="carreiras">
				<input type="hidden" name="idc_return" value="<?php echo esc_url(get_permalink()); ?>">
				<?php wp_nonce_field('idc_carreiras', 'idc_carreiras_nonce'); ?>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-nome"><?php echo esc_html($label_nome); ?></label>
					<input class="idc-form__input" type="text" id="idc-carreiras-nome" name="nome" required autocomplete="name" placeholder="<?php esc_attr_e('Seu nome', 'instituto-dr-chao'); ?>">
				</div>

				<div class="idc-form__row idc-form__row--half">
					<div>
						<label class="idc-form__label" for="idc-carreiras-email"><?php echo esc_html($label_email); ?></label>
						<input class="idc-form__input" type="email" id="idc-carreiras-email" name="email" required autocomplete="email" placeholder="seu@email.com">
					</div>
					<div>
						<label class="idc-form__label" for="idc-carreiras-telefone"><?php echo esc_html($label_tel); ?></label>
						<input class="idc-form__input" type="tel" id="idc-carreiras-telefone" name="telefone" required autocomplete="tel" placeholder="(00) 00000-0000">
					</div>
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-area"><?php echo esc_html($label_area); ?></label>
					<select class="idc-form__input idc-form__select" id="idc-carreiras-area" name="area">
						<?php foreach ($areas as $row) : ?>
							<option value="<?php echo esc_attr($row['value']); ?>"><?php echo esc_html($row['label']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-cv"><?php echo esc_html($label_cv); ?></label>
					<div class="idc-form__file-wrap">
						<input class="idc-form__file" type="file" id="idc-carreiras-cv" name="curriculo" accept=".pdf,.doc,.docx,application/pdf" data-idc-file>
						<label class="idc-form__file-btn" for="idc-carreiras-cv"><?php echo esc_html($label_choose); ?></label>
						<span class="idc-form__file-name" data-idc-file-name aria-live="polite"></span>
					</div>
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-mensagem"><?php echo esc_html($label_mensagem); ?></label>
					<textarea class="idc-form__textarea" id="idc-carreiras-mensagem" name="mensagem" rows="5" placeholder="<?php esc_attr_e('Fale um pouco sobre você e por que deseja fazer parte do nosso time...', 'instituto-dr-chao'); ?>"></textarea>
				</div>

				<div class="idc-form__row idc-form__row--check">
					<label class="idc-form__check">
						<input type="checkbox" name="lgpd" value="1" required>
						<span>
							<?php
							printf(
								esc_html__('Li e concordo com a %s.', 'instituto-dr-chao'),
								'<a href="' . esc_url(home_url('/privacidade/')) . '">' . esc_html__('política de privacidade', 'instituto-dr-chao') . '</a>'
							);
							?>
						</span>
					</label>
				</div>

				<button type="submit" class="idc-btn idc-btn--primary idc-carreiras__submit"><?php echo esc_html($label_submit); ?></button>
			</form>
		</div>

		<div class="idc-carreiras__map idc-contato__map" aria-label="<?php esc_attr_e('Mapa de localização', 'instituto-dr-chao'); ?>">
			<iframe
				class="idc-contato__map-frame"
				src="<?php echo esc_url($map_src); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				title="<?php esc_attr_e('Mapa — Instituto Dr. Chao', 'instituto-dr-chao'); ?>"
			></iframe>
			<div class="idc-contato__map-card">
				<img class="idc-contato__map-card-pin" src="<?php echo esc_url(idc_asset('assets/icons/icon-map-pin.svg')); ?>" alt="" width="32" height="32" decoding="async">
				<strong class="idc-contato__map-card-title"><?php esc_html_e('Instituto Dr. Chao', 'instituto-dr-chao'); ?></strong>
				<p class="idc-contato__map-card-addr"><?php echo nl2br(esc_html($endereco)); ?></p>
			</div>
		</div>
	</div>
</section>
