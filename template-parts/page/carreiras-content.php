<?php
/**
 * Conteúdo — Carreiras (formulário frontend).
 *
 * @package Instituto_Dr_Chao
 */

$form_title = function_exists('get_field') && get_field('idc_carreiras_form_title')
	? (string) get_field('idc_carreiras_form_title')
	: __('Candidate-se', 'instituto-dr-chao');

$form_lead = function_exists('get_field') && get_field('idc_carreiras_form_lead')
	? (string) get_field('idc_carreiras_form_lead')
	: __('Envie seu currículo e conte-nos por que deseja fazer parte do Instituto Dr. Chao.', 'instituto-dr-chao');

$mailto = function_exists('get_field') && get_field('idc_carreiras_email')
	? (string) get_field('idc_carreiras_email')
	: 'rh@institutodrchao.com.br';
?>
<section class="idc-carreiras" aria-labelledby="idc-carreiras-form-title">
	<div class="idc-container">
		<div class="idc-carreiras__inner">
			<h2 id="idc-carreiras-form-title" class="idc-carreiras__title"><?php echo esc_html($form_title); ?></h2>
			<p class="idc-carreiras__lead"><?php echo esc_html($form_lead); ?></p>

			<form
				class="idc-form idc-carreiras__form"
				action="<?php echo esc_url('mailto:' . $mailto); ?>"
				method="post"
				enctype="text/plain"
			>
				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-nome"><?php esc_html_e('Nome completo', 'instituto-dr-chao'); ?></label>
					<input class="idc-form__input" type="text" id="idc-carreiras-nome" name="nome" required autocomplete="name">
				</div>
				<div class="idc-form__row idc-form__row--half">
					<div>
						<label class="idc-form__label" for="idc-carreiras-email"><?php esc_html_e('E-mail', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="email" id="idc-carreiras-email" name="email" required autocomplete="email">
					</div>
					<div>
						<label class="idc-form__label" for="idc-carreiras-telefone"><?php esc_html_e('Telefone', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="tel" id="idc-carreiras-telefone" name="telefone" required autocomplete="tel">
					</div>
				</div>
				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-mensagem"><?php esc_html_e('Mensagem', 'instituto-dr-chao'); ?></label>
					<textarea class="idc-form__textarea" id="idc-carreiras-mensagem" name="mensagem" rows="5"></textarea>
				</div>
				<div class="idc-form__row">
					<label class="idc-form__label" for="idc-carreiras-cv"><?php esc_html_e('Currículo (PDF)', 'instituto-dr-chao'); ?></label>
					<input class="idc-form__file" type="file" id="idc-carreiras-cv" name="curriculo" accept=".pdf,.doc,.docx">
					<p class="idc-form__hint"><?php esc_html_e('Upload via mailto não é suportado — anexe manualmente ao e-mail ou aguarde integração backend.', 'instituto-dr-chao'); ?></p>
				</div>
				<p class="idc-form__note">
					<?php
					printf(
						/* translators: %s: e-mail RH */
						esc_html__('Ao enviar, seu cliente de e-mail abrirá uma mensagem para %s.', 'instituto-dr-chao'),
						esc_html($mailto)
					);
					?>
				</p>
				<button type="submit" class="idc-btn idc-btn--primary"><?php esc_html_e('Enviar candidatura', 'instituto-dr-chao'); ?></button>
			</form>
		</div>
	</div>
</section>
