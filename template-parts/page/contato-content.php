<?php
/**
 * Conteúdo — Contato (cards + formulário stub + mapa).
 *
 * @package Instituto_Dr_Chao
 */

$telefone = function_exists('get_field') && get_field('idc_contato_telefone')
	? (string) get_field('idc_contato_telefone')
	: (string) idc_option('idc_telefone', '(11) 99999-9999');

$whatsapp = function_exists('get_field') && get_field('idc_contato_whatsapp_label')
	? (string) get_field('idc_contato_whatsapp_label')
	: (string) idc_option('idc_whatsapp', '5511999999999');

$endereco = function_exists('get_field') && get_field('idc_contato_endereco')
	? (string) get_field('idc_contato_endereco')
	: (string) idc_option('idc_endereco', 'Av. Exemplo, 123 — São Paulo, SP');

$form_title = function_exists('get_field') && get_field('idc_contato_form_title')
	? (string) get_field('idc_contato_form_title')
	: __('Envie uma mensagem', 'instituto-dr-chao');

$form_lead = function_exists('get_field') && get_field('idc_contato_form_lead')
	? (string) get_field('idc_contato_form_lead')
	: __('Preencha o formulário e nossa equipe entrará em contato em breve.', 'instituto-dr-chao');

$whatsapp_display = preg_replace('/^55/', '', preg_replace('/\D+/', '', $whatsapp));
if (strlen($whatsapp_display) === 11) {
	$whatsapp_display = sprintf('(%s) %s-%s', substr($whatsapp_display, 0, 2), substr($whatsapp_display, 2, 5), substr($whatsapp_display, 7));
}
?>
<section class="idc-contato" aria-label="<?php esc_attr_e('Informações de contato', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-contato__cards">
			<article class="idc-contato__card">
				<h2 class="idc-contato__card-title"><?php esc_html_e('Telefone', 'instituto-dr-chao'); ?></h2>
				<p class="idc-contato__card-value">
					<a href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $telefone)); ?>"><?php echo esc_html($telefone); ?></a>
				</p>
			</article>
			<article class="idc-contato__card">
				<h2 class="idc-contato__card-title"><?php esc_html_e('WhatsApp', 'instituto-dr-chao'); ?></h2>
				<p class="idc-contato__card-value">
					<a href="<?php echo esc_url(idc_whatsapp_url_for_context('contato')); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html($whatsapp_display ?: $whatsapp); ?>
					</a>
				</p>
			</article>
			<article class="idc-contato__card">
				<h2 class="idc-contato__card-title"><?php esc_html_e('Endereço', 'instituto-dr-chao'); ?></h2>
				<p class="idc-contato__card-value"><?php echo nl2br(esc_html($endereco)); ?></p>
			</article>
		</div>

		<div class="idc-contato__grid">
			<div class="idc-contato__form-wrap">
				<h2 class="idc-contato__form-title"><?php echo esc_html($form_title); ?></h2>
				<p class="idc-contato__form-lead"><?php echo esc_html($form_lead); ?></p>
				<form class="idc-form idc-contato__form" action="#" method="post" data-idc-form-stub>
					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-nome"><?php esc_html_e('Nome', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="text" id="idc-contato-nome" name="nome" required autocomplete="name">
					</div>
					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-email"><?php esc_html_e('E-mail', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="email" id="idc-contato-email" name="email" required autocomplete="email">
					</div>
					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-mensagem"><?php esc_html_e('Mensagem', 'instituto-dr-chao'); ?></label>
						<textarea class="idc-form__textarea" id="idc-contato-mensagem" name="mensagem" rows="5" required></textarea>
					</div>
					<p class="idc-form__note"><?php esc_html_e('Formulário ilustrativo — integração com plugin de contato pendente.', 'instituto-dr-chao'); ?></p>
					<button type="submit" class="idc-btn idc-btn--primary"><?php esc_html_e('Enviar mensagem', 'instituto-dr-chao'); ?></button>
				</form>
			</div>

			<div class="idc-contato__map" aria-label="<?php esc_attr_e('Mapa de localização', 'instituto-dr-chao'); ?>">
				<div class="idc-contato__map-placeholder">
					<p><?php esc_html_e('Mapa em breve', 'instituto-dr-chao'); ?></p>
					<p class="idc-contato__map-address"><?php echo esc_html(str_replace("\n", ', ', $endereco)); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
