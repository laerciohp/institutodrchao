/**
 * Menu mobile + interações base.
 */
(function () {
	'use strict';

	var toggle = document.querySelector('[data-idc-nav-toggle]');
	var drawer = document.querySelector('[data-idc-nav-drawer]');

	if (!toggle || !drawer) {
		return;
	}

	toggle.addEventListener('click', function () {
		var open = drawer.classList.toggle('is-open');
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		document.body.classList.toggle('idc-nav-open', open);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
			drawer.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.classList.remove('idc-nav-open');
		}
	});
})();

/**
 * FAQ accordion — páginas de especialidade.
 */
(function () {
	'use strict';

	document.querySelectorAll('[data-idc-accordion]').forEach(function (accordion) {
		accordion.querySelectorAll('[data-idc-accordion-trigger]').forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				var expanded = trigger.getAttribute('aria-expanded') === 'true';
				var panelId = trigger.getAttribute('aria-controls');
				var panel = panelId ? document.getElementById(panelId) : null;

				trigger.setAttribute('aria-expanded', expanded ? 'false' : 'true');
				if (panel) {
					panel.hidden = expanded;
				}
			});
		});
	});
})();

/**
 * Carrosséis (depoimentos + equipe) — bullets + drag (mouse/touch).
 * Navegação só por dots (como no Figma); setas removidas.
 */
(function () {
	'use strict';

	function visibleCount(root) {
		var w = window.innerWidth || document.documentElement.clientWidth;
		if (root.classList.contains('idc-team__carousel')) {
			if (w >= 1024) return 3;
			if (w >= 700) return 2;
			return 1;
		}
		if (w >= 1024) return 3;
		if (w >= 700) return 2;
		return 1;
	}

	function initCarousel(root) {
		var track = root.querySelector('[data-idc-carousel-track]');
		var viewport = root.querySelector('.idc-testimonials__viewport, .idc-team__viewport') || track.parentElement;
		var slides = Array.prototype.slice.call(root.querySelectorAll('[data-idc-carousel-slide]'));
		var dotsWrap = root.querySelector('[data-idc-carousel-dots]');
		if (!track || slides.length === 0) return;

		var index = 0;
		var autoplayMs = parseInt(root.getAttribute('data-idc-autoplay') || '0', 10);
		var timer = null;
		var pointerId = null;
		var startX = 0;
		var startY = 0;
		var deltaX = 0;
		var dragging = false;
		var moved = false;
		var axisLocked = null; // 'x' | 'y' | null
		var baseOffset = 0;
		var suppressClick = false;

		root.setAttribute('tabindex', '0');
		root.setAttribute('aria-roledescription', 'carrossel');

		function maxIndex() {
			return Math.max(0, slides.length - visibleCount(root));
		}

		function gapPx() {
			var style = window.getComputedStyle(track);
			return parseFloat(style.columnGap || style.gap) || 0;
		}

		function slideStep() {
			return slides[0].getBoundingClientRect().width + gapPx();
		}

		function offsetFor(i) {
			return i * slideStep();
		}

		function setTransform(px, animate) {
			track.style.transition = animate === false ? 'none' : '';
			track.style.transform = 'translateX(-' + px + 'px)';
		}

		function goTo(i, animate) {
			index = Math.max(0, Math.min(i, maxIndex()));
			setTransform(offsetFor(index), animate !== false);
			updateDots();
		}

		function buildDots() {
			if (!dotsWrap) return;
			dotsWrap.innerHTML = '';
			var pages = maxIndex() + 1;
			if (pages <= 1) {
				dotsWrap.hidden = true;
				return;
			}
			dotsWrap.hidden = false;
			for (var d = 0; d < pages; d++) {
				(function (page) {
					var btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'idc-carousel__dot';
					btn.setAttribute('role', 'tab');
					btn.setAttribute('aria-label', 'Ir para slide ' + (page + 1));
					btn.addEventListener('click', function () {
						goTo(page);
						restartAutoplay();
					});
					dotsWrap.appendChild(btn);
				})(d);
			}
			updateDots();
		}

		function updateDots() {
			if (!dotsWrap) return;
			var dots = dotsWrap.querySelectorAll('.idc-carousel__dot');
			dots.forEach(function (dot, i) {
				var active = i === index;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-selected', active ? 'true' : 'false');
				dot.tabIndex = active ? 0 : -1;
			});
		}

		function stopAutoplay() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function startAutoplay() {
			stopAutoplay();
			if (autoplayMs <= 0 || slides.length <= visibleCount(root)) return;
			timer = setInterval(function () {
				if (index >= maxIndex()) {
					goTo(0);
				} else {
					goTo(index + 1);
				}
			}, autoplayMs);
		}

		function restartAutoplay() {
			stopAutoplay();
			startAutoplay();
		}

		function onPointerDown(e) {
			if (e.pointerType === 'mouse' && e.button !== 0) return;
			if (maxIndex() <= 0) return;
			pointerId = e.pointerId;
			startX = e.clientX;
			startY = e.clientY;
			deltaX = 0;
			dragging = true;
			moved = false;
			axisLocked = null;
			baseOffset = offsetFor(index);
			stopAutoplay();
			setTransform(baseOffset, false);
			root.classList.add('is-dragging');
			if (viewport && viewport.setPointerCapture) {
				try {
					viewport.setPointerCapture(pointerId);
				} catch (err) { /* ignore */ }
			}
		}

		function onPointerMove(e) {
			if (!dragging || e.pointerId !== pointerId) return;
			var dx = e.clientX - startX;
			var dy = e.clientY - startY;

			if (!axisLocked) {
				if (Math.abs(dx) < 6 && Math.abs(dy) < 6) return;
				axisLocked = Math.abs(dx) >= Math.abs(dy) ? 'x' : 'y';
				if (axisLocked === 'y') {
					dragging = false;
					root.classList.remove('is-dragging');
					setTransform(baseOffset, true);
					restartAutoplay();
					return;
				}
			}

			if (axisLocked !== 'x') return;

			e.preventDefault();
			deltaX = dx;
			moved = Math.abs(deltaX) > 8;
			var maxOff = offsetFor(maxIndex());
			var next = baseOffset - deltaX;
			// Resistência nas bordas
			if (next < 0) next = next * 0.35;
			if (next > maxOff) next = maxOff + (next - maxOff) * 0.35;
			setTransform(next, false);
		}

		function onPointerUp(e) {
			if (e.pointerId !== pointerId) return;
			var wasDragging = dragging || moved;
			dragging = false;
			pointerId = null;
			root.classList.remove('is-dragging');
			setTransform(offsetFor(index), false);

			if (wasDragging && Math.abs(deltaX) > 40) {
				goTo(deltaX < 0 ? index + 1 : index - 1);
				suppressClick = true;
			} else {
				goTo(index);
			}
			deltaX = 0;
			restartAutoplay();
		}

		function onClickCapture(e) {
			if (!suppressClick) return;
			e.preventDefault();
			e.stopPropagation();
			suppressClick = false;
		}

		var dragSurface = viewport || track;
		dragSurface.addEventListener('pointerdown', onPointerDown);
		dragSurface.addEventListener('pointermove', onPointerMove);
		dragSurface.addEventListener('pointerup', onPointerUp);
		dragSurface.addEventListener('pointercancel', onPointerUp);
		dragSurface.addEventListener('lostpointercapture', onPointerUp);
		track.addEventListener('click', onClickCapture, true);

		root.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				goTo(index - 1);
				restartAutoplay();
			} else if (e.key === 'ArrowRight') {
				e.preventDefault();
				goTo(index + 1);
				restartAutoplay();
			}
		});

		root.addEventListener('mouseenter', stopAutoplay);
		root.addEventListener('mouseleave', function () {
			if (!dragging) startAutoplay();
		});
		root.addEventListener('focusin', stopAutoplay);
		root.addEventListener('focusout', startAutoplay);

		window.addEventListener('resize', function () {
			buildDots();
			goTo(Math.min(index, maxIndex()), false);
		});

		buildDots();
		goTo(0, false);

		// Só inicia autoplay depois que a seção estiver visível (evita
		// avançar slides enquanto .idc-reveal ainda tem opacity:0).
		var section = root.closest('.idc-reveal') || root.closest('section');
		function maybeStart() {
			if (!section || section.classList.contains('is-visible') || !section.classList.contains('idc-reveal')) {
				startAutoplay();
				return true;
			}
			return false;
		}
		if (!maybeStart()) {
			section.addEventListener('idc:reveal', function onReveal() {
				section.removeEventListener('idc:reveal', onReveal);
				startAutoplay();
			});
			// Fallback se o reveal nunca disparar.
			window.setTimeout(function () {
				if (!timer) startAutoplay();
			}, 2500);
		}
	}

	document.querySelectorAll('[data-idc-carousel]').forEach(initCarousel);
})();

/**
 * Scroll reveal — fade-up nas seções principais.
 */
(function () {
	'use strict';

	var selectors = [
		'.idc-trust',
		'.idc-pillars',
		'.idc-why',
		'.idc-testimonials',
		'.idc-team',
		'.idc-blog',
		'.idc-cta',
		'.idc-page-hero',
		'.idc-hub-cards',
		'.idc-instituto',
		'.idc-specialty__content',
		'.idc-specialty__faq',
		'.idc-instalacoes',
		'.idc-contato',
		'.idc-carreiras',
		'.idc-strip-cta',
		'.idc-phases',
		'.idc-integrativa-grid',
		'.idc-blog-archive'
	];

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var nodes = [];

	selectors.forEach(function (sel) {
		document.querySelectorAll(sel).forEach(function (el) {
			nodes.push(el);
		});
	});

	if (nodes.length === 0) return;

	if (reduce || !('IntersectionObserver' in window)) {
		nodes.forEach(function (el) {
			el.classList.add('idc-reveal', 'is-visible');
		});
		return;
	}

	nodes.forEach(function (el) {
		el.classList.add('idc-reveal');
	});

	function reveal(el) {
		if (!el || el.classList.contains('is-visible')) return;
		el.classList.add('is-visible');
		el.dispatchEvent(new CustomEvent('idc:reveal', { bubbles: true }));
	}

	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				reveal(entry.target);
				io.unobserve(entry.target);
			}
		});
	}, { threshold: 0.08, rootMargin: '0px 0px -8% 0px' });

	nodes.forEach(function (el) {
		io.observe(el);
	});

	// Flush após o 1º paint: evita seções escaparem com opacity:0
	// (admin bar, lazy layout, IO que não dispara no load).
	function flushVisible() {
		var vh = window.innerHeight || document.documentElement.clientHeight || 0;
		nodes.forEach(function (el) {
			if (el.classList.contains('is-visible')) return;
			var r = el.getBoundingClientRect();
			if (r.bottom > 0 && r.top < vh * 0.95) {
				reveal(el);
				io.unobserve(el);
			}
		});
	}

	requestAnimationFrame(function () {
		requestAnimationFrame(flushVisible);
	});
	window.addEventListener('load', flushVisible, { once: true });
})();

/**
 * Filtro de categorias — arquivo do blog (data-cat nos cards).
 */
(function () {
	'use strict';

	var toolbar = document.querySelector('[data-idc-blog-filters]');
	if (!toolbar) return;

	var buttons = toolbar.querySelectorAll('[data-idc-blog-filter]');
	var cards = document.querySelectorAll('[data-idc-blog-grid] .idc-blog-card');
	var empty = document.querySelector('[data-idc-blog-empty]');
	var nav = document.querySelector('.idc-blog-archive__nav');

	function applyFilter(catId) {
		var visible = 0;
		cards.forEach(function (card) {
			var cats = (card.getAttribute('data-cat') || '').split(/\s+/).filter(Boolean);
			var show = catId === 'all' || cats.indexOf(catId) !== -1;
			card.classList.toggle('is-filtered-out', !show);
			if (show) visible += 1;
		});
		if (empty) {
			empty.hidden = visible > 0;
		}
		if (nav) {
			nav.hidden = catId !== 'all';
		}
	}

	buttons.forEach(function (btn) {
		btn.addEventListener('click', function () {
			var catId = btn.getAttribute('data-idc-blog-filter') || 'all';
			buttons.forEach(function (b) {
				var active = b === btn;
				b.classList.toggle('is-active', active);
				b.setAttribute('aria-pressed', active ? 'true' : 'false');
			});
			applyFilter(catId);
		});
	});
})();

/**
 * Nome do arquivo no seletor customizado (Carreiras).
 */
(function () {
	'use strict';
	document.querySelectorAll('[data-idc-file]').forEach(function (input) {
		var wrap = input.closest('.idc-form__file-wrap');
		var nameEl = wrap ? wrap.querySelector('[data-idc-file-name]') : null;
		if (!nameEl) return;
		input.addEventListener('change', function () {
			var file = input.files && input.files[0];
			nameEl.textContent = file ? file.name : '';
		});
	});
})();
