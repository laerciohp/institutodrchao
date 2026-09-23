<?php
/**
 * Depoimentos — carrossel (Figma 133:493).
 * Fonte: CPT idc_depoimento (menu Depoimentos no painel).
 *
 * @package Instituto_Dr_Chao
 */

$badge = (string) idc_option('idc_testimonials_badge', '5.0 Avaliação Média');
$title = (string) idc_option('idc_testimonials_title', 'O que dizem nossos pacientes');

$items = function_exists('idc_get_testimonials_from_cpt') ? idc_get_testimonials_from_cpt() : [];
if ($items === []) {
	// Fallback: repeater ACF legado ou defaults.
	$legacy = idc_option('idc_testimonials', []);
	$items  = is_array($legacy) && $legacy !== [] ? $legacy : idc_default_testimonials();
}
$total = count($items);
?>
<section class="idc-testimonials" aria-labelledby="idc-testimonials-title">
	<div class="idc-container">
		<header class="idc-testimonials__intro">
			<div class="idc-testimonials__badge">
				<img src="<?php echo esc_url(idc_asset('assets/icons/star.svg')); ?>" alt="" width="20" height="19" decoding="async">
				<span><?php echo esc_html($badge); ?></span>
			</div>
			<h2 id="idc-testimonials-title" class="idc-testimonials__title"><?php echo esc_html($title); ?></h2>
		</header>

		<div class="idc-testimonials__carousel" data-idc-carousel data-idc-autoplay="<?php echo $total > 3 ? '6000' : '0'; ?>">
			<div class="idc-testimonials__viewport">
				<div class="idc-testimonials__track" data-idc-carousel-track>
					<?php foreach ($items as $item) :
						$rating = max(1, min(5, (int) ($item['rating'] ?? 5)));
						$quote  = trim((string) ($item['quote'] ?? ''));
						$quote  = preg_replace('/^[“"„«]\s*|\s*[”"»]$/u', '', $quote) ?? $quote;
						$name   = (string) ($item['name'] ?? '');
						$role   = (string) ($item['role'] ?? '');
						$photo  = idc_image_url($item['photo'] ?? null);
						?>
						<blockquote class="idc-testimonial idc-testimonials__slide" data-idc-carousel-slide>
							<div class="idc-testimonial__stars" aria-label="<?php echo esc_attr(sprintf(/* translators: %d stars */ __('%d de 5 estrelas', 'instituto-dr-chao'), $rating)); ?>">
								<?php for ($i = 0; $i < $rating; $i++) : ?>
									<img src="<?php echo esc_url(idc_asset('assets/icons/star-sm.svg')); ?>" alt="" width="20" height="19" decoding="async">
								<?php endfor; ?>
							</div>
							<p class="idc-testimonial__quote">“<?php echo esc_html($quote); ?>”</p>
							<footer class="idc-testimonial__author">
								<?php
								$initials = '';
								if ($name !== '') {
									$parts = preg_split('/\s+/u', trim($name)) ?: [];
									$parts = array_values(array_filter($parts, static fn($p) => $p !== ''));
									if ($parts !== []) {
										$initials = mb_strtoupper(mb_substr($parts[0], 0, 1));
										if (count($parts) > 1) {
											$initials .= mb_strtoupper(mb_substr($parts[count($parts) - 1], 0, 1));
										}
									}
								}
								?>
								<div class="idc-testimonial__avatar"<?php echo $photo === '' && $initials !== '' ? ' data-initials="' . esc_attr($initials) . '"' : ''; ?>>
									<?php if ($photo !== '') : ?>
										<img src="<?php echo esc_url($photo); ?>" alt="" width="48" height="48" loading="lazy" decoding="async">
									<?php endif; ?>
								</div>
								<div>
									<p class="idc-testimonial__name"><?php echo esc_html($name); ?></p>
									<p class="idc-testimonial__role"><?php echo esc_html($role); ?></p>
								</div>
							</footer>
						</blockquote>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ($total > 1) : ?>
				<div class="idc-carousel__controls">
					<div class="idc-carousel__dots" data-idc-carousel-dots role="tablist" aria-label="<?php esc_attr_e('Navegação dos depoimentos', 'instituto-dr-chao'); ?>"></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
