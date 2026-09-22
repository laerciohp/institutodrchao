<?php
/**
 * Conteúdo — Contato (Figma: formulário + Fale diretamente + mapa).
 *
 * @package Instituto_Dr_Chao
 */

$telefone = function_exists('get_field') && get_field('idc_contato_telefone')
	? (string) get_field('idc_contato_telefone')
	: (string) idc_option('idc_telefone', '(11) 2218-8080');

$endereco = function_exists('get_field') && get_field('idc_contato_endereco')
	? (string) get_field('idc_contato_endereco')
	: (string) idc_option('idc_endereco', "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003");

$email = (string) idc_option('idc_email', 'atendimento@institutodrchao.com.br');
$horario = (string) idc_option('idc_horario', 'Seg. à Sex. das 08h às 18h');

$form_title = function_exists('get_field') && get_field('idc_contato_form_title')
	? (string) get_field('idc_contato_form_title')
	: __('Envie uma mensagem', 'instituto-dr-chao');

$map_src = (string) idc_option(
	'idc_map_embed',
	'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed'
);

$assuntos = [
	''                      => __('Selecione um assunto', 'instituto-dr-chao'),
	'Agendar consulta'      => __('Agendar consulta', 'instituto-dr-chao'),
	'Ortopedia'             => __('Ortopedia Regenerativa', 'instituto-dr-chao'),
	'Fisioterapia'          => __('Fisioterapia', 'instituto-dr-chao'),
	'Medicina Integrativa'  => __('Medicina Integrativa', 'instituto-dr-chao'),
	'Dúvidas gerais'        => __('Dúvidas gerais', 'instituto-dr-chao'),
];
?>
<section class="idc-contato" aria-label="<?php esc_attr_e('Informações de contato', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-contato__grid">
			<div class="idc-contato__form-wrap" id="idc-contato-form">
				<h2 class="idc-contato__form-title"><?php echo esc_html($form_title); ?></h2>
				<?php echo idc_forms_feedback_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<form class="idc-form idc-contato__form" method="post" action="">
					<input type="hidden" name="idc_form_action" value="contato">
					<?php wp_nonce_field('idc_contato', 'idc_contato_nonce'); ?>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-nome"><?php esc_html_e('Nome completo', 'instituto-dr-chao'); ?></label>
						<input class="idc-form__input" type="text" id="idc-contato-nome" name="nome" required autocomplete="name" placeholder="<?php esc_attr_e('Seu nome', 'instituto-dr-chao'); ?>">
					</div>

					<div class="idc-form__row idc-form__row--half">
						<div>
							<label class="idc-form__label" for="idc-contato-email"><?php esc_html_e('E-mail', 'instituto-dr-chao'); ?></label>
							<input class="idc-form__input" type="email" id="idc-contato-email" name="email" required autocomplete="email" placeholder="seu@email.com">
						</div>
						<div>
							<label class="idc-form__label" for="idc-contato-telefone"><?php esc_html_e('Telefone', 'instituto-dr-chao'); ?></label>
							<input class="idc-form__input" type="tel" id="idc-contato-telefone" name="telefone" autocomplete="tel" placeholder="(00) 00000-0000">
						</div>
					</div>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-assunto"><?php esc_html_e('Assunto de interesse', 'instituto-dr-chao'); ?></label>
						<select class="idc-form__input idc-form__select" id="idc-contato-assunto" name="assunto">
							<?php foreach ($assuntos as $value => $label) : ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-mensagem"><?php esc_html_e('Mensagem', 'instituto-dr-chao'); ?></label>
						<textarea class="idc-form__textarea" id="idc-contato-mensagem" name="mensagem" rows="5" required placeholder="<?php esc_attr_e('Como podemos ajudar?', 'instituto-dr-chao'); ?>"></textarea>
					</div>

					<div class="idc-form__row idc-form__row--check">
						<label class="idc-form__check">
							<input type="checkbox" name="lgpd" value="1" required>
							<span>
								<?php
								printf(
									/* translators: %s: privacy policy URL */
									esc_html__('Li e concordo com a %s.', 'instituto-dr-chao'),
									'<a href="' . esc_url(home_url('/privacidade/')) . '">' . esc_html__('política de privacidade', 'instituto-dr-chao') . '</a>'
								);
								?>
							</span>
						</label>
					</div>

					<button type="submit" class="idc-btn idc-btn--primary"><?php esc_html_e('Enviar mensagem', 'instituto-dr-chao'); ?></button>
				</form>
			</div>

			<aside class="idc-contato__aside">
				<h2 class="idc-contato__aside-title"><?php esc_html_e('Fale diretamente', 'instituto-dr-chao'); ?></h2>

				<a class="idc-contato__direct idc-contato__direct--wa" href="<?php echo esc_url(idc_whatsapp_url_for_context('contato')); ?>" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo esc_url(idc_asset('assets/icons/icon-whatsapp.svg')); ?>" alt="" width="20" height="20">
					<span><?php esc_html_e('Conversar no WhatsApp', 'instituto-dr-chao'); ?></span>
				</a>

				<a class="idc-contato__direct" href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $telefone)); ?>">
					<span><?php echo esc_html($telefone); ?></span>
				</a>

				<a class="idc-contato__direct" href="mailto:<?php echo esc_attr($email); ?>">
					<span><?php echo esc_html($email); ?></span>
				</a>

				<div class="idc-contato__meta">
					<div class="idc-contato__meta-item">
						<strong><?php esc_html_e('Endereço', 'instituto-dr-chao'); ?></strong>
						<p><?php echo nl2br(esc_html($endereco)); ?></p>
					</div>
					<div class="idc-contato__meta-item">
						<strong><?php esc_html_e('Horário de atendimento', 'instituto-dr-chao'); ?></strong>
						<p><?php echo esc_html($horario); ?></p>
					</div>
					<a class="idc-btn idc-btn--secondary" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo rawurlencode(str_replace(["\r", "\n"], ' ', $endereco)); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e('Ver no Google Maps', 'instituto-dr-chao'); ?>
					</a>
				</div>
			</aside>
		</div>

		<div class="idc-contato__map" aria-label="<?php esc_attr_e('Mapa de localização', 'instituto-dr-chao'); ?>">
			<iframe
				class="idc-contato__map-frame"
				src="<?php echo esc_url($map_src); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				title="<?php esc_attr_e('Mapa — Instituto Dr. Chao', 'instituto-dr-chao'); ?>"
			></iframe>
		</div>
	</div>
</section>
