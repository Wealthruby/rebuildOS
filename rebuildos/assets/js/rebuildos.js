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
			var raw = localStorage.getItem(STORAGE_KEY);
			return raw ? normalizeData(JSON.parse(raw)) : cloneEmptyData();
		} catch (error) {
			return cloneEmptyData();
		}
	}

	function saveData(data) {
		localStorage.setItem(STORAGE_KEY, JSON.stringify(normalizeData(data)));
	}

	function getFormValues(form) {
		var data = {};
		new FormData(form).forEach(function (value, key) {
			data[key] = typeof value === 'string' ? value.trim() : value;
		});
		return data;
	}

	function makeRecord(payload) {
		return Object.assign({
			id: Date.now().toString(36) + Math.random().toString(36).slice(2, 6),
			timestamp: new Date().toISOString()
		}, payload);
	}

	function mostCommon(items) {
		if (!items.length) {
			return 'Not enough data yet';
		}
		var counts = {};
		items.forEach(function (value) {
			if (!value) {
				return;
			}
			counts[value] = (counts[value] || 0) + 1;
		});
		var sorted = Object.keys(counts).sort(function (a, b) {
			return counts[b] - counts[a];
		});
		return sorted[0] || 'Not enough data yet';
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('.rebuildos-app[data-rebuildos-app="1"]');
		if (!app) {
			return;
		}

		var data = loadData();
		saveData(data);

		var feedback = app.querySelector('[data-rebuildos-feedback]');
		var tabs = app.querySelectorAll('.rebuildos-tabs__button');
		var panels = app.querySelectorAll('[data-rebuildos-panel]');
		var todayResult = app.querySelector('[data-rebuildos-today-result]');
		var urgeResult = app.querySelector('[data-rebuildos-urge-result]');
		var resetResult = app.querySelector('[data-rebuildos-reset-result]');
		var autopsyResult = app.querySelector('[data-rebuildos-autopsy-result]');
		var controlResult = app.querySelector('[data-rebuildos-control-result]');
		var weeklyResult = app.querySelector('[data-rebuildos-weekly-result]');
		var dashboardCards = app.querySelector('[data-rebuildos-dashboard-cards]');
		var dashboardEmpty = app.querySelector('[data-rebuildos-dashboard-empty]');
		var exportSummary = app.querySelector('[data-rebuildos-export-summary]');
		var timerDisplay = app.querySelector('[data-rebuildos-timer]');
		var timerInterval = null;

		function setFeedback(message) {
			if (feedback) {
				feedback.textContent = message;
			}
		}

		function riskLabel(score) {
			if (score >= 4) {
				return 'High';
			}
			if (score >= 2.7) {
				return 'Medium';
			}
			return 'Low';
		}

		function suggestBoundary(checkin) {
			if (checkin.screenRisk >= 4 || checkin.riskWindow === 'late night') {
				return 'Phone out of room before your risk window.';
			}
			if (checkin.stressLevel >= 4) {
				return 'Set one short recovery break before your next pressure block.';
			}
			if (checkin.lonelinessLevel >= 4) {
				return 'Send one honest message before evening.';
			}
			return 'Protect your minimum action window with a clear start time.';
		}

		function minimumReminder(entry) {
			return 'Minimum viable day reminder: ' + entry.minimumAction + '.';
		}

		function renderToday() {
			if (!todayResult) {
				return;
			}
			var latest = data.dailyCheckins[0];
			if (!latest) {
				todayResult.innerHTML = '<p class="rebuildos-panel__text">No check-in saved yet today.</p>';
				return;
			}
			todayResult.innerHTML = '<h4>Today\'s Rebuild Snapshot</h4><p><strong>Daily Risk Score: ' + latest.dailyRiskLabel + '</strong></p><p>Suggested boundary: ' + latest.suggestedBoundary + '</p><p>' + minimumReminder(latest) + '</p>';
		}

		function renderUrge() {
			if (!urgeResult) {
				return;
			}
			var latest = data.urgeLogs[0];
			var survived = data.urgeLogs.filter(function (entry) {
				return entry.outcome === 'survived';
			}).length;
			var relapsed = data.urgeLogs.filter(function (entry) {
				return entry.outcome === 'relapsed';
			}).length;
			if (!latest) {
				urgeResult.innerHTML = '<p class="rebuildos-panel__text">No urge events logged yet.</p>';
				return;
			}
			urgeResult.innerHTML = '<h4>Saved Event</h4><p>Intensity ' + latest.intensity + '/10 • ' + latest.emotion + ' • ' + latest.outcome + '</p><p>You recorded the moment. That creates useful pattern data.</p><p>Survived: ' + survived + ' | Relapsed: ' + relapsed + '</p>';
		}

		function renderAutopsy() {
			if (!autopsyResult) {
				return;
			}
			var latest = data.relapseAutopsies[0];
			if (!latest) {
				autopsyResult.innerHTML = '<p class="rebuildos-panel__text">No autopsy entries yet.</p>';
				return;
			}
			autopsyResult.innerHTML = '<h4>Latest Review</h4><p><strong>Likely system weak point:</strong> ' + latest.likelyWeakPoint + '</p><p><strong>One system adjustment:</strong> ' + latest.systemAdjustment + '</p><p><strong>Next clean action:</strong> ' + latest.nextCleanAction + '</p>';
		}

		function renderControl() {
			if (!controlResult) {
				return;
			}
			var latest = data.controlAudits[0];
			if (!latest) {
				controlResult.innerHTML = '<p class="rebuildos-panel__text">No control audit saved yet.</p>';
				return;
			}
			controlResult.innerHTML = '<h4>Control Snapshot</h4><p>Control Score: ' + latest.controlScore + '/100</p><p>Top dependency: ' + latest.topDependency + '</p><p>One control move: ' + latest.controllableAction + '</p>';
		}

		function renderWeekly() {
			if (!weeklyResult) {
				return;
			}
			var latest = data.weeklyReviews[0];
			if (!latest) {
				weeklyResult.innerHTML = '<p class="rebuildos-panel__text">No weekly review saved yet.</p>';
				return;
			}
			weeklyResult.innerHTML = '<h4>Latest Weekly Review</h4><p>Biggest win: ' + latest.biggestWin + '</p><p>System weakness: ' + latest.systemWeakness + '</p><p>Next boundary: ' + latest.nextBoundary + '</p>';
		}

		function renderDashboard() {
			if (!dashboardCards || !dashboardEmpty) {
				return;
			}
			var enoughData = data.dailyCheckins.length + data.urgeLogs.length >= 3;
			dashboardEmpty.hidden = enoughData;
			dashboardCards.innerHTML = '';
			if (!enoughData) {
				return;
			}

			var avgIntensity = data.urgeLogs.length ? (data.urgeLogs.reduce(function (sum, entry) {
				return sum + Number(entry.intensity || 0);
			}, 0) / data.urgeLogs.length).toFixed(1) : '0.0';
			var survived = data.urgeLogs.filter(function (entry) { return entry.outcome === 'survived'; }).length;
			var relapsed = data.urgeLogs.filter(function (entry) { return entry.outcome === 'relapsed'; }).length;
			var avgRisk = data.dailyCheckins.length ? (data.dailyCheckins.reduce(function (sum, entry) {
				return sum + Number(entry.dailyRiskScore || 0);
			}, 0) / data.dailyCheckins.length) : 3;
			var rebuildScore = Math.max(0, Math.min(100, Math.round((survived * 10) + ((5 - avgRisk) * 12) - (relapsed * 8))));

			var cards = [
				['Most common trigger emotion', mostCommon(data.urgeLogs.map(function (e) { return e.emotion; }))],
				['Highest-risk time window', mostCommon(data.dailyCheckins.map(function (e) { return e.riskWindow; }))],
				['Most common device/context', mostCommon(data.urgeLogs.map(function (e) { return e.device + ' / ' + e.context; }))],
				['Average urge intensity', avgIntensity + ' / 10'],
				['Survived urges', String(survived)],
				['Relapse count', String(relapsed)],
				['Best replacement action', mostCommon(data.urgeLogs.map(function (e) { return e.responseAction || e.replacementAction; }))],
				['Current Rebuild Score', rebuildScore + ' / 100'],
				['Suggested system adjustment', avgRisk >= 4 ? 'Add a hard boundary before your highest-risk window.' : 'Keep repeating your strongest replacement action before risk windows.']
			];

			cards.forEach(function (card) {
				var el = document.createElement('article');
				el.className = 'rebuildos-card';
				el.innerHTML = '<h4>' + card[0] + '</h4><p>' + card[1] + '</p>';
				dashboardCards.appendChild(el);
			});
		}

		function renderExportSummary() {
			if (!exportSummary) {
				return;
			}
			exportSummary.innerHTML = '';
			[
				['Daily check-ins', data.dailyCheckins.length],
				['Urge logs', data.urgeLogs.length],
				['Relapse autopsies', data.relapseAutopsies.length],
				['Control audits', data.controlAudits.length],
				['Weekly reviews', data.weeklyReviews.length]
			].forEach(function (pair) {
				var card = document.createElement('article');
				card.className = 'rebuildos-card';
				card.innerHTML = '<h4>' + pair[0] + '</h4><p>' + pair[1] + '</p>';
				exportSummary.appendChild(card);
			});
		}

		function renderAll() {
			renderToday();
			renderUrge();
			renderAutopsy();
			renderControl();
			renderWeekly();
			renderDashboard();
			renderExportSummary();
		}

		tabs.forEach(function (button) {
			button.addEventListener('click', function () {
				var tab = button.getAttribute('data-rebuildos-tab');
				tabs.forEach(function (btn) { btn.classList.remove('is-active'); });
				button.classList.add('is-active');
				panels.forEach(function (panel) {
					var active = panel.getAttribute('data-rebuildos-panel') === tab;
					panel.hidden = !active;
					panel.classList.toggle('is-active', active);
				});
			});
		});

		var todayForm = app.querySelector('[data-rebuildos-form="today"]');
		if (todayForm) {
			var ranges = todayForm.querySelectorAll('[data-rebuildos-range]');
			ranges.forEach(function (range) {
				var valueNode = range.parentNode.querySelector('[data-rebuildos-range-value]');
				if (valueNode) {
					valueNode.textContent = range.value;
				}
				range.addEventListener('input', function () {
					if (valueNode) {
						valueNode.textContent = range.value;
					}
				});
			});

			var riskWindowSelect = todayForm.querySelector('[data-rebuildos-risk-window]');
			var customWrap = todayForm.querySelector('[data-rebuildos-custom-window-wrap]');
			var customWindow = todayForm.querySelector('[data-rebuildos-custom-window]');

			function updateCustomWindowVisibility() {
				var show = riskWindowSelect && riskWindowSelect.value === 'custom';
				if (customWrap) {
					customWrap.classList.toggle('rebuildos-hidden', !show);
				}
				if (customWindow) {
					customWindow.required = !!show;
				}
			}

			if (riskWindowSelect) {
				riskWindowSelect.addEventListener('change', updateCustomWindowVisibility);
				updateCustomWindowVisibility();
			}

			todayForm.addEventListener('submit', function (event) {
				event.preventDefault();
				var values = getFormValues(todayForm);
				var riskWindow = values.riskWindow === 'custom' ? values.customRiskWindow : values.riskWindow;

				var screenRisk = Number(values.screenRisk);
				var stressLevel = Number(values.stressLevel);
				var lonelinessLevel = Number(values.lonelinessLevel);
				var boredomLevel = Number(values.boredomLevel);
				var sleepRisk = 6 - Number(values.sleepQuality);
				var energyRisk = 6 - Number(values.energyLevel);
				var score = (screenRisk + stressLevel + lonelinessLevel + boredomLevel + sleepRisk + energyRisk) / 6;

				var entry = makeRecord({
					sleepQuality: Number(values.sleepQuality),
					stressLevel: stressLevel,
					lonelinessLevel: lonelinessLevel,
					boredomLevel: boredomLevel,
					energyLevel: Number(values.energyLevel),
					screenRisk: screenRisk,
					riskWindow: riskWindow,
					boundary: values.boundary,
					minimumAction: values.minimumAction,
					notes: values.notes || '',
					dailyRiskScore: Number(score.toFixed(2)),
					dailyRiskLabel: riskLabel(score),
					suggestedBoundary: suggestBoundary({
						screenRisk: screenRisk,
						stressLevel: stressLevel,
						lonelinessLevel: lonelinessLevel,
						riskWindow: riskWindow
					})
				});
				data.dailyCheckins.unshift(entry);
				saveData(data);
				renderAll();
				setFeedback('Today check-in saved privately in this browser.');
			});
		}

		var urgeForm = app.querySelector('[data-rebuildos-form="urge"]');
		if (urgeForm) {
			urgeForm.addEventListener('submit', function (event) {
				event.preventDefault();
				var values = getFormValues(urgeForm);
				data.urgeLogs.unshift(makeRecord({
					intensity: Number(values.intensity),
					emotion: values.emotion,
					context: values.context,
					device: values.device,
					location: values.location,
					appBeforeUrge: values.appBeforeUrge,
					firstCompromise: values.firstCompromise,
					responseAction: values.responseAction,
					outcome: values.outcome,
					notes: values.notes || ''
				}));
				saveData(data);
				renderAll();
				urgeForm.reset();
				setFeedback('Urge event logged privately.');
			});
		}

		var resetStart = app.querySelector('[data-rebuildos-reset-start]');
		if (resetStart) {
			resetStart.addEventListener('click', function () {
				setFeedback('Reset started. Stay with the next 12 minutes.');
			});
		}

		var timerButton = app.querySelector('[data-rebuildos-reset-timer]');
		if (timerButton && timerDisplay) {
			timerButton.addEventListener('click', function () {
				var remaining = 720;
				clearInterval(timerInterval);
				timerInterval = setInterval(function () {
					var minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
					var seconds = String(remaining % 60).padStart(2, '0');
					timerDisplay.textContent = minutes + ':' + seconds;
					remaining -= 1;
					if (remaining < 0) {
						clearInterval(timerInterval);
						timerDisplay.textContent = '00:00';
						setFeedback('12-minute reset complete. Log your outcome.');
					}
				}, 1000);
			});
		}

		var resetForm = app.querySelector('[data-rebuildos-form="reset"]');
		if (resetForm) {
			resetForm.addEventListener('submit', function (event) {
				event.preventDefault();
				var values = getFormValues(resetForm);
				var resetRecord = makeRecord({
					replacementAction: values.replacementAction,
					urgeName: values.urgeName,
					intensity: Number(values.intensity),
					lie: values.lie,
					outcome: values.outcome,
					emotion: 'other',
					context: 'emergency reset',
					device: 'other',
					responseAction: values.replacementAction
				});
				data.closedLoopActions.unshift(resetRecord);
				data.urgeLogs.unshift(resetRecord);
				saveData(data);
				resetResult.innerHTML = '<h4>Reset Logged</h4><p>You moved through a high-risk moment. Record the pattern, then return to the next clean action.</p>';
				renderAll();
				setFeedback('Emergency reset outcome saved privately.');
			});
		}

		var autopsyForm = app.querySelector('[data-rebuildos-form="autopsy"]');
		if (autopsyForm) {
			autopsyForm.addEventListener('submit', function (event) {
				event.preventDefault();
				var values = getFormValues(autopsyForm);
				var likelyWeakPoint = values.boundaryFailed || values.firstCompromise;
				var systemAdjustment = values.systemChange || ('Reinforce boundary around: ' + likelyWeakPoint);

				data.relapseAutopsies.unshift(makeRecord({
					whatHappened: values.whatHappened,
					firstCompromise: values.firstCompromise,
					boundaryFailed: values.boundaryFailed,
					emotionAvoided: values.emotionAvoided,
					lieBelieved: values.lieBelieved,
					replacementNeeded: values.replacementNeeded,
					systemChange: values.systemChange,
					nextCleanAction: values.nextCleanAction,
					likelyWeakPoint: likelyWeakPoint,
					systemAdjustment: systemAdjustment
				}));
				saveData(data);
				renderAll();
				autopsyForm.reset();
				setFeedback('Relapse autopsy saved. This is pattern work, not self-judgment.');
			});
		}

		var controlForm = app.querySelector('[data-rebuildos-form="control"]');
		if (controlForm) {
			controlForm.addEventListener('submit', function (event) {
				event.preventDefault();
				var values = getFormValues(controlForm);
				values.controlScore = Math.round((Object.keys(values).filter(function (k) { return values[k]; }).length / 5) * 100);
				data.controlAudits.unshift(makeRecord(values));
				saveData(data);
				renderAll();
				controlForm.reset();
				setFeedback('Control audit saved.');
			});
		}

		var weeklyForm = app.querySelector('[data-rebuildos-form="weekly"]');
		if (weeklyForm) {
			weeklyForm.addEventListener('submit', function (event) {
				event.preventDefault();
				data.weeklyReviews.unshift(makeRecord(getFormValues(weeklyForm)));
				saveData(data);
				renderAll();
				weeklyForm.reset();
				setFeedback('Weekly review saved privately.');
			});
		}

		var exportJsonButton = app.querySelector('[data-rebuildos-export-json]');
		if (exportJsonButton) {
			exportJsonButton.addEventListener('click', function () {
				var blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
				var url = URL.createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = 'rebuildos-guest-data.json';
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
				URL.revokeObjectURL(url);
				setFeedback('JSON exported. Keep your file private.');
			});
		}

		var exportCsvButton = app.querySelector('[data-rebuildos-export-csv]');
		if (exportCsvButton) {
			exportCsvButton.addEventListener('click', function () {
				var rows = ['bucket,id,timestamp,summary'];
				Object.keys(data).forEach(function (bucket) {
					data[bucket].forEach(function (entry) {
						var summary = (entry.notes || entry.boundary || entry.outcome || '').toString().replace(/,/g, ' ');
						rows.push([bucket, entry.id, entry.timestamp, summary].join(','));
					});
				});
				var blob = new Blob([rows.join('\n')], { type: 'text/csv' });
				var url = URL.createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = 'rebuildos-guest-data.csv';
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
				URL.revokeObjectURL(url);
				setFeedback('CSV exported.');
			});
		}

		var importInput = app.querySelector('[data-rebuildos-import-file]');
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
						if (!window.confirm('Import will replace current local data. Continue?')) {
							setFeedback('Import cancelled.');
							importInput.value = '';
							return;
						}
						data = imported;
						saveData(data);
						renderAll();
						setFeedback('Import complete.');
					} catch (error) {
						setFeedback('Import failed. Please use a valid RebuildOS JSON file.');
					}
					importInput.value = '';
				};
				reader.readAsText(file);
			});
		}

		var clearButton = app.querySelector('[data-rebuildos-clear]');
		if (clearButton) {
			clearButton.addEventListener('click', function () {
				if (!window.confirm('Clear all local RebuildOS guest data from this browser?')) {
					setFeedback('Clear cancelled.');
					return;
				}
				data = cloneEmptyData();
				saveData(data);
				renderAll();
				setFeedback('Local browser data cleared.');
			});
		}

		renderAll();
	});
})();
