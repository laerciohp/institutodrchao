<?php
/**
 * Conteúdos padrão das páginas institucionais (quando ACF ainda vazio).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cards do hub Especialidades.
 *
 * @return list<array{title:string,text:string,link_label:string,link_url:string,image:string,tone:string}>
 */
function idc_default_hub_cards(): array {
	return [
		[
			'title'      => 'Ortopedia Regenerativa',
			'text'       => 'Diagnóstico preciso e tratamentos cirúrgicos ou conservadores de alta complexidade, com foco na regeneração tecidual e recuperação funcional.',
			'link_label' => 'Saiba mais',
			'link_url'   => home_url('/ortopedia-regenerativa/'),
			'image'      => idc_asset('assets/images/pages/hub-ortopedia.png'),
			'tone'       => 'sand',
		],
		[
			'title'      => 'Fisioterapia Especializada em Dor',
			'text'       => 'Reabilitação física com protocolos personalizados para dor crônica e recuperação de movimento, em ambiente amplo e acolhedor.',
			'link_label' => 'Saiba mais',
			'link_url'   => home_url('/fisioterapia/'),
			'image'      => idc_asset('assets/images/pages/hub-fisio.png'),
			'tone'       => 'peach',
		],
		[
			'title'      => 'Medicina Integrativa e Regenerativa',
			'text'       => 'Acupuntura, controle da dor e suporte nutricional. Olhamos para o corpo como um sistema interconectado, potencializando a cura.',
			'link_label' => 'Saiba mais',
			'link_url'   => home_url('/medicina-integrativa/'),
			'image'      => idc_asset('assets/images/pages/hub-integrativa.png'),
			'tone'       => 'cream',
		],
	];
}

/**
 * Cards de diferenciais — O Instituto.
 *
 * @return list<array{title:string,text:string,icon:string}>
 */
function idc_default_instituto_cards(): array {
	return [
		[
			'title' => 'História e tradição',
			'text'  => 'Mais de três décadas dedicadas à ortopedia e reabilitação com excelência clínica.',
			'icon'  => idc_asset('assets/icons/inst-1.svg'),
		],
		[
			'title' => 'Abordagem integrada',
			'text'  => 'Ortopedia, fisioterapia e medicina integrativa trabalhando juntas no seu plano de cuidado.',
			'icon'  => idc_asset('assets/icons/inst-2.svg'),
		],
		[
			'title' => 'Ambiente acolhedor',
			'text'  => 'Espaços pensados para que você se sinta seguro e confortável em cada etapa do tratamento.',
			'icon'  => idc_asset('assets/icons/inst-3.svg'),
		],
		[
			'title' => 'Equipe titulada',
			'text'  => 'Especialistas formados nos maiores centros do país, dedicados à sua recuperação.',
			'icon'  => idc_asset('assets/icons/inst-4.svg'),
		],
	];
}

/**
 * FAQ padrão para páginas de especialidade.
 *
 * @return list<array{question:string,answer:string}>
 */
function idc_default_specialty_faq(): array {
	return [
		[
			'question' => 'Como funciona a primeira consulta?',
			'answer'   => 'Na avaliação inicial, nossa equipe analisa seu histórico, realiza exame físico detalhado e define um plano de tratamento personalizado.',
		],
		[
			'question' => 'Quais convênios são aceitos?',
			'answer'   => 'Atendemos diversos planos de saúde. Entre em contato para confirmar a cobertura do seu convênio.',
		],
		[
			'question' => 'Preciso de encaminhamento médico?',
			'answer'   => 'Depende do seu convênio e do tipo de consulta. Nossa equipe orienta você sobre a documentação necessária.',
		],
	];
}

/**
 * Seções de conteúdo padrão — especialidade.
 *
 * @param string $slug Slug da especialidade (ortopedia, fisioterapia, integrativa).
 * @return list<array{title:string,text:string}>
 */
function idc_default_specialty_sections(string $slug): array {
	$sections = [
		'ortopedia' => [
			[
				'title' => 'Diagnóstico de precisão',
				'text'  => 'Utilizamos exames de imagem avançados e avaliação clínica minuciosa para identificar a origem da sua dor e definir o melhor caminho terapêutico.',
			],
			[
				'title' => 'Tratamentos conservadores e cirúrgicos',
				'text'  => 'Desde infiltrações e terapias regenerativas até procedimentos cirúrgicos minimamente invasivos, sempre priorizando a recuperação funcional.',
			],
		],
		'fisioterapia' => [
			[
				'title' => 'Especialização em dor',
				'text'  => 'Protocolos baseados em evidências para dor crônica, pós-operatório e reabilitação funcional com acompanhamento contínuo.',
			],
			[
				'title' => 'Equipamentos de última geração',
				'text'  => 'Recursos como laserterapia, ultrassom terapêutico e exercícios terapêuticos personalizados em ambiente amplo e acolhedor.',
			],
		],
		'integrativa' => [
			[
				'title' => 'Visão sistêmica da saúde',
				'text'  => 'Integramos acupuntura, nutrição e terapias complementares ao tratamento ortopédico e fisioterapêutico.',
			],
			[
				'title' => 'Bem-estar contínuo',
				'text'  => 'Foco na qualidade de vida a longo prazo, com planos de cuidado que vão além do alívio imediato dos sintomas.',
			],
		],
	];

	return $sections[$slug] ?? $sections['ortopedia'];
}
