<?php
/**
 * Template Name: Privacidade
 * Política de privacidade (LGPD).
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = 'PRIVACIDADE';
$before  = 'Como cuidamos dos ';
$accent  = 'seus dados';
$after   = '';
$lead    = 'Transparência e respeito às informações que você compartilha conosco no Instituto Dr. Chao.';

if (function_exists('get_field')) {
	$eyebrow = get_field('idc_page_eyebrow') ?: $eyebrow;
	$before  = get_field('idc_page_title_before') ?: $before;
	$accent  = get_field('idc_page_title_accent') ?: $accent;
	$after   = get_field('idc_page_title_after') ?: $after;
	$lead    = get_field('idc_page_lead') ?: $lead;
}
?>
<main class="site-main site-main--page" id="main">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Privacidade', 'instituto-dr-chao'),
		'centered'         => true,
	]);
	?>

	<section class="idc-legal idc-container">
		<div class="idc-legal__content">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php if (trim((string) get_the_content()) !== '') : ?>
						<?php the_content(); ?>
					<?php else : ?>
						<h2><?php esc_html_e('1. Quem somos', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('O Instituto Dr. Chao — Centro de Ortopedia e Traumatologia trata dados pessoais conforme a Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018), com sede na Rua Maria Cândida, 1.788, Vila Guilherme, São Paulo — SP.', 'instituto-dr-chao'); ?></p>

						<h2><?php esc_html_e('2. Dados que coletamos', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('Coletamos dados fornecidos por você em formulários do site (nome, e-mail, telefone, mensagem e, em candidaturas, currículo), além de dados técnicos de navegação necessários ao funcionamento do site.', 'instituto-dr-chao'); ?></p>

						<h2><?php esc_html_e('3. Finalidade', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('Utilizamos os dados para responder solicitações de contato, agendar atendimentos, avaliar candidaturas e melhorar a experiência no site.', 'instituto-dr-chao'); ?></p>

						<h2><?php esc_html_e('4. Compartilhamento', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('Não vendemos dados pessoais. Podemos compartilhar informações com prestadores essenciais (hospedagem, e-mail) sob contrato e apenas na medida necessária.', 'instituto-dr-chao'); ?></p>

						<h2><?php esc_html_e('5. Seus direitos', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('Você pode solicitar acesso, correção, exclusão ou portabilidade dos seus dados, bem como revogar consentimentos, pelo e-mail atendimento@institutodrchao.com.br ou telefone (11) 2218-8080.', 'instituto-dr-chao'); ?></p>

						<h2><?php esc_html_e('6. Contato do encarregado', 'instituto-dr-chao'); ?></h2>
						<p><?php esc_html_e('Para questões de privacidade, escreva para atendimento@institutodrchao.com.br com o assunto “LGPD”.', 'instituto-dr-chao'); ?></p>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
