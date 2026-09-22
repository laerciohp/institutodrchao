<?php
/**
 * ACF — CPT Tratamento.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

function idc_register_acf_tratamento_fields(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'    => 'group_idc_tratamento',
		'title'  => 'Dados do tratamento',
		'fields' => [
			[
				'key'   => 'field_idc_tx_eyebrow',
				'label' => 'Eyebrow',
				'name'  => 'idc_tx_eyebrow',
				'type'  => 'text',
				'default_value' => 'TRATAMENTO',
			],
			[
				'key'   => 'field_idc_tx_lead',
				'label' => 'Lead (acima do conteúdo)',
				'name'  => 'idc_tx_lead',
				'type'  => 'textarea',
				'rows'  => 3,
			],
			[
				'key'          => 'field_idc_tx_faqs',
				'label'        => 'Perguntas frequentes',
				'name'         => 'idc_tx_faqs',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Adicionar pergunta',
				'sub_fields'   => [
					['key' => 'field_idc_tx_faq_q', 'label' => 'Pergunta', 'name' => 'question', 'type' => 'text'],
					['key' => 'field_idc_tx_faq_a', 'label' => 'Resposta', 'name' => 'answer', 'type' => 'textarea', 'rows' => 3],
				],
			],
			[
				'key'           => 'field_idc_tx_cta_label',
				'label'         => 'Label CTA WhatsApp',
				'name'          => 'idc_tx_cta_label',
				'type'          => 'text',
				'default_value' => 'Agendar Consulta',
			],
			[
				'key'           => 'field_idc_tx_related_label',
				'label'         => 'Link relacionado (label)',
				'name'          => 'idc_tx_related_label',
				'type'          => 'text',
				'default_value' => 'Ver Ortopedia Regenerativa',
			],
			[
				'key'           => 'field_idc_tx_related_url',
				'label'         => 'Link relacionado (URL)',
				'name'          => 'idc_tx_related_url',
				'type'          => 'text',
				'default_value' => '/ortopedia-regenerativa/',
			],
		],
		'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'idc_tratamento']]],
	]);
}
add_action('acf/init', 'idc_register_acf_tratamento_fields');
