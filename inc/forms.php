<?php
/**
 * Formulários nativos — Contato e Carreiras (CPT + wp_mail/SMTP).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Destinatário padrão dos formulários.
 */
function idc_forms_recipient(string $fallback = 'atendimento@institutodrchao.com.br'): string {
	$email = (string) idc_option('idc_email', $fallback);
	return is_email($email) ? $email : $fallback;
}

/**
 * Processa POST dos formulários.
 */
function idc_forms_handle_submit(): void {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	$action = isset($_POST['idc_form_action']) ? sanitize_key((string) $_POST['idc_form_action']) : '';
	if ($action === '') {
		return;
	}

	if ($action === 'contato') {
		idc_forms_handle_contato();
		return;
	}

	if ($action === 'carreiras') {
		idc_forms_handle_carreiras();
	}
}
add_action('template_redirect', 'idc_forms_handle_submit', 5);

/**
 * Flash message na query string (volta para a página do formulário).
 */
function idc_forms_redirect(string $status, string $anchor = ''): void {
	$return = '';
	if (!empty($_POST['idc_return'])) {
		$return = esc_url_raw(wp_unslash((string) $_POST['idc_return']));
	}
	if ($return === '') {
		$ref = wp_get_referer();
		$return = is_string($ref) ? $ref : home_url('/');
	}

	$url = remove_query_arg(['idc_form', 'idc_form_msg'], $return);
	$url = add_query_arg(
		[
			'idc_form'     => $status,
			'idc_form_msg' => 1,
		],
		$url
	);
	if ($anchor !== '') {
		$url .= '#' . ltrim($anchor, '#');
	}
	wp_safe_redirect($url);
	exit;
}

/**
 * Status final: salvou no CPT = sucesso para o usuário.
 * Se o e-mail falhar, ainda retorna sucesso (ok_mail) com a mesma mensagem amigável.
 */
function idc_forms_status_after_send(bool $saved, bool $sent): string {
	if (!$saved) {
		return 'error';
	}
	return $sent ? 'ok' : 'ok_mail';
}

/**
 * Contato.
 */
function idc_forms_handle_contato(): void {
	if (!isset($_POST['idc_contato_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['idc_contato_nonce'])), 'idc_contato')) {
		idc_forms_redirect('error', 'idc-contato-form');
	}

	$nome     = isset($_POST['nome']) ? sanitize_text_field(wp_unslash($_POST['nome'])) : '';
	$email    = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
	$telefone = isset($_POST['telefone']) ? sanitize_text_field(wp_unslash($_POST['telefone'])) : '';
	$assunto  = isset($_POST['assunto']) ? sanitize_text_field(wp_unslash($_POST['assunto'])) : '';
	$mensagem = isset($_POST['mensagem']) ? sanitize_textarea_field(wp_unslash($_POST['mensagem'])) : '';
	$lgpd     = !empty($_POST['lgpd']);

	if ($nome === '' || !is_email($email) || $mensagem === '' || !$lgpd) {
		idc_forms_redirect('invalid', 'idc-contato-form');
	}

	$saved = false;
	if (function_exists('idc_save_contato_submission')) {
		$saved = idc_save_contato_submission([
			'nome'     => $nome,
			'email'    => $email,
			'telefone' => $telefone,
			'assunto'  => $assunto,
			'mensagem' => $mensagem,
			'lgpd'     => $lgpd,
		]) > 0;
	}

	$to      = idc_forms_recipient();
	$subject = sprintf('[Contato IDC] %s', $assunto !== '' ? $assunto : 'Nova mensagem');
	$body    = "Nome: {$nome}\nE-mail: {$email}\nTelefone: {$telefone}\nAssunto: {$assunto}\n\nMensagem:\n{$mensagem}\n";
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
	];

	$sent = wp_mail($to, $subject, $body, $headers);
	idc_forms_redirect(idc_forms_status_after_send($saved, $sent), 'idc-contato-form');
}

/**
 * Carreiras (com anexo).
 */
function idc_forms_handle_carreiras(): void {
	if (!isset($_POST['idc_carreiras_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['idc_carreiras_nonce'])), 'idc_carreiras')) {
		idc_forms_redirect('error', 'idc-carreiras-form');
	}

	$nome     = isset($_POST['nome']) ? sanitize_text_field(wp_unslash($_POST['nome'])) : '';
	$email    = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
	$telefone = isset($_POST['telefone']) ? sanitize_text_field(wp_unslash($_POST['telefone'])) : '';
	$area     = isset($_POST['area']) ? sanitize_text_field(wp_unslash($_POST['area'])) : '';
	$mensagem = isset($_POST['mensagem']) ? sanitize_textarea_field(wp_unslash($_POST['mensagem'])) : '';
	$lgpd     = !empty($_POST['lgpd']);

	if ($nome === '' || !is_email($email) || $telefone === '' || !$lgpd) {
		idc_forms_redirect('invalid', 'idc-carreiras-form');
	}

	$attachments = [];
	$attach_id   = 0;

	if (!empty($_FILES['curriculo']['name'])) {
		$file_size = (int) ($_FILES['curriculo']['size'] ?? 0);
		if ($file_size > 5 * MB_IN_BYTES) {
			idc_forms_redirect('upload', 'idc-carreiras-form');
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$overrides = [
			'test_form' => false,
			'mimes'     => [
				'pdf'  => 'application/pdf',
				'doc'  => 'application/msword',
				'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			],
		];
		$upload = wp_handle_upload($_FILES['curriculo'], $overrides);
		if (!empty($upload['error'])) {
			idc_forms_redirect('upload', 'idc-carreiras-form');
		}
		if (!empty($upload['file']) && !empty($upload['url'])) {
			$attachments[] = $upload['file'];
			$filetype      = wp_check_filetype(basename($upload['file']), null);
			$attach_id     = wp_insert_attachment(
				[
					'post_mime_type' => $filetype['type'] ?? 'application/pdf',
					'post_title'     => sanitize_file_name(basename($upload['file'])),
					'post_content'   => '',
					'post_status'    => 'inherit',
				],
				$upload['file']
			);
			if (!is_wp_error($attach_id) && $attach_id) {
				wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $upload['file']));
			} else {
				$attach_id = 0;
			}
		}
	}

	$saved = false;
	if (function_exists('idc_save_curriculo_submission')) {
		$saved = idc_save_curriculo_submission([
			'nome'          => $nome,
			'email'         => $email,
			'telefone'      => $telefone,
			'area'          => $area,
			'mensagem'      => $mensagem,
			'lgpd'          => $lgpd,
			'attachment_id' => $attach_id,
		]) > 0;
	}

	$to = function_exists('get_field') && get_field('idc_carreiras_email')
		? (string) get_field('idc_carreiras_email')
		: (string) idc_option('idc_email_rh', 'rh@institutodrchao.com.br');

	if (!is_email($to)) {
		$to = idc_forms_recipient();
	}

	$subject = sprintf('[Carreiras IDC] %s%s', $nome, $area !== '' ? ' — ' . $area : '');
	$body    = "Nome: {$nome}\nE-mail: {$email}\nTelefone: {$telefone}\nÁrea/vaga: {$area}\n\nMensagem:\n{$mensagem}\n";
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
	];

	$sent = wp_mail($to, $subject, $body, $headers, $attachments);
	idc_forms_redirect(idc_forms_status_after_send($saved, $sent), 'idc-carreiras-form');
}

/**
 * Mensagem de feedback após submit.
 */
function idc_forms_feedback_html(): string {
	if (empty($_GET['idc_form_msg'])) {
		return '';
	}

	$status = isset($_GET['idc_form']) ? sanitize_key((string) $_GET['idc_form']) : '';
	$map    = [
		'ok'      => ['class' => 'is-success', 'text' => __('Mensagem enviada com sucesso. Em breve entraremos em contato.', 'instituto-dr-chao')],
		'ok_mail' => ['class' => 'is-success', 'text' => __('Mensagem enviada com sucesso. Em breve entraremos em contato.', 'instituto-dr-chao')],
		'invalid' => ['class' => 'is-error', 'text' => __('Preencha os campos obrigatórios e aceite a política de privacidade.', 'instituto-dr-chao')],
		'upload'  => ['class' => 'is-error', 'text' => __('Não foi possível anexar o currículo. Use PDF ou DOC até 5 MB.', 'instituto-dr-chao')],
		'error'   => ['class' => 'is-error', 'text' => __('Não foi possível enviar agora. Tente novamente ou fale pelo WhatsApp.', 'instituto-dr-chao')],
	];

	if (!isset($map[$status])) {
		return '';
	}

	return sprintf(
		'<div class="idc-form__feedback %s" role="status">%s</div>',
		esc_attr($map[$status]['class']),
		esc_html($map[$status]['text'])
	);
}
