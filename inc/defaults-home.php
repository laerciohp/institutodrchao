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
		['type' => 'rating', 'value' => '4.9', 'label' => '742 avaliações no Google'],
		['type' => 'number', 'value' => '38+', 'label' => 'ANOS DE HISTÓRIA'],
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
			'icon'       => idc_asset('assets/icons/icon-ortopedia-figma.svg'),
			'image'      => idc_theme_image('assets/images/pillar-ortopedia', 'png'),
		],
		[
			'layout'     => 'narrow',
			'title'      => "Fisioterapia &\nReabilitação",
			'text'       => 'Recuperação de movimento funcional com protocolos personalizados e equipamentos de última geração em um ambiente amplo e acolhedor.',
			'link_label' => 'Explorar',
			'link_url'   => home_url('/fisioterapia/'),
			'tone'       => 'mint',
			'icon'       => idc_asset('assets/icons/icon-fisioterapia-figma.svg'),
		],
		[
			'layout'     => 'narrow',
			'title'      => 'Medicina Integrativa',
			'text'       => 'Acupuntura, controle da dor crônica e suporte nutricional. Olhamos para o seu corpo como um sistema interconectado, potencializando a cura.',
			'link_label' => 'Explorar',
			'link_url'   => home_url('/medicina-integrativa/'),
			'tone'       => 'lavender',
			'icon'       => idc_asset('assets/icons/icon-integrativa-figma.svg'),
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
			'title' => 'Cuidado que começa pela escuta',
			'text'  => 'Ouvimos antes de prescrever, a origem da sua dor só aparece quando você é ouvido.',
			'icon'  => idc_asset('assets/icons/diff-1-figma.svg'),
		],
		[
			'title' => 'Tratamento individualizado',
			'text'  => 'Cada plano de tratamento é único, assim como você.',
			'icon'  => idc_asset('assets/icons/diff-2-figma.svg'),
		],
		[
			'title' => 'Técnica com acolhimento',
			'text'  => 'Da consulta à reabilitação no mesmo local.',
			'icon'  => idc_asset('assets/icons/diff-3-figma.svg'),
		],
		[
			'title' => 'Compromisso com qualidade de vida',
			'text'  => 'Nosso foco é o seu bem-estar integral.',
			'icon'  => idc_asset('assets/icons/diff-4-figma.svg'),
		],
	];
}

/**
 * Depoimentos padrão — reviews reais de https://www.institutodrchao.com.br/
 * no formato Figma (rating, quote, name, role).
 *
 * @return list<array{rating:int,quote:string,name:string,role:string}>
 */
function idc_default_testimonials(): array {
	return [
		[
			'rating' => 5,
			'quote'  => 'Ótimo espaço. Desde a pessoa que te recebe até o médico que finaliza o atendimento, todos muito atenciosos! Recomendo.',
			'name'   => 'André Fagundes Cabral',
			'role'   => 'Paciente',
		],
		[
			'rating' => 5,
			'quote'  => 'Ambiente acolhedor, excelentes funcionários e médicos. Super recomendo, faço aplicações no meu joelho com o Dr. Luiz. Mudou a qualidade da minha vida. Obrigada à família Instituto Dr. Chao.',
			'name'   => 'Cida Rocha',
			'role'   => 'Paciente Ortopedia',
		],
		[
			'rating' => 5,
			'quote'  => 'Sempre fui muito bem atendido na clínica por toda a equipe. Passo há anos com o Dr. Daniel, especialista em joelhos, e com o tratamento meu quadro hoje é estável e sem dor. Faço infiltrações semestrais com o Dr. e com o enfermeiro Cris, que sempre teve o tato para amenizar o procedimento. Recomendo!',
			'name'   => 'Triguinho Zaga',
			'role'   => 'Paciente Ortopedia',
		],
		[
			'rating' => 5,
			'quote'  => 'Dr. Chao, um médico brilhante que dedicou a vida à cura. Seus sábios ensinamentos se perpetuam através da sua filha, a fantástica Dra. Débora, que trouxe uma nova visão da medicina moderna agregada à sabedoria do pai. Excelente atendimento de todos os funcionários, inclusive o querido Cris!',
			'name'   => 'Renata Marinelli',
			'role'   => 'Paciente',
		],
		[
			'rating' => 5,
			'quote'  => 'Acabei de passar com a Dra. Érica: médica maravilhosa, atenciosa e cuidadosa. Examina com cuidado, explica tudo com detalhes — estou encantada, nota 1000. A recepção também muito cordial e simpática. Estão de parabéns.',
			'name'   => 'Nara Moura',
			'role'   => 'Paciente',
		],
		[
			'rating' => 5,
			'quote'  => 'Sempre que preciso, sou muito bem atendida. Me sinto em casa — uma família que cuida muito bem de todos.',
			'name'   => 'Elisa Marcia',
			'role'   => 'Paciente',
		],
	];
}

/**
 * Troca paths legados de assets do tema pelas URLs canônicas atuais.
 */
function idc_normalize_theme_image_url(string $url): string {
	// Hub/modelo anatômico não é o fill do card Home (exame do joelho anexado).
	$canonical = idc_theme_image('assets/images/pillar-ortopedia', 'png');
	$map       = [
		'pages/hub-ortopedia.jpg',
		'pages/hub-ortopedia.png',
	];
	foreach ($map as $needle) {
		if ($canonical !== '' && str_contains($url, $needle)) {
			return $canonical;
		}
	}
	return $url;
}

/**
 * Resolve URL de imagem ACF ou string.
 *
 * @param mixed  $image
 * @param string $fallback
 */
function idc_image_url($image, string $fallback = ''): string {
	if (is_numeric($image) && (int) $image > 0) {
		$url = wp_get_attachment_image_url((int) $image, 'medium');
		return $url ? idc_normalize_theme_image_url($url) : $fallback;
	}
	if (is_array($image) && !empty($image['ID']) && empty($image['url'])) {
		$url = wp_get_attachment_image_url((int) $image['ID'], 'medium');
		return $url ? idc_normalize_theme_image_url($url) : $fallback;
	}
	if (is_array($image) && !empty($image['url'])) {
		return idc_normalize_theme_image_url((string) $image['url']);
	}
	if (is_string($image) && $image !== '') {
		return idc_normalize_theme_image_url($image);
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
