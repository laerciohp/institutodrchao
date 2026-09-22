<?php
/**
 * Breadcrumb — Home > [itens] > Página atual.
 *
 * Args:
 * - current: rótulo da página atual
 * - items: [{label, url}] intermediários
 * - home_label / home_url: override do primeiro crumb (padrão: Início → /)
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$current     = (string) ($args['current'] ?? '');
$items       = $args['items'] ?? [];
$home_label  = (string) ($args['home_label'] ?? __('Início', 'instituto-dr-chao'));
$home_url    = (string) ($args['home_url'] ?? home_url('/'));

if ($current === '') {
	return;
}

if (!is_array($items)) {
	$items = [];
}

$sep_path = IDC_THEME_DIR . '/assets/icons/breadcrumb-sep.svg';
$has_sep  = is_readable($sep_path);

$render_sep = static function () use ($has_sep): void {
	?>
	<li class="idc-breadcrumb__sep" aria-hidden="true">
		<?php if ($has_sep) : ?>
			<img src="<?php echo esc_url(idc_asset('assets/icons/breadcrumb-sep.svg')); ?>" alt="" width="5" height="7" decoding="async">
		<?php else : ?>
			<span>&gt;</span>
		<?php endif; ?>
	</li>
	<?php
};
?>
<nav class="idc-breadcrumb" aria-label="<?php esc_attr_e('Trilha de navegação', 'instituto-dr-chao'); ?>">
	<ol class="idc-breadcrumb__list">
		<li class="idc-breadcrumb__item">
			<a class="idc-breadcrumb__link" href="<?php echo esc_url($home_url); ?>">
				<?php echo esc_html($home_label); ?>
			</a>
		</li>
		<?php $render_sep(); ?>
		<?php foreach ($items as $item) : ?>
			<?php
			$label = (string) ($item['label'] ?? '');
			$url   = (string) ($item['url'] ?? '');
			if ($label === '') {
				continue;
			}
			?>
			<li class="idc-breadcrumb__item">
				<?php if ($url !== '') : ?>
					<a class="idc-breadcrumb__link" href="<?php echo esc_url($url); ?>">
						<?php echo esc_html($label); ?>
					</a>
				<?php else : ?>
					<?php echo esc_html($label); ?>
				<?php endif; ?>
			</li>
			<?php $render_sep(); ?>
		<?php endforeach; ?>
		<li class="idc-breadcrumb__item idc-breadcrumb__item--current" aria-current="page">
			<?php echo esc_html($current); ?>
		</li>
	</ol>
</nav>
