(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('.rebuildos-app[data-rebuildos-app="1"]');
		if (!app) {
			return;
		}

		var buttons = app.querySelectorAll('.rebuildos-tabs__button');
		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				buttons.forEach(function (btn) {
					btn.classList.remove('is-active');
				});
				button.classList.add('is-active');
			});
		});
	});
})();
