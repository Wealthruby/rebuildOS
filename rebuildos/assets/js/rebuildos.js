(function () {
	'use strict';

	var STORAGE_KEY = 'rebuildos_v1_data';
	var EMPTY_DATA = {
		dailyCheckins: [],
		urgeLogs: [],
		relapseAutopsies: [],
		controlAudits: [],
		closedLoopActions: [],
		weeklyReviews: []
	};

	function cloneEmptyData() {
		return JSON.parse(JSON.stringify(EMPTY_DATA));
	}

	function normalizeData(data) {
		var normalized = cloneEmptyData();
		if (!data || typeof data !== 'object') {
			return normalized;
		}

		Object.keys(normalized).forEach(function (key) {
			if (Array.isArray(data[key])) {
				normalized[key] = data[key];
			}
		});

		return normalized;
	}

	function loadData() {
		try {
			var raw = window.localStorage.getItem(STORAGE_KEY);
			if (!raw) {
				return cloneEmptyData();
			}
			return normalizeData(JSON.parse(raw));
		} catch (error) {
			return cloneEmptyData();
		}
	}

	function saveData(data) {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(normalizeData(data)));
	}

	function nowRecord(note) {
		return {
			id: Date.now().toString(36),
			timestamp: new Date().toISOString(),
			note: note
		};
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('.rebuildos-app[data-rebuildos-app="1"]');
		if (!app) {
			return;
		}

		var data = loadData();
		saveData(data);

		var feedback = app.querySelector('[data-rebuildos-feedback]');
		var stats = app.querySelector('[data-rebuildos-stats]');
		var buttons = app.querySelectorAll('.rebuildos-tabs__button');
		var panels = app.querySelectorAll('[data-rebuildos-panel]');
		var forms = app.querySelectorAll('[data-rebuildos-form]');
		var exportButton = app.querySelector('[data-rebuildos-export]');
		var importInput = app.querySelector('[data-rebuildos-import-file]');
		var clearButton = app.querySelector('[data-rebuildos-clear]');

		function renderStats() {
			if (!stats) {
				return;
			}

			stats.innerHTML = '';
			Object.keys(EMPTY_DATA).forEach(function (key) {
				var item = document.createElement('li');
				item.className = 'rebuildos-stats__item';
				item.textContent = key + ': ' + data[key].length;
				stats.appendChild(item);
			});
		}

		function setFeedback(message) {
			if (!feedback) {
				return;
			}
			feedback.textContent = message;
		}

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				var tab = button.getAttribute('data-rebuildos-tab');
				buttons.forEach(function (btn) {
					btn.classList.remove('is-active');
				});
				button.classList.add('is-active');

				panels.forEach(function (panel) {
					var isActive = panel.getAttribute('data-rebuildos-panel') === tab;
					panel.classList.toggle('is-active', isActive);
					panel.hidden = !isActive;
				});
			});
		});

		forms.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				event.preventDefault();
				var bucket = form.getAttribute('data-rebuildos-form');
				var textArea = form.querySelector('textarea[name="note"]');
				var note = textArea ? textArea.value.trim() : '';

				if (!bucket || !Array.isArray(data[bucket])) {
					setFeedback('This form is not configured correctly yet.');
					return;
				}

				if (!note) {
					setFeedback('Please add a short note before saving.');
					return;
				}

				data[bucket].unshift(nowRecord(note));
				saveData(data);
				renderStats();
				setFeedback('Saved privately in this browser.');
				form.reset();
			});
		});

		if (exportButton) {
			exportButton.addEventListener('click', function () {
				var blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
				var url = URL.createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = 'rebuildos-guest-data.json';
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
				URL.revokeObjectURL(url);
				setFeedback('Export complete. Keep your file private.');
			});
		}

		if (importInput) {
			importInput.addEventListener('change', function (event) {
				var file = event.target.files && event.target.files[0];
				if (!file) {
					return;
				}

				var reader = new FileReader();
				reader.onload = function (loadEvent) {
					try {
						var imported = normalizeData(JSON.parse(loadEvent.target.result));
						if (!window.confirm('Import will replace current local guest data. Continue?')) {
							setFeedback('Import cancelled. Your current local data is unchanged.');
							importInput.value = '';
							return;
						}

						data = imported;
						saveData(data);
						renderStats();
						setFeedback('Import complete. Local browser data updated.');
					} catch (error) {
						setFeedback('Import failed. Please choose a valid RebuildOS JSON file.');
					}
					importInput.value = '';
				};
				reader.readAsText(file);
			});
		}

		if (clearButton) {
			clearButton.addEventListener('click', function () {
				if (!window.confirm('Clear all local RebuildOS guest data from this browser?')) {
					setFeedback('Clear cancelled. Local data was not changed.');
					return;
				}

				data = cloneEmptyData();
				saveData(data);
				renderStats();
				setFeedback('Local data cleared from this browser.');
			});
		}

		renderStats();
	});
})();
