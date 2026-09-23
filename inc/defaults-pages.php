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
			'image'      => idc_theme_image('assets/images/pages/hub-ortopedia'),
			'tone'       => 'sand',
		],
		[
			'title'      => 'Fisioterapia Especializada em Dor',
			'text'       => 'Reabilitação física com protocolos personalizados para dor crônica e recuperação de movimento, em ambiente amplo e acolhedor.',
			'link_label' => 'Saiba mais',
			'link_url'   => home_url('/fisioterapia/'),
			'image'      => idc_theme_image('assets/images/pages/hub-fisio'),
			'tone'       => 'peach',
		],
		[
			'title'      => 'Medicina Integrativa e Regenerativa',
			'text'       => 'Acupuntura, controle da dor e suporte nutricional. Olhamos para o corpo como um sistema interconectado, potencializando a cura.',
			'link_label' => 'Saiba mais',
			'link_url'   => home_url('/medicina-integrativa/'),
			'image'      => idc_theme_image('assets/images/pages/hub-integrativa'),
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
			'title' => 'Experiência desde 1987',
			'text'  => 'Tradição consolidada em diagnósticos precisos e tratamentos eficazes.',
			'icon'  => idc_asset('assets/icons/inst-1.svg'),
		],
		[
			'title' => 'Cuidado humano e escuta ativa',
			'text'  => 'Tempo e atenção dedicados a compreender suas necessidades únicas.',
			'icon'  => idc_asset('assets/icons/inst-2.svg'),
		],
		[
			'title' => 'Plano individualizado',
			'text'  => 'Estratégias sob medida, respeitando seu corpo e seus objetivos.',
			'icon'  => idc_asset('assets/icons/inst-3.svg'),
		],
		[
			'title' => 'Integração entre especialidades',
			'text'  => 'Visão sistêmica com equipe multidisciplinar trabalhando em sintonia.',
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
			'answer'   => 'Na avaliação inicial, analisamos seu histórico, realizamos exame físico detalhado e, quando necessário, solicitamos exames de imagem. A partir disso, montamos um plano personalizado — priorizando opções conservadoras e regenerativas.',
		],
		[
			'question' => 'Toda condição ortopédica precisa de cirurgia?',
			'answer'   => 'Não. Muitas condições podem ser tratadas sem cirurgia — essa é a nossa prioridade. Oferecemos tratamentos minimamente invasivos para a dor e procedimentos regenerativos que visam melhorar a performance das células de músculos, tendões e articulações. A cirurgia entra quando há indicação clara.',
		],
		[
			'question' => 'Como a fisioterapia se integra ao tratamento?',
			'answer'   => 'Nosso programa de reabilitação ortopédica é garantido pela colaboração estreita entre fisioterapeutas e médicos ortopedistas. O bem-estar do paciente fica no centro do plano, da avaliação à manutenção do movimento.',
		],
		[
			'question' => 'Quais convênios são aceitos?',
			'answer'   => 'Atendemos diversos planos de saúde. Entre em contato para confirmar a cobertura do seu convênio e a documentação necessária.',
		],
		[
			'question' => 'Preciso de encaminhamento médico?',
			'answer'   => 'Depende do seu convênio e do tipo de consulta. Nossa equipe orienta você sobre a documentação necessária antes do agendamento.',
		],
	];
}

/**
 * Cards de tratamentos com FAQ — Ortopedia Regenerativa (Figma).
 *
 * @return list<array{title:string,intro:string,faqs:list<array{question:string,answer:string}>}>
 */
function idc_default_ortopedia_treatments(): array {
	return [
		[
			'title' => 'Viscossuplementação',
			'icon'  => idc_asset('assets/icons/tx-viscos.svg'),
			'intro' => '',
			'faqs'  => [
				[
					'question' => 'O que é',
					'answer'   => 'A viscossuplementação é a infiltração de ácido hialurônico na articulação para melhorar a lubrificação, reduzir a dor e favorecer a mobilidade — especialmente em artrose e desgaste cartilaginoso.',
				],
				[
					'question' => 'Quando considerar',
					'answer'   => 'Quando há dor articular, rigidez ou limitação ao caminhar, e o tratamento clínico ainda pode adiar ou evitar uma cirurgia. A indicação é definida após avaliação ortopédica.',
				],
				[
					'question' => 'Como funciona',
					'answer'   => 'O ácido hialurônico é aplicado diretamente na articulação em ambiente ambulatorial. O procedimento é rápido e visa restaurar a viscosidade do líquido sinovial.',
				],
				[
					'question' => 'É dolorido?',
					'answer'   => 'O desconforto costuma ser leve e passageiro. Usamos técnica cuidadosa e, quando necessário, anestesia local para tornar a aplicação mais confortável.',
				],
				[
					'question' => 'Quantas aplicações são necessárias?',
					'answer'   => 'Depende da articulação, do grau de desgaste e do protocolo escolhido. Em muitos casos, o plano prevê uma a três aplicações, com acompanhamento médico.',
				],
			],
		],
		[
			'title' => 'Ozonioterapia',
			'icon'  => idc_asset('assets/icons/tx-ozonio.svg'),
			'intro' => '',
			'faqs'  => [
				[
					'question' => 'O que é',
					'answer'   => 'A ozonioterapia utiliza uma mistura de oxigênio e ozônio medicinal em doses controladas para modular inflamação, melhorar oxigenação local e apoiar a regeneração tecidual.',
				],
				[
					'question' => 'Quando considerar',
					'answer'   => 'Em dor musculoesquelética, processos inflamatórios e reabilitação quando o objetivo é um caminho conservador e regenerativo, sempre sob indicação médica.',
				],
				[
					'question' => 'Como funciona',
					'answer'   => 'O gás é aplicado conforme o protocolo (local ou sistêmico) definido pelo especialista. As sessões são curtas e fazem parte de um plano individualizado.',
				],
				[
					'question' => 'É seguro?',
					'answer'   => 'Sim, em ambiente clínico e com dosagens adequadas. Contraindicações existem e são avaliadas na consulta antes de iniciar o protocolo.',
				],
				[
					'question' => 'Em quanto tempo faz efeito?',
					'answer'   => 'A resposta varia. Muitos pacientes relatam alívio progressivo ao longo das sessões; o tempo depende da condição tratada e da adesão ao plano.',
				],
			],
		],
	];
}

/**
 * Fases da recuperação — Fisioterapia.
 *
 * @return list<array{number:string,label:string,title:string,text:string,icon:string}>
 */
function idc_default_fisioterapia_phases(): array {
	return [
		[
			'number' => '1',
			'label'  => 'FASE 1',
			'title'  => 'Alívio da Dor',
			'text'   => 'Intervenção inicial focada na redução imediata do desconforto e controle do processo inflamatório.',
			'icon'   => idc_asset('assets/icons/icon-fisio.svg'),
		],
		[
			'number' => '2',
			'label'  => 'FASE 2',
			'title'  => 'Estabilização',
			'text'   => 'Fortalecimento estrutural primário para garantir suporte articular e evitar recidivas imediatas.',
			'icon'   => idc_asset('assets/icons/diff-2.svg'),
		],
		[
			'number' => '3',
			'label'  => 'FASE 3',
			'title'  => 'Reabilitação Funcional',
			'text'   => 'Retorno progressivo às atividades diárias e esportivas com protocolos de carga controlada.',
			'icon'   => idc_asset('assets/icons/diff-3.svg'),
		],
		[
			'number' => '4',
			'label'  => 'FASE 4',
			'title'  => 'Manutenção e Prevenção',
			'text'   => 'Estratégias de longo prazo e educação corporal para manter os ganhos e promover saúde duradoura.',
			'icon'   => idc_asset('assets/icons/diff-4.svg'),
		],
	];
}

/**
 * Fases para seed ACF (sem URL de ícone — editor pode subir SVG/PNG).
 *
 * @return list<array{number:string,label:string,title:string,text:string}>
 */
function idc_default_fisioterapia_phases_for_acf(): array {
	$rows = [];
	foreach (idc_default_fisioterapia_phases() as $phase) {
		$rows[] = [
			'number' => $phase['number'],
			'label'  => $phase['label'],
			'title'  => $phase['title'],
			'text'   => $phase['text'],
		];
	}
	return $rows;
}

/**
 * Grade bento — Medicina Integrativa (Figma 3×3).
 *
 * @return list<array{title:string,text:string,icon:string}>
 */
function idc_default_integrativa_grid(): array {
	$icon = idc_asset('assets/icons/icon-integrativa.svg');
	return [
		[
			'title' => 'Neuromodulação',
			'text'  => 'Equilíbrio do sistema nervoso central para tratamento de dores crônicas.',
			'icon'  => $icon,
		],
		[
			'title' => 'Neuromodulação do Nervo Vago',
			'text'  => 'Estímulo não invasivo para regulação autonômica e controle inflamatório.',
			'icon'  => idc_asset('assets/icons/diff-1.svg'),
		],
		[
			'title' => 'Fotobiomodulação do SNC',
			'text'  => 'Terapia com luz para reparação celular e redução da neuroinflamação.',
			'icon'  => idc_asset('assets/icons/diff-2.svg'),
		],
		[
			'title' => 'Proloterapia',
			'text'  => 'Infiltrações regenerativas para fortalecimento de ligamentos e tendões.',
			'icon'  => idc_asset('assets/icons/diff-3.svg'),
		],
		[
			'title' => 'PRP — Plasma Rico em Plaquetas',
			'text'  => 'Utilização de fatores de crescimento autólogos para regeneração tecidual.',
			'icon'  => idc_asset('assets/icons/diff-4.svg'),
		],
		[
			'title' => 'Hidrogel',
			'text'  => 'Injeções de suporte biomecânico para alívio articular prolongado.',
			'icon'  => idc_asset('assets/icons/inst-2.svg'),
		],
		[
			'title' => 'Soroterapia',
			'text'  => 'Reposição endovenosa de vitaminas e antioxidantes para suporte imunológico.',
			'icon'  => idc_asset('assets/icons/inst-3.svg'),
		],
		[
			'title' => 'Emagrecimento',
			'text'  => 'Abordagem metabólica focada na redução de sobrecarga articular e inflamação sistêmica.',
			'icon'  => idc_asset('assets/icons/inst-4.svg'),
		],
		[
			'title' => 'Nutrição Funcional',
			'text'  => 'Dietas anti-inflamatórias personalizadas para otimizar os resultados terapêuticos.',
			'icon'  => idc_asset('assets/icons/inst-1.svg'),
		],
	];
}

/**
 * Grade para seed ACF (sem ícones — fallbacks do tema no front).
 *
 * @return list<array{title:string,text:string}>
 */
function idc_default_integrativa_grid_for_acf(): array {
	$rows = [];
	foreach (idc_default_integrativa_grid() as $item) {
		$rows[] = [
			'title' => $item['title'],
			'text'  => $item['text'],
		];
	}
	return $rows;
}

/**
 * Strip CTA padrão.
 *
 * @return array{idc_strip_title:string,idc_strip_lead:string,idc_strip_label:string}
 */
function idc_default_strip_cta(): array {
	return [
		'idc_strip_title' => 'Pronto para dar o primeiro passo?',
		'idc_strip_lead'  => 'Agende sua avaliação e fale com nossa equipe pelo WhatsApp.',
		'idc_strip_label' => 'Fale via WhatsApp',
	];
}

/**
 * Strip CTA alinhado ao Figma por slug de página.
 *
 * @return array{idc_strip_title:string,idc_strip_lead:string,idc_strip_label:string}
 */
function idc_default_strip_cta_for_slug(string $slug): array {
	$by_slug = [
		'especialidades' => [
			'idc_strip_title' => 'Dúvidas sobre por onde começar?',
			'idc_strip_lead'  => 'Nossa equipe está pronta para orientar você sobre a melhor abordagem para o seu caso.',
			'idc_strip_label' => 'Fale via WhatsApp',
		],
		'ortopedia-regenerativa' => [
			'idc_strip_title' => 'Pronto para iniciar seu cuidado?',
			'idc_strip_lead'  => '',
			'idc_strip_label' => 'Fale via WhatsApp',
		],
		'fisioterapia' => [
			'idc_strip_title' => 'Pronto para iniciar sua reabilitação?',
			'idc_strip_lead'  => '',
			'idc_strip_label' => 'Fale via WhatsApp',
		],
		'medicina-integrativa' => [
			'idc_strip_title' => 'Pronto para buscar um equilíbrio sistêmico para sua saúde?',
			'idc_strip_lead'  => '',
			'idc_strip_label' => 'Fale via WhatsApp',
		],
	];

	return $by_slug[$slug] ?? idc_default_strip_cta();
}

/**
 * Hero padrão por slug de página.
 *
 * @return array{idc_page_eyebrow:string,idc_page_title_before:string,idc_page_title_accent:string,idc_page_title_after:string,idc_page_lead:string}|null
 */
function idc_default_page_hero(string $slug): ?array {
	$heroes = [
		'especialidades' => [
			'idc_page_eyebrow'      => 'NOSSOS TRATAMENTOS',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Cuidar de você',
			'idc_page_title_after'  => ' é a nossa maior missão.',
			'idc_page_lead'         => 'Cada pilar reúne especialistas que trabalham juntos para devolver movimento, bem-estar e qualidade de vida.',
		],
		'ortopedia-regenerativa' => [
			'idc_page_eyebrow'      => 'TRATAMENTOS · ESPECIALIDADE PRINCIPAL',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Ortopedia Regenerativa',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Tratamentos que estimulam a recuperação das articulações, reduzem a dor e ajudam você a recuperar seus movimentos e sua qualidade de vida.',
		],
		'fisioterapia' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Fisioterapia Especializada em Dor',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Nossa abordagem integra técnicas avançadas com um cuidado humanizado profundo. Através de 4 fases de recuperação distintas, desenhamos um caminho focado não apenas em tratar os sintomas, mas em restaurar a verdadeira função e o bem-estar do seu corpo.',
		],
		'medicina-integrativa' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => 'Medicina ',
			'idc_page_title_accent' => 'Integrativa',
			'idc_page_title_after'  => ' e Regenerativa.',
			'idc_page_lead'         => 'Uma abordagem que enxerga o paciente como um todo. A medicina integrativa atua em sinergia com os tratamentos ortopédicos, buscando equilibrar o organismo, reduzir inflamações sistêmicas e otimizar a capacidade natural de cura e regeneração celular do corpo.',
		],
		'o-instituto' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Existimos para que ninguém seja definido pela sua dor.',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Desde 1987, o Instituto Dr. Chao vem transformando a maneira como olhamos para a ortopedia e reabilitação. Nossa história começou com a crença fundamental de que o tratamento médico deve transcender o sintoma, abraçando a complexidade do ser humano. Hoje, somos referência em cuidado integrado, unindo alta precisão técnica a uma escuta empática, garantindo que cada paciente encontre seu caminho único para o bem-estar duradouro e uma vida em movimento pleno.',
		],
		'instalacoes' => [
			'idc_page_eyebrow'      => 'INSTALAÇÕES',
			'idc_page_title_before' => 'Um ambiente pensado para',
			'idc_page_title_accent' => 'o seu cuidado',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Salas amplas, equipamentos modernos e espaços de acolhimento — tudo preparado para a sua jornada de recuperação.',
		],
		'contato' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => 'Vamos conversar sobre o',
			'idc_page_title_accent' => ' seu cuidado?',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Nossa equipe está pronta para te receber com a atenção e precisão que sua saúde merece. Entre em contato para agendar uma consulta ou tirar dúvidas.',
		],
		'carreiras' => [
			'idc_page_eyebrow'      => 'TRABALHE CONOSCO',
			'idc_page_title_before' => 'Faça parte do',
			'idc_page_title_accent' => 'time',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'No Instituto Dr. Chao, acreditamos que oferecer um atendimento excepcional começa por ter uma equipe movida por empatia, dedicação e excelência. Se você compartilha do nosso compromisso de acolher, cuidar e transformar a jornada de diagnóstico e reabilitação das pessoas, nós queremos conhecer você. Venha construir uma carreira com propósito. Preencha o formulário abaixo, anexe seu currículo e dê o primeiro passo para fazer a diferença em cada etapa do nosso trabalho.',
		],
		'privacidade' => [
			'idc_page_eyebrow'      => 'PRIVACIDADE',
			'idc_page_title_before' => 'Como cuidamos dos ',
			'idc_page_title_accent' => 'seus dados',
			'idc_page_title_after'  => '',
			'idc_page_lead'         => 'Transparência e respeito às informações que você compartilha conosco no Instituto Dr. Chao.',
		],
		'blog' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Conhecimento',
			'idc_page_title_after'  => ' para o seu cuidado.',
			'idc_page_lead'         => 'Artigos, dicas e novidades sobre ortopedia, fisioterapia, medicina integrativa e bem-estar. Escritos por nossa equipe de especialistas para ajudar você a viver com mais movimento e menos dor.',
		],
	];

	return $heroes[$slug] ?? null;
}

/**
 * Galeria padrão — Instalações.
 *
 * @return list<array{image:string,caption:string}>
 */
function idc_default_instalacoes_gallery(): array {
	return [
		[
			'image'   => idc_asset('assets/images/instalacoes/instalacoes-recepcao.jpg'),
			'caption' => 'Recepção',
		],
		[
			'image'   => idc_asset('assets/images/instalacoes/instalacoes-recepcao-2.jpg'),
			'caption' => 'Área de atendimento',
		],
		[
			'image'   => idc_asset('assets/images/instalacoes/instalacoes-fisioterapia.jpg'),
			'caption' => 'Sala de fisioterapia',
		],
		[
			'image'   => idc_asset('assets/images/instalacoes/instalacoes-sala-zen.jpg'),
			'caption' => 'Sala de cuidados integrativos',
		],
		[
			'image'   => idc_asset('assets/images/instalacoes/instalacoes-entrada.jpg'),
			'caption' => 'Entrada',
		],
	];
}

/**
 * Conteúdo HTML padrão da política de privacidade (LGPD).
 */
function idc_default_privacy_content(): string {
	return <<<'HTML'
<h2>1. Quem somos</h2>
<p>O Instituto Dr. Chao — Centro de Ortopedia e Traumatologia trata dados pessoais conforme a Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018), com sede na Rua Maria Cândida, 1.788, Vila Guilherme, São Paulo — SP.</p>

<h2>2. Dados que coletamos</h2>
<p>Coletamos dados fornecidos por você em formulários do site (nome, e-mail, telefone, mensagem e, em candidaturas, currículo), além de dados técnicos de navegação necessários ao funcionamento do site.</p>

<h2>3. Finalidade e seus direitos</h2>
<p>Utilizamos os dados para responder solicitações de contato, agendar atendimentos, avaliar candidaturas e melhorar a experiência no site. Você pode solicitar acesso, correção, exclusão ou portabilidade dos seus dados, bem como revogar consentimentos, pelo e-mail atendimento@institutodrchao.com.br ou telefone (11) 2218-8080.</p>

<h2>4. Contato do encarregado</h2>
<p>Não vendemos dados pessoais. Podemos compartilhar informações com prestadores essenciais (hospedagem, e-mail) sob contrato e apenas na medida necessária. Para questões de privacidade, escreva para atendimento@institutodrchao.com.br com o assunto “LGPD”.</p>
HTML;
}

/**
 * Assuntos padrão do formulário de contato.
 *
 * @return list<array{label:string,value:string}>
 */
function idc_default_contato_assuntos(): array {
	return [
		['label' => 'Agendar consulta', 'value' => 'Agendar consulta'],
		['label' => 'Ortopedia Regenerativa', 'value' => 'Ortopedia'],
		['label' => 'Fisioterapia', 'value' => 'Fisioterapia'],
		['label' => 'Medicina Integrativa', 'value' => 'Medicina Integrativa'],
		['label' => 'Dúvidas gerais', 'value' => 'Dúvidas gerais'],
	];
}

/**
 * Áreas padrão do formulário de carreiras.
 *
 * @return list<array{label:string}>
 */
function idc_default_carreiras_areas(): array {
	return [
		['label' => 'Ortopedia'],
		['label' => 'Fisioterapia'],
		['label' => 'Medicina Integrativa'],
		['label' => 'Recepção / Administrativo'],
		['label' => 'Outras áreas'],
	];
}

/**
 * Profissionais reais (produção) para seed.
 *
 * @return list<array{title:string,slug:string,content:string,crm:string,especialidade:string,image:string,menu_order:int}>
 */
function idc_default_profissionais_seed(): array {
	return [
		[
			'title'         => 'Dra. Dhebora Chao',
			'slug'          => 'dra-dhebora-chao',
			'content'       => "<ul>\n<li>Graduada em Medicina pela Universidade Nove de Julho</li>\n<li>Residência Médica em Ortopedia e Traumatologia no Hospital Antônio Giglio — SP</li>\n<li>Residência Médica (R4) em Ortopedia Pediátrica na Santa Casa de São Paulo — SP</li>\n<li>Residência Médica (R5) em Ortopedia Pediátrica na FMABC — SP</li>\n<li>Membro Titular da SBOT</li>\n<li>Pós-graduação em Ortomolecular, Nutrigenômica e Envelhecimento Saudável — FAPES SP</li>\n</ul>",
			'crm'           => 'CRM: 156716 / TEOT: 14625',
			'especialidade' => 'Diretora e Responsável Técnica · Ortopedia / Ortopedia Pediátrica',
			'image'         => 'assets/images/team/dra-dhebora-chao.jpg',
			'menu_order'    => 1,
		],
		[
			'title'         => 'Dra. Érica Luisada Troiano',
			'slug'          => 'dra-erica',
			'content'       => "<ul>\n<li>Graduação em Medicina pela Universidade Nove de Julho</li>\n<li>Residência médica em Ortopedia e Traumatologia — Hospital Municipal Campo Limpo</li>\n<li>Residência Médica em Cirurgia da Mão — Hospital Alvorada Moema</li>\n<li>Extensão universitária em Microcirurgia — UNIFESP</li>\n<li>Membro titular da SBOT</li>\n<li>Membro titular da Sociedade Brasileira de Cirurgia da Mão</li>\n</ul>",
			'crm'           => 'CRM: 163550',
			'especialidade' => 'Ortopedia e Traumatologia · Cirurgia da Mão',
			'image'         => 'assets/images/team/dra-erica.jpg',
			'menu_order'    => 2,
		],
		[
			'title'         => 'Dr. Daniel Bechara Jacob Ferreira',
			'slug'          => 'dr-daniel',
			'content'       => "<ul>\n<li>Graduado em Medicina pela UNICAMP</li>\n<li>Residência em Ortopedia e Traumatologia — UNICAMP</li>\n<li>Residência em Cirurgia do Joelho — UNICAMP</li>\n<li>Pós-graduação em Medicina do Esporte</li>\n<li>Membro titular da SBOT e da SBMEE</li>\n</ul>",
			'crm'           => 'CRM: 107865 / TEOT: 10204',
			'especialidade' => 'Ortopedia e Traumatologia · Cirurgia do Joelho · Medicina do Esporte',
			'image'         => 'assets/images/team/dr-daniel.jpg',
			'menu_order'    => 3,
		],
		[
			'title'         => 'Dr. Luiz Antonio Nascimento Campos',
			'slug'          => 'dr-luiz-antonio',
			'content'       => "<ul>\n<li>Graduado em Medicina pela FMUSP</li>\n<li>Residência no IOT-FMUSP</li>\n<li>Membro titular da SBOT</li>\n<li>Preceptoria da Residência Médica do IOT-HC-FMUSP (1989)</li>\n</ul>",
			'crm'           => 'CRM: 54003 / TEOT: 3202',
			'especialidade' => 'Ortopedia e Traumatologia',
			'image'         => 'assets/images/team/dr-luiz-antonio.jpg',
			'menu_order'    => 4,
		],
		[
			'title'         => 'Dr. Thiago Castro Garcia Belaunde',
			'slug'          => 'dr-thiago-belaunde',
			'content'       => "<ul>\n<li>Graduado em Medicina pela Universidade Nove de Julho</li>\n<li>Residência em Ortopedia e Traumatologia — Hospital Nossa Senhora do Pari</li>\n<li>Residência em Cirurgia do Pé e Tornozelo — Hospital Nossa Senhora do Pari</li>\n<li>Membro Titular da SBOT e da Sociedade Brasileira de Cirurgia do Pé e Tornozelo</li>\n</ul>",
			'crm'           => 'CRM: 160897 / TEOT: 15707',
			'especialidade' => 'Ortopedia e Traumatologia · Cirurgia do Pé e Tornozelo',
			'image'         => 'assets/images/team/dr-thiago-belaunde.jpg',
			'menu_order'    => 5,
		],
		[
			'title'         => 'Dr. Arthur Burdino Bonifácio',
			'slug'          => 'dr-arthur-bonifacio',
			'content'       => "<ul>\n<li>Formado em Fisioterapia desde 2022</li>\n<li>Pós-graduado em Ortopedia e Traumatologia</li>\n<li>Pós-graduado em Neurociência da Dor</li>\n<li>Pós-graduado em Raciocínio e Prática Clínica da Coluna Vertebral</li>\n<li>Especializando no Método McKenzie</li>\n</ul>",
			'crm'           => '',
			'especialidade' => 'Fisioterapeuta Ortopédico',
			'image'         => 'assets/images/team/dr-arthur-bonifacio.jpg',
			'menu_order'    => 6,
		],
		[
			'title'         => 'Dr. Nicolas Pereira de Araújo',
			'slug'          => 'dr-nicolas-pereira',
			'content'       => '<p>Especialização no tratamento das afecções da coluna vertebral; experiência em lesões ortopédicas de membros superiores e inferiores; formações em terapia manual e tratamento de atletas.</p>',
			'crm'           => '',
			'especialidade' => 'Fisioterapeuta Ortopédico',
			'image'         => 'assets/images/team/dr-nicolas-pereira.jpg',
			'menu_order'    => 7,
		],
	];
}
