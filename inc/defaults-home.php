<?php
/**
 * Conteúdos padrão da Home (quando ACF ainda vazio).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * @return list<array{type:string,value:string,label:string}>
 */
function idc_default_trust_items(): array {
	return [
		['type' => 'number', 'value' => '38+', 'label' => 'ANOS DE HISTÓRIA'],
		['type' => 'stars', 'value' => '5.0', 'label' => '5.0 GOOGLE REVIEWS'],
		['type' => 'number', 'value' => '7', 'label' => 'ESPECIALISTAS TITULADOS'],
		['type' => 'number', 'value' => '3', 'label' => 'FRENTES INTEGRADAS'],
	];
}

/**
 * @return list<array<string,mixed>>
 */
function idc_default_pillars_cards(): array {
	return [
		[
			'layout'     => 'primary',
			'title'      => 'Ortopedia Especializada',
			'text'       => 'Diagnóstico preciso e tratamentos cirúrgicos ou conservadores de alta complexidade. Nossa base clínica sólida garante a segurança que você precisa no momento mais crítico.',
			'link_label' => 'Saber mais',
			'link_url'   => home_url('/ortopedia-regenerativa/'),
			'tone'       => 'sand',
			'icon'       => idc_asset('assets/icons/icon-ortopedia.svg'),
			'image'      => idc_asset('assets/images/pillar-ortopedia.png'),
		],
		[
			'layout'     => 'narrow',
			'title'      => "Fisioterapia &\nReabilitação",
			'text'       => 'Recuperação de movimento funcional com protocolos personalizados e equipamentos de última geração em um ambiente amplo e acolhedor.',
			'link_label' => 'Explorar',
			'link_url'   => home_url('/fisioterapia/'),
			'tone'       => 'mint',
			'icon'       => idc_asset('assets/icons/icon-fisio.svg'),
		],
		[
			'layout'     => 'narrow',
			'title'      => 'Medicina Integrativa',
			'text'       => 'Acupuntura, controle da dor crônica e suporte nutricional. Olhamos para o seu corpo como um sistema interconectado, potencializando a cura.',
			'link_label' => 'Explorar',
			'link_url'   => home_url('/medicina-integrativa/'),
			'tone'       => 'lavender',
			'icon'       => idc_asset('assets/icons/icon-integrativa.svg'),
		],
		[
			'layout'       => 'cta',
			'title'        => 'Pronto para dar o primeiro passo?',
			'text'         => 'Nossa equipe está preparada para te ajudar.',
			'link_label'   => 'Falar agora',
			'tone'         => 'cream',
			'use_whatsapp' => true,
		],
	];
}

/**
 * @return list<array{title:string,text:string,icon:string}>
 */
function idc_default_why_items(): array {
	return [
		[
			'title' => 'Corpo Clínico Renomado',
			'text'  => 'Especialistas formados nos maiores centros do país.',
			'icon'  => idc_asset('assets/icons/diff-1.svg'),
		],
		[
			'title' => 'Abordagem Individual',
			'text'  => 'Cada plano de tratamento é único, assim como você.',
			'icon'  => idc_asset('assets/icons/diff-2.svg'),
		],
		[
			'title' => 'Estrutura Completa',
			'text'  => 'Da consulta à reabilitação no mesmo local.',
			'icon'  => idc_asset('assets/icons/diff-3.svg'),
		],
		[
			'title' => 'Atendimento Humanizado',
			'text'  => 'Nosso foco é o seu bem-estar integral.',
			'icon'  => idc_asset('assets/icons/diff-4.svg'),
		],
	];
}

/**
 * @return list<array{rating:int,quote:string,name:string,role:string}>
 */
function idc_default_testimonials(): array {
	return [
		[
			'rating' => 5,
			'quote'  => 'Atendimento excepcional desde a recepção até o consultório. A equipe de fisioterapia me ajudou a recuperar os movimentos que achei que tinha perdido para sempre.',
			'name'   => 'Maria Silva',
			'role'   => 'Paciente Ortopedia',
		],
		[
			'rating' => 5,
			'quote'  => 'O Instituto mudou minha visão sobre tratamento de dor crônica. A acupuntura junto com a fisioterapia fizeram milagres na minha coluna.',
			'name'   => 'João Mendes',
			'role'   => 'Paciente Medicina Integrativa',
		],
		[
			'rating' => 5,
			'quote'  => 'A cirurgia foi um sucesso e todo o acompanhamento pós-operatório me deu muita segurança. Profissionais extremamente competentes.',
			'name'   => 'Ana Costa',
			'role'   => 'Paciente Cirurgia',
		],
	];
}

/**
 * Resolve URL de imagem ACF ou string.
 *
 * @param mixed  $image
 * @param string $fallback
 */
function idc_image_url($image, string $fallback = ''): string {
	if (is_array($image) && !empty($image['url'])) {
		return (string) $image['url'];
	}
	if (is_string($image) && $image !== '') {
		return $image;
	}
	return $fallback;
}

/**
 * @param mixed $image
 */
function idc_image_alt($image, string $fallback = ''): string {
	if (is_array($image) && !empty($image['alt'])) {
		return (string) $image['alt'];
	}
	return $fallback;
}
