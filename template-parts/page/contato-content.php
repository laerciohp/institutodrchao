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

$email   = (string) idc_option('idc_email', 'atendimento@institutodrchao.com.br');
$horario = (string) idc_option('idc_horario', 'Seg. à Sex. das 08h às 18h');

$form_title = function_exists('get_field') && get_field('idc_contato_form_title')
	? (string) get_field('idc_contato_form_title')
	: __('Envie uma mensagem', 'instituto-dr-chao');

$form_lead = function_exists('get_field') && get_field('idc_contato_form_lead')
	? (string) get_field('idc_contato_form_lead')
	: '';

$aside_title = function_exists('get_field') && get_field('idc_contato_aside_title')
	? (string) get_field('idc_contato_aside_title')
	: __('Fale diretamente', 'instituto-dr-chao');

$whatsapp_label = function_exists('get_field') && get_field('idc_contato_whatsapp_label')
	? (string) get_field('idc_contato_whatsapp_label')
	: __('Conversar no WhatsApp', 'instituto-dr-chao');

$label_nome     = (string) idc_page_field('idc_contato_label_nome', __('Nome completo', 'instituto-dr-chao'));
$label_email    = (string) idc_page_field('idc_contato_label_email', __('E-mail', 'instituto-dr-chao'));
$label_tel      = (string) idc_page_field('idc_contato_label_telefone', __('Telefone', 'instituto-dr-chao'));
$label_assunto  = (string) idc_page_field('idc_contato_label_assunto', __('Assunto de interesse', 'instituto-dr-chao'));
$label_mensagem = (string) idc_page_field('idc_contato_label_mensagem', __('Mensagem', 'instituto-dr-chao'));
$label_submit   = (string) idc_page_field('idc_contato_label_submit', __('Enviar mensagem', 'instituto-dr-chao'));
$label_endereco = (string) idc_page_field('idc_contato_label_endereco', __('Endereço', 'instituto-dr-chao'));
$label_horario  = (string) idc_page_field('idc_contato_label_horario', __('Horário de atendimento', 'instituto-dr-chao'));
$label_maps     = (string) idc_page_field('idc_contato_label_maps', __('Ver no Google Maps', 'instituto-dr-chao'));

$map_src = (string) idc_option(
	'idc_map_embed',
	'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed'
);

$assuntos_default = [
	['value' => '', 'label' => __('Selecione um assunto', 'instituto-dr-chao')],
	['value' => 'Agendar consulta', 'label' => __('Agendar consulta', 'instituto-dr-chao')],
	['value' => 'Ortopedia', 'label' => __('Ortopedia Regenerativa', 'instituto-dr-chao')],
	['value' => 'Fisioterapia', 'label' => __('Fisioterapia', 'instituto-dr-chao')],
	['value' => 'Medicina Integrativa', 'label' => __('Medicina Integrativa', 'instituto-dr-chao')],
	['value' => 'Dúvidas gerais', 'label' => __('Dúvidas gerais', 'instituto-dr-chao')],
];

$assuntos_acf = function_exists('get_field') ? get_field('idc_contato_assuntos') : null;
$assuntos     = $assuntos_default;
if (is_array($assuntos_acf) && $assuntos_acf !== []) {
	$assuntos = array_merge(
		[['value' => '', 'label' => __('Selecione um assunto', 'instituto-dr-chao')]],
		array_map(static function ($row): array {
			return [
				'value' => (string) ($row['value'] ?? $row['label'] ?? ''),
				'label' => (string) ($row['label'] ?? ''),
			];
		}, $assuntos_acf)
	);
}
?>
<section class="idc-contato" aria-label="<?php esc_attr_e('Informações de contato', 'instituto-dr-chao'); ?>">
	<span class="idc-contato__decor" aria-hidden="true"></span>
	<div class="idc-container">
		<div class="idc-contato__grid">
			<div class="idc-contato__form-wrap" id="idc-contato-form">
				<h2 class="idc-contato__form-title"><?php echo esc_html($form_title); ?></h2>
				<?php if ($form_lead !== '') : ?>
					<p class="idc-contato__form-lead"><?php echo esc_html($form_lead); ?></p>
				<?php endif; ?>
				<?php echo idc_forms_feedback_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<form id="idc-contato-form" class="idc-form idc-contato__form" method="post" action="<?php echo esc_url(get_permalink()); ?>">
					<input type="hidden" name="idc_form_action" value="contato">
					<input type="hidden" name="idc_return" value="<?php echo esc_url(get_permalink()); ?>">
					<?php wp_nonce_field('idc_contato', 'idc_contato_nonce'); ?>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-nome"><?php echo esc_html($label_nome); ?></label>
						<input class="idc-form__input" type="text" id="idc-contato-nome" name="nome" required autocomplete="name" placeholder="<?php esc_attr_e('Seu nome', 'instituto-dr-chao'); ?>">
					</div>

					<div class="idc-form__row idc-form__row--half">
						<div>
							<label class="idc-form__label" for="idc-contato-email"><?php echo esc_html($label_email); ?></label>
							<input class="idc-form__input" type="email" id="idc-contato-email" name="email" required autocomplete="email" placeholder="seu@email.com">
						</div>
						<div>
							<label class="idc-form__label" for="idc-contato-telefone"><?php echo esc_html($label_tel); ?></label>
							<input class="idc-form__input" type="tel" id="idc-contato-telefone" name="telefone" autocomplete="tel" placeholder="(00) 00000-0000">
						</div>
					</div>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-assunto"><?php echo esc_html($label_assunto); ?></label>
						<select class="idc-form__input idc-form__select" id="idc-contato-assunto" name="assunto">
							<?php foreach ($assuntos as $row) : ?>
								<option value="<?php echo esc_attr($row['value']); ?>"><?php echo esc_html($row['label']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="idc-form__row">
						<label class="idc-form__label" for="idc-contato-mensagem"><?php echo esc_html($label_mensagem); ?></label>
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

					<button type="submit" class="idc-btn idc-btn--primary idc-contato__submit"><?php echo esc_html($label_submit); ?></button>
				</form>
			</div>

			<aside class="idc-contato__aside">
				<h2 class="idc-contato__aside-title"><?php echo esc_html($aside_title !== '' ? $aside_title : __('Fale diretamente', 'instituto-dr-chao')); ?></h2>

				<div class="idc-contato__directs">
					<a class="idc-contato__direct idc-contato__direct--wa" href="<?php echo esc_url(idc_whatsapp_url_for_context('contato')); ?>" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url(idc_asset('assets/icons/icon-whatsapp.svg')); ?>" alt="" width="20" height="20">
						<span><?php echo esc_html($whatsapp_label !== '' ? $whatsapp_label : __('Conversar no WhatsApp', 'instituto-dr-chao')); ?></span>
					</a>

					<a class="idc-contato__direct" href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $telefone)); ?>">
						<img src="<?php echo esc_url(idc_asset('assets/icons/icon-phone.svg')); ?>" alt="" width="20" height="20">
						<span><?php echo esc_html($telefone); ?></span>
					</a>

					<a class="idc-contato__direct" href="mailto:<?php echo esc_attr($email); ?>">
						<img src="<?php echo esc_url(idc_asset('assets/icons/icon-email.svg')); ?>" alt="" width="20" height="20">
						<span><?php echo esc_html($email); ?></span>
					</a>
				</div>

				<div class="idc-contato__meta">
					<div class="idc-contato__meta-item">
						<span class="idc-contato__meta-icon" aria-hidden="true">
							<img src="<?php echo esc_url(idc_asset('assets/icons/icon-map-pin.svg')); ?>" alt="" width="20" height="20" decoding="async">
						</span>
						<div class="idc-contato__meta-text">
							<strong><?php echo esc_html($label_endereco); ?></strong>
							<p><?php echo nl2br(esc_html($endereco)); ?></p>
						</div>
					</div>
					<div class="idc-contato__meta-item">
						<span class="idc-contato__meta-icon" aria-hidden="true">
							<img src="<?php echo esc_url(idc_asset('assets/icons/icon-clock.svg')); ?>" alt="" width="20" height="20" decoding="async">
						</span>
						<div class="idc-contato__meta-text">
							<strong><?php echo esc_html($label_horario); ?></strong>
							<p><?php echo esc_html($horario); ?></p>
						</div>
					</div>
					<a class="idc-contato__meta-item idc-contato__meta-link" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo rawurlencode(str_replace(["\r", "\n"], ' ', $endereco)); ?>" target="_blank" rel="noopener noreferrer">
						<span class="idc-contato__meta-icon" aria-hidden="true">
							<img src="<?php echo esc_url(idc_asset('assets/icons/icon-map-pin.svg')); ?>" alt="" width="20" height="20" decoding="async">
						</span>
						<span class="idc-contato__meta-text">
							<strong><?php echo esc_html($label_maps); ?></strong>
						</span>
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
			<div class="idc-contato__map-card">
				<img class="idc-contato__map-card-pin" src="<?php echo esc_url(idc_asset('assets/icons/icon-map-pin.svg')); ?>" alt="" width="32" height="32" decoding="async">
				<strong class="idc-contato__map-card-title"><?php esc_html_e('Instituto Dr. Chao', 'instituto-dr-chao'); ?></strong>
				<p class="idc-contato__map-card-addr"><?php echo nl2br(esc_html($endereco)); ?></p>
			</div>
		</div>
	</div>
</section>
