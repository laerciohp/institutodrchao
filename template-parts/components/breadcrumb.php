<?php
/**
 * Breadcrumb — Início > Página atual.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$current = (string) ($args['current'] ?? '');

if ($current === '') {
	return;
}

$sep_path = IDC_THEME_DIR . '/assets/icons/breadcrumb-sep.svg';
$has_sep  = is_readable($sep_path);
?>
<nav class="idc-breadcrumb" aria-label="<?php esc_attr_e('Trilha de navegação', 'instituto-dr-chao'); ?>">
	<ol class="idc-breadcrumb__list">
		<li class="idc-breadcrumb__item">
			<a class="idc-breadcrumb__link" href="<?php echo esc_url(home_url('/')); ?>">
				<?php esc_html_e('Início', 'instituto-dr-chao'); ?>
			</a>
		</li>
		<li class="idc-breadcrumb__sep" aria-hidden="true">
			<?php if ($has_sep) : ?>
				<img src="<?php echo esc_url(idc_asset('assets/icons/breadcrumb-sep.svg')); ?>" alt="" width="5" height="7" decoding="async">
			<?php else : ?>
				<span>&gt;</span>
			<?php endif; ?>
		</li>
		<li class="idc-breadcrumb__item idc-breadcrumb__item--current" aria-current="page">
			<?php echo esc_html($current); ?>
		</li>
	</ol>
</nav>
