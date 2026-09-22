<?php
/**
 * Campos ACF das páginas institucionais (por page template).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Campos comuns de hero de página.
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_page_hero_fields(string $prefix = 'idc_page'): array {
	return [
		[
			'key'   => "field_{$prefix}_eyebrow",
			'label' => 'Eyebrow',
			'name'  => "{$prefix}_eyebrow",
			'type'  => 'text',
		],
		[
			'key'   => "field_{$prefix}_title_before",
			'label' => 'Título (antes)',
			'name'  => "{$prefix}_title_before",
			'type'  => 'text',
		],
		[
			'key'   => "field_{$prefix}_title_accent",
			'label' => 'Título (destaque serif)',
			'name'  => "{$prefix}_title_accent",
			'type'  => 'text',
		],
		[
			'key'   => "field_{$prefix}_title_after",
			'label' => 'Título (depois)',
			'name'  => "{$prefix}_title_after",
			'type'  => 'text',
		],
		[
			'key'   => "field_{$prefix}_lead",
			'label' => 'Lead',
			'name'  => "{$prefix}_lead",
			'type'  => 'textarea',
			'rows'  => 3,
		],
	];
}

/**
 * Campos de strip CTA (opcionais por página).
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_strip_cta_fields(): array {
	return [
		[
			'key'   => 'field_idc_strip_tab',
			'label' => 'Faixa CTA',
			'type'  => 'tab',
		],
		[
			'key'   => 'field_idc_strip_title',
			'label' => 'Título da faixa',
			'name'  => 'idc_strip_title',
			'type'  => 'text',
		],
		[
			'key'   => 'field_idc_strip_lead',
			'label' => 'Texto da faixa',
			'name'  => 'idc_strip_lead',
			'type'  => 'textarea',
			'rows'  => 2,
		],
		[
			'key'   => 'field_idc_strip_label',
			'label' => 'Label do botão',
			'name'  => 'idc_strip_label',
			'type'  => 'text',
		],
	];
}

/**
 * FAQ repeater.
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_faq_fields(): array {
	return [
		[
			'key'   => 'field_idc_faq_tab',
			'label' => 'FAQ',
			'type'  => 'tab',
		],
		[
			'key'   => 'field_idc_faq_title',
			'label' => 'Título da seção',
			'name'  => 'idc_faq_title',
			'type'  => 'text',
			'default_value' => 'Dúvidas frequentes',
		],
		[
			'key'          => 'field_idc_faq_items',
			'label'        => 'Perguntas',
			'name'         => 'idc_faq_items',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Adicionar pergunta',
			'sub_fields'   => [
				[
					'key'   => 'field_idc_faq_question',
					'label' => 'Pergunta',
					'name'  => 'question',
					'type'  => 'text',
				],
				[
					'key'   => 'field_idc_faq_answer',
					'label' => 'Resposta',
					'name'  => 'answer',
					'type'  => 'textarea',
					'rows'  => 3,
				],
			],
		],
	];
}

/**
 * Registra field groups por template de página.
 */
function idc_register_acf_page_fields(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	// —— Hub Especialidades ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_especialidades',
		'title'  => 'Página — Especialidades Hub',
		'fields' => array_merge(
			[
				['key' => 'field_idc_hub_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields(),
			[
				['key' => 'field_idc_hub_tab_cards', 'label' => 'Cards', 'type' => 'tab'],
				[
					'key'          => 'field_idc_hub_cards',
					'label'        => 'Cards do hub',
					'name'         => 'idc_hub_cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar card',
					'sub_fields'   => [
						['key' => 'field_idc_hub_card_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_hub_card_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
						['key' => 'field_idc_hub_card_link_label', 'label' => 'Label do link', 'name' => 'link_label', 'type' => 'text'],
						['key' => 'field_idc_hub_card_link_url', 'label' => 'URL', 'name' => 'link_url', 'type' => 'url'],
						[
							'key'     => 'field_idc_hub_card_tone',
							'label'   => 'Tom',
							'name'    => 'tone',
							'type'    => 'select',
							'choices' => ['sand' => 'Areia', 'peach' => 'Pêssego', 'cream' => 'Cream'],
						],
						['key' => 'field_idc_hub_card_image', 'label' => 'Imagem', 'name' => 'image', 'type' => 'image', 'return_format' => 'array'],
					],
				],
			],
			idc_acf_strip_cta_fields()
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-especialidades.php']]],
	]);

	// —— O Instituto ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_instituto',
		'title'  => 'Página — O Instituto',
		'fields' => array_merge(
			[
				['key' => 'field_idc_inst_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields(),
			[
				['key' => 'field_idc_inst_tab_essencia', 'label' => 'Essência', 'type' => 'tab'],
				['key' => 'field_idc_instituto_essencia_eyebrow', 'label' => 'Eyebrow essência', 'name' => 'idc_instituto_essencia_eyebrow', 'type' => 'text'],
				['key' => 'field_idc_instituto_essencia_title', 'label' => 'Título essência', 'name' => 'idc_instituto_essencia_title', 'type' => 'text'],
				['key' => 'field_idc_instituto_essencia_lead', 'label' => 'Lead essência', 'name' => 'idc_instituto_essencia_lead', 'type' => 'textarea', 'rows' => 2],
				[
					'key'          => 'field_idc_instituto_cards',
					'label'        => 'Diferenciais (2×2)',
					'name'         => 'idc_instituto_cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar card',
					'sub_fields'   => [
						['key' => 'field_idc_inst_card_icon', 'label' => 'Ícone', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array'],
						['key' => 'field_idc_inst_card_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_inst_card_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 2],
					],
				],
				['key' => 'field_idc_instituto_image', 'label' => 'Imagem interior', 'name' => 'idc_instituto_image', 'type' => 'image', 'return_format' => 'array'],
			],
			idc_acf_strip_cta_fields()
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-o-instituto.php']]],
	]);

	// —— Especialidades (ortopedia, fisio, integrativa) ——
	$specialty_templates = [
		'page-ortopedia.php'            => 'Ortopedia Regenerativa',
		'page-fisioterapia.php'         => 'Fisioterapia',
		'page-medicina-integrativa.php' => 'Medicina Integrativa',
	];

	foreach ($specialty_templates as $template => $label) {
		acf_add_local_field_group([
			'key'    => 'group_idc_page_' . sanitize_key(str_replace(['page-', '.php'], '', $template)),
			'title'  => 'Página — ' . $label,
			'fields' => array_merge(
				[
					['key' => 'field_idc_spec_tab_hero_' . md5($template), 'label' => 'Hero', 'type' => 'tab'],
				],
				idc_acf_page_hero_fields(),
				[
					['key' => 'field_idc_spec_tab_sections_' . md5($template), 'label' => 'Conteúdo', 'type' => 'tab'],
					[
						'key'          => 'field_idc_specialty_sections_' . md5($template),
						'label'        => 'Seções',
						'name'         => 'idc_specialty_sections',
						'type'         => 'repeater',
						'layout'       => 'block',
						'button_label' => 'Adicionar seção',
						'sub_fields'   => [
							['key' => 'field_idc_spec_sec_title_' . md5($template), 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
							['key' => 'field_idc_spec_sec_text_' . md5($template), 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 4],
						],
					],
				],
				idc_acf_faq_fields(),
				idc_acf_strip_cta_fields()
			),
			'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => $template]]],
		]);
	}

	// —— Contato ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_contato',
		'title'  => 'Página — Contato',
		'fields' => array_merge(
			[
				['key' => 'field_idc_cont_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields(),
			[
				['key' => 'field_idc_cont_tab_info', 'label' => 'Informações', 'type' => 'tab'],
				['key' => 'field_idc_contato_telefone', 'label' => 'Telefone (override)', 'name' => 'idc_contato_telefone', 'type' => 'text', 'instructions' => 'Deixe vazio para usar IDC Opções.'],
				['key' => 'field_idc_contato_whatsapp_label', 'label' => 'WhatsApp exibição (override)', 'name' => 'idc_contato_whatsapp_label', 'type' => 'text'],
				['key' => 'field_idc_contato_endereco', 'label' => 'Endereço (override)', 'name' => 'idc_contato_endereco', 'type' => 'textarea', 'rows' => 3],
				['key' => 'field_idc_contato_form_title', 'label' => 'Título do formulário', 'name' => 'idc_contato_form_title', 'type' => 'text'],
				['key' => 'field_idc_contato_form_lead', 'label' => 'Lead do formulário', 'name' => 'idc_contato_form_lead', 'type' => 'textarea', 'rows' => 2],
			]
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-contato.php']]],
	]);

	// —— Carreiras ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_carreiras',
		'title'  => 'Página — Carreiras',
		'fields' => array_merge(
			[
				['key' => 'field_idc_carr_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields(),
			[
				['key' => 'field_idc_carr_tab_form', 'label' => 'Formulário', 'type' => 'tab'],
				['key' => 'field_idc_carreiras_form_title', 'label' => 'Título do formulário', 'name' => 'idc_carreiras_form_title', 'type' => 'text'],
				['key' => 'field_idc_carreiras_form_lead', 'label' => 'Lead do formulário', 'name' => 'idc_carreiras_form_lead', 'type' => 'textarea', 'rows' => 2],
				['key' => 'field_idc_carreiras_email', 'label' => 'E-mail RH (mailto)', 'name' => 'idc_carreiras_email', 'type' => 'email', 'default_value' => 'rh@institutodrchao.com.br'],
			]
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-carreiras.php']]],
	]);
}
add_action('acf/init', 'idc_register_acf_page_fields');
