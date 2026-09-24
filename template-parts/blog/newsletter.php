<?php
/**
 * Newsletter do Blog — Figma 133:2534.
 *
 * @package Instituto_Dr_Chao
 */

$notice = '';
$status = isset($_GET['idc_nl']) ? sanitize_key((string) $_GET['idc_nl']) : '';
if ($status === 'ok') {
	$notice = __('Inscrição recebida. Obrigado!', 'instituto-dr-chao');
} elseif ($status === 'invalid') {
	$notice = __('Informe um e-mail válido.', 'instituto-dr-chao');
} elseif ($status === 'err') {
	$notice = __('Não foi possível concluir agora. Tente novamente.', 'instituto-dr-chao');
}

$title = __('Fique por dentro das novidades', 'instituto-dr-chao');
$lead  = __('Receba conteúdos sobre ortopedia, reabilitação e bem-estar — escritos pela nossa equipe.', 'instituto-dr-chao');
?>
<section class="idc-blog-newsletter" aria-labelledby="idc-blog-nl-title">
	<div class="idc-container">
		<div class="idc-blog-newsletter__inner">
			<div class="idc-blog-newsletter__copy">
				<h2 id="idc-blog-nl-title" class="idc-blog-newsletter__title"><?php echo esc_html($title); ?></h2>
				<p class="idc-blog-newsletter__lead"><?php echo esc_html($lead); ?></p>
			</div>
			<form
				class="idc-blog-newsletter__form"
				method="post"
				action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
			>
				<input type="hidden" name="action" value="idc_blog_newsletter">
				<?php wp_nonce_field('idc_blog_newsletter', 'idc_nl_nonce'); ?>
				<label class="screen-reader-text" for="idc-nl-email"><?php esc_html_e('Seu e-mail', 'instituto-dr-chao'); ?></label>
				<input
					class="idc-blog-newsletter__input"
					type="email"
					id="idc-nl-email"
					name="email"
					required
					autocomplete="email"
					placeholder="<?php esc_attr_e('seu@email.com', 'instituto-dr-chao'); ?>"
				>
				<button type="submit" class="idc-btn idc-btn--primary idc-blog-newsletter__submit">
					<?php esc_html_e('Inscrever-se', 'instituto-dr-chao'); ?>
				</button>
			</form>
			<?php if ($notice !== '') : ?>
				<p class="idc-blog-newsletter__notice" role="status"><?php echo esc_html($notice); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
