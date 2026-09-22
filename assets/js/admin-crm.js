/**
 * CRM Kanban — drag & drop + lixeira.
 */
(function () {
	'use strict';

	if (typeof idcCrm === 'undefined') {
		return;
	}

	var board = document.querySelector('.idc-crm-board');
	if (!board) {
		return;
	}

	var dragged = null;

	function post(action, data) {
		var body = new FormData();
		body.append('action', action);
		body.append('nonce', idcCrm.nonce);
		Object.keys(data).forEach(function (key) {
			body.append(key, data[key]);
		});
		return fetch(idcCrm.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: body,
		}).then(function (r) {
			return r.json();
		});
	}

	function refreshCounts() {
		board.querySelectorAll('.idc-crm-col').forEach(function (col) {
			var n = col.querySelectorAll('.idc-crm-card').length;
			var badge = col.querySelector('.idc-crm-col__count');
			if (badge) {
				badge.textContent = String(n);
			}
		});
	}

	board.querySelectorAll('.idc-crm-card').forEach(function (card) {
		card.addEventListener('dragstart', function (e) {
			dragged = card;
			card.classList.add('is-dragging');
			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/plain', card.getAttribute('data-id') || '');
		});
		card.addEventListener('dragend', function () {
			card.classList.remove('is-dragging');
			board.querySelectorAll('.idc-crm-col__list').forEach(function (z) {
				z.classList.remove('is-dragover');
			});
			dragged = null;
		});
	});

	board.querySelectorAll('.idc-crm-col__list').forEach(function (zone) {
		zone.addEventListener('dragover', function (e) {
			e.preventDefault();
			e.dataTransfer.dropEffect = 'move';
			zone.classList.add('is-dragover');
		});
		zone.addEventListener('dragleave', function () {
			zone.classList.remove('is-dragover');
		});
		zone.addEventListener('drop', function (e) {
			e.preventDefault();
			zone.classList.remove('is-dragover');
			if (!dragged) {
				return;
			}
			var col = zone.closest('.idc-crm-col');
			var status = col ? col.getAttribute('data-status') : '';
			var postId = dragged.getAttribute('data-id');
			if (!status || !postId) {
				return;
			}

			zone.appendChild(dragged);
			refreshCounts();

			post('idc_crm_set_status', {
				post_id: postId,
				status: status,
			}).then(function (res) {
				if (!res || !res.success) {
					window.alert(idcCrm.i18n.error);
					window.location.reload();
				}
			}).catch(function () {
				window.alert(idcCrm.i18n.error);
				window.location.reload();
			});
		});
	});

	board.querySelectorAll('.idc-crm-card__trash').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			if (!window.confirm(idcCrm.i18n.confirm)) {
				return;
			}
			var id = btn.getAttribute('data-id');
			var card = btn.closest('.idc-crm-card');
			post('idc_crm_trash', { post_id: id }).then(function (res) {
				if (res && res.success && card) {
					card.remove();
					refreshCounts();
				} else {
					window.alert(idcCrm.i18n.error);
				}
			}).catch(function () {
				window.alert(idcCrm.i18n.error);
			});
		});
	});
})();
