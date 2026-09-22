<?php
/**
 * Logo (custom logo WP ou SVG do Figma).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$variant = $args['variant'] ?? 'dark'; // dark | light
$suffix  = $variant === 'light' ? '-light' : '';

if (has_custom_logo()) {
	echo '<div class="idc-logo idc-logo--custom">';
	the_custom_logo();
	echo '</div>';
	return;
}
?>
<a class="idc-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<span class="idc-logo__mark">
		<img src="<?php echo esc_url(idc_asset('assets/icons/logo-mark' . $suffix . '.svg')); ?>" alt="" width="32" height="32" decoding="async">
	</span>
	<span class="idc-logo__text">
		<span class="idc-logo__wordmark-dr">
			<img src="<?php echo esc_url(idc_asset('assets/icons/logo-wordmark-drchao' . $suffix . '.svg')); ?>" alt="dr. chao" width="77" height="19" decoding="async">
		</span>
		<span class="idc-logo__wordmark-inst">
			<img src="<?php echo esc_url(idc_asset('assets/icons/logo-wordmark-instituto' . $suffix . '.svg')); ?>" alt="instituto" width="52" height="4" decoding="async">
		</span>
	</span>
</a>
