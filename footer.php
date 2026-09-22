<?php
/**
 * Footer.
 *
 * @package Instituto_Dr_Chao
 */

$instagram = (string) idc_option('idc_instagram', '');
$facebook  = (string) idc_option('idc_facebook', '');
$year      = (int) gmdate('Y');
?>
<footer class="idc-footer" role="contentinfo">
	<div class="idc-footer__grid">
		<div class="idc-footer__brand">
			<?php get_template_part('template-parts/components/logo', null, ['variant' => 'light']); ?>
			<p class="idc-footer__tagline">
				<?php esc_html_e('Precisão que acolhe. Excelência em ortopedia e reabilitação integrada desde 1987.', 'instituto-dr-chao'); ?>
			</p>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php esc_html_e('Tratamentos', 'instituto-dr-chao'); ?></p>
			<ul class="idc-footer__links">
				<li><a href="<?php echo esc_url(home_url('/ortopedia-regenerativa/')); ?>"><?php esc_html_e('Ortopedia', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/fisioterapia/')); ?>"><?php esc_html_e('Fisioterapia', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/medicina-integrativa/')); ?>"><?php esc_html_e('Medicina Integrativa', 'instituto-dr-chao'); ?></a></li>
			</ul>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php esc_html_e('Institucional', 'instituto-dr-chao'); ?></p>
			<ul class="idc-footer__links">
				<li><a href="<?php echo esc_url(home_url('/o-instituto/')); ?>"><?php esc_html_e('O Instituto', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/#corpo-clinico')); ?>"><?php esc_html_e('Corpo Clínico', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'instituto-dr-chao'); ?></a></li>
			</ul>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php esc_html_e('Contato & Jurídico', 'instituto-dr-chao'); ?></p>
			<ul class="idc-footer__links">
				<li><a href="<?php echo esc_url(home_url('/contato/')); ?>"><?php esc_html_e('Fale Conosco', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/carreiras/')); ?>"><?php esc_html_e('Trabalhe Conosco', 'instituto-dr-chao'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/privacidade/')); ?>"><?php esc_html_e('Privacidade', 'instituto-dr-chao'); ?></a></li>
			</ul>
		</div>
	</div>

	<div class="idc-footer__bottom">
		<p class="idc-footer__copy">
			&copy; <?php echo esc_html((string) $year); ?>
			<?php esc_html_e('Instituto Dr. Chao. Todos os direitos reservados.', 'instituto-dr-chao'); ?>
		</p>
		<div class="idc-footer__social">
			<?php if ($instagram !== '') : ?>
				<a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					<img src="<?php echo esc_url(idc_asset('assets/icons/social-instagram.svg')); ?>" alt="" width="17" height="15" decoding="async">
				</a>
			<?php endif; ?>
			<?php if ($facebook !== '') : ?>
				<a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
					<img src="<?php echo esc_url(idc_asset('assets/icons/social-share.svg')); ?>" alt="" width="15" height="17" decoding="async">
				</a>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php
$scripts_body = idc_option('idc_scripts_body', '');
if (is_string($scripts_body) && $scripts_body !== '') {
	echo $scripts_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
wp_footer();
?>
</body>
</html>
