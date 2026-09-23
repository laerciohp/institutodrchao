<?php
/**
 * Footer.
 *
 * @package Instituto_Dr_Chao
 */

$instagram = (string) idc_option('idc_instagram', '');
$facebook  = (string) idc_option('idc_facebook', '');
$year      = (int) gmdate('Y');

$tagline = (string) idc_option(
	'idc_footer_tagline',
	'Precisão que acolhe. Excelência em ortopedia e reabilitação integrada desde 1987.'
);
$copy = (string) idc_option(
	'idc_footer_copy',
	'Instituto Dr. Chao. Todos os direitos reservados.'
);
$col_tratamentos   = (string) idc_option('idc_footer_col_tratamentos', 'Tratamentos');
$col_institucional = (string) idc_option('idc_footer_col_institucional', 'Institucional');
$col_contato       = (string) idc_option('idc_footer_col_contato', 'Contato & Jurídico');

/**
 * Verifica se um theme_location de menu tem itens.
 */
$idc_footer_menu_has_items = static function (string $location): bool {
	$locations = get_nav_menu_locations();
	if (empty($locations[$location])) {
		return false;
	}
	$items = wp_get_nav_menu_items((int) $locations[$location]);
	return !empty($items);
};
?>
<footer class="idc-footer" role="contentinfo">
	<div class="idc-footer__grid">
		<div class="idc-footer__brand">
			<?php get_template_part('template-parts/components/logo', null, ['variant' => 'light']); ?>
			<p class="idc-footer__tagline">
				<?php echo esc_html($tagline !== '' ? $tagline : __('Precisão que acolhe. Excelência em ortopedia e reabilitação integrada desde 1987.', 'instituto-dr-chao')); ?>
			</p>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php echo esc_html($col_tratamentos !== '' ? $col_tratamentos : __('Tratamentos', 'instituto-dr-chao')); ?></p>
			<?php if ($idc_footer_menu_has_items('footer_tratamentos')) : ?>
				<?php
				wp_nav_menu([
					'theme_location' => 'footer_tratamentos',
					'container'      => false,
					'menu_class'     => 'idc-footer__links',
					'depth'          => 1,
					'fallback_cb'    => false,
				]);
				?>
			<?php else : ?>
				<ul class="idc-footer__links">
					<li><a href="<?php echo esc_url(home_url('/ortopedia-regenerativa/')); ?>"><?php esc_html_e('Ortopedia', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/fisioterapia/')); ?>"><?php esc_html_e('Fisioterapia', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/medicina-integrativa/')); ?>"><?php esc_html_e('Medicina Integrativa', 'instituto-dr-chao'); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php echo esc_html($col_institucional !== '' ? $col_institucional : __('Institucional', 'instituto-dr-chao')); ?></p>
			<?php if ($idc_footer_menu_has_items('footer_institucional')) : ?>
				<?php
				wp_nav_menu([
					'theme_location' => 'footer_institucional',
					'container'      => false,
					'menu_class'     => 'idc-footer__links',
					'depth'          => 1,
					'fallback_cb'    => false,
				]);
				?>
			<?php else : ?>
				<ul class="idc-footer__links">
					<li><a href="<?php echo esc_url(home_url('/o-instituto/')); ?>"><?php esc_html_e('O Instituto', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/corpo-clinico/')); ?>"><?php esc_html_e('Corpo Clínico', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'instituto-dr-chao'); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div>
			<p class="idc-footer__col-title"><?php echo esc_html($col_contato !== '' ? $col_contato : __('Contato & Jurídico', 'instituto-dr-chao')); ?></p>
			<?php if ($idc_footer_menu_has_items('footer_contato')) : ?>
				<?php
				wp_nav_menu([
					'theme_location' => 'footer_contato',
					'container'      => false,
					'menu_class'     => 'idc-footer__links',
					'depth'          => 1,
					'fallback_cb'    => false,
				]);
				?>
			<?php else : ?>
				<ul class="idc-footer__links">
					<li><a href="<?php echo esc_url(home_url('/contato/')); ?>"><?php esc_html_e('Fale Conosco', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/carreiras/')); ?>"><?php esc_html_e('Trabalhe Conosco', 'instituto-dr-chao'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/privacidade/')); ?>"><?php esc_html_e('Privacidade', 'instituto-dr-chao'); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>
	</div>

	<div class="idc-footer__bottom">
		<p class="idc-footer__copy">
			&copy; <?php echo esc_html((string) $year); ?>
			<?php echo esc_html($copy !== '' ? $copy : __('Instituto Dr. Chao. Todos os direitos reservados.', 'instituto-dr-chao')); ?>
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

<?php get_template_part('template-parts/components/whatsapp-fab'); ?>

<?php
$scripts_body = idc_option('idc_scripts_body', '');
if (is_string($scripts_body) && $scripts_body !== '') {
	echo $scripts_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
wp_footer();
?>
</body>
</html>
