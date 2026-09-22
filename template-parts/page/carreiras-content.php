<?php
/**
 * Conteúdo — Carreiras / Trabalhe Conosco (Figma).
 *
 * @package Instituto_Dr_Chao
 */

$form_title = function_exists('get_field') && get_field('idc_carreiras_form_title')
	? (string) get_field('idc_carreiras_form_title')
	: __('Envie uma mensagem', 'instituto-dr-chao');

$areas = [
	''                 => __('Selecione', 'instituto-dr-chao'),
	'Ortopedia'        => __('Ortopedia', 'instituto-dr-chao'),
	'Fisioterapia'     => __('Fisioterapia', 'instituto-dr-chao'),
	'Medicina Integrativa' => __('Medicina Integrativa', 'instituto-dr-chao'),
	'Recepção / Administrativo' => __('Recepção / Administrativo', 'instituto-dr-chao'),
	'Outras áreas'     => __('Outras áreas', 'instituto-dr-chao'),
];
?>
<section class="idc-carreiras" aria-labelledby="idc-carreiras-form-title">
	<div class="idc-container">
		<div class="idc-carreiras__inner" id="idc-carreiras-form">
			<h2 id="idc-carreiras-form-title" class="idc-carreiras__title"><?php echo esc_html($form_title); ?></h2>
			<?php echo idc_forms_feedback_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<form class="idc-form idc-carreiras__form" method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="idc_form_action" value="carreiras">
				<?php wp_nonce_field('idc_carreiras', 'idc_carreiras_nonce'); ?>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-nome"><?php esc_html_e('Nome completo', 'instituto-dr-chao'); ?></label>
					<input class="idc-form__input" type="text" id="idc-carreiras-nome" name="nome" required autocomplete="name" placeholder="<?php esc_attr_e('Seu nome', 'instituto-dr-chao'); ?>">
				</div>

				<div class="idc-form__row idc-form__row--half">
					<div>
						<label class="idc-form__label" for="idc-carreiras-email"><?php esc_html_e('E-mail', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="email" id="idc-carreiras-email" name="email" required autocomplete="email" placeholder="seu@email.com">
					</div>
					<div>
						<label class="idc-form__label" for="idc-carreiras-telefone"><?php esc_html_e('Telefone / WhatsApp', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="tel" id="idc-carreiras-telefone" name="telefone" required autocomplete="tel" placeholder="(00) 00000-0000">
					</div>
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-area"><?php esc_html_e('Área de atuação ou vaga desejada', 'instituto-dr-chao'); ?></label>
					<select class="idc-form__input idc-form__select" id="idc-carreiras-area" name="area">
						<?php foreach ($areas as $value => $label) : ?>
							<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-cv"><?php esc_html_e('Anexe seu currículo (PDF ou DOC, máx 5MB)', 'instituto-dr-chao'); ?></label>
					<input class="idc-form__file" type="file" id="idc-carreiras-cv" name="curriculo" accept=".pdf,.doc,.docx,application/pdf">
				</div>

				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-mensagem"><?php esc_html_e('Mensagem', 'instituto-dr-chao'); ?></label>
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

				<button type="submit" class="idc-btn idc-btn--primary"><?php esc_html_e('Enviar candidatura', 'instituto-dr-chao'); ?></button>
			</form>
		</div>
	</div>
</section>
