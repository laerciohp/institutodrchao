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
 * Formulário stub — previne submit real em contato.
 */
(function () {
	'use strict';

	document.querySelectorAll('[data-idc-form-stub]').forEach(function (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			alert('Formulário ilustrativo — integração pendente.');
		});
	});
})();
