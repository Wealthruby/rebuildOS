<?php
/**
 * RebuildOS app shell template.
 *
 * @package RebuildOS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs = array(
	'today'            => __( 'Today', 'rebuildos' ),
	'urge-log'         => __( 'Urge Log', 'rebuildos' ),
	'emergency-reset'  => __( 'Emergency Reset', 'rebuildos' ),
	'relapse-autopsy'  => __( 'Relapse Autopsy', 'rebuildos' ),
	'control-audit'    => __( 'Control Audit', 'rebuildos' ),
	'dashboard'        => __( 'Dashboard', 'rebuildos' ),
	'weekly-review'    => __( 'Weekly Review', 'rebuildos' ),
	'export'           => __( 'Export', 'rebuildos' ),
);
?>
<div class="rebuildos-app" data-rebuildos-app="1">
	<header class="rebuildos-app__header">
		<p class="rebuildos-app__eyebrow"><?php echo esc_html__( 'RebuildOS', 'rebuildos' ); ?></p>
		<h2 class="rebuildos-app__title"><?php echo esc_html__( 'Private Rebuild Dashboard', 'rebuildos' ); ?></h2>
		<p class="rebuildos-app__subtitle"><?php echo esc_html__( 'Track patterns, protect boundaries, and choose the next clean action.', 'rebuildos' ); ?></p>
		<p class="rebuildos-app__privacy-note"><?php echo esc_html__( 'Guest data is stored only in this browser unless you export it.', 'rebuildos' ); ?></p>
	</header>

	<nav class="rebuildos-tabs" aria-label="<?php echo esc_attr__( 'RebuildOS Sections', 'rebuildos' ); ?>">
		<ul class="rebuildos-tabs__list">
			<?php foreach ( $tabs as $tab_slug => $tab_label ) : ?>
				<li class="rebuildos-tabs__item">
					<button type="button" class="rebuildos-tabs__button<?php echo ( 'today' === $tab_slug ) ? ' is-active' : ''; ?>" data-rebuildos-tab="<?php echo esc_attr( $tab_slug ); ?>"><?php echo esc_html( $tab_label ); ?></button>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="rebuildos-panels">
		<section class="rebuildos-panel is-active" data-rebuildos-panel="today" aria-live="polite">
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Daily Rebuild Check-In', 'rebuildos' ); ?></h3>
			<p class="rebuildos-panel__text"><?php echo esc_html__( 'Today does not need to be perfect. It needs one clean action and one real boundary.', 'rebuildos' ); ?></p>
			<form class="rebuildos-form" data-rebuildos-form="today">
				<div class="rebuildos-grid rebuildos-grid--metrics">
					<label><?php echo esc_html__( 'Sleep Quality (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="sleepQuality" value="3" required></label>
					<label><?php echo esc_html__( 'Stress Level (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="stressLevel" value="3" required></label>
					<label><?php echo esc_html__( 'Loneliness Level (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="lonelinessLevel" value="3" required></label>
					<label><?php echo esc_html__( 'Boredom Level (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="boredomLevel" value="3" required></label>
					<label><?php echo esc_html__( 'Energy Level (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="energyLevel" value="3" required></label>
					<label><?php echo esc_html__( 'Screen Risk (1-5)', 'rebuildos' ); ?><input type="range" min="1" max="5" name="screenRisk" value="3" required></label>
				</div>
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'Highest Risk Window Today', 'rebuildos' ); ?>
						<select name="riskWindow" required>
							<option value="morning"><?php echo esc_html__( 'Morning', 'rebuildos' ); ?></option>
							<option value="afternoon"><?php echo esc_html__( 'Afternoon', 'rebuildos' ); ?></option>
							<option value="evening"><?php echo esc_html__( 'Evening', 'rebuildos' ); ?></option>
							<option value="late night"><?php echo esc_html__( 'Late Night', 'rebuildos' ); ?></option>
							<option value="custom"><?php echo esc_html__( 'Custom', 'rebuildos' ); ?></option>
						</select>
					</label>
					<label><?php echo esc_html__( 'Today’s Boundary', 'rebuildos' ); ?><input type="text" name="boundary" required></label>
					<label><?php echo esc_html__( 'Minimum Viable Day Action', 'rebuildos' ); ?><input type="text" name="minimumAction" required></label>
					<label><?php echo esc_html__( 'Notes (optional)', 'rebuildos' ); ?><textarea name="notes" rows="3"></textarea></label>
				</div>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Today Check-In', 'rebuildos' ); ?></button>
			</form>
			<div class="rebuildos-card" data-rebuildos-today-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="urge-log" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Urge Log', 'rebuildos' ); ?></h3>
			<p class="rebuildos-panel__text"><?php echo esc_html__( 'Log the moment as data, not shame.', 'rebuildos' ); ?></p>
			<form class="rebuildos-form" data-rebuildos-form="urge">
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'Urge Intensity (1-10)', 'rebuildos' ); ?><input type="range" min="1" max="10" name="intensity" value="5" required></label>
					<label><?php echo esc_html__( 'Trigger Emotion', 'rebuildos' ); ?>
						<select name="emotion" required>
							<option value="boredom">Boredom</option><option value="loneliness">Loneliness</option><option value="stress">Stress</option><option value="anger">Anger</option><option value="sadness">Sadness</option><option value="anxiety">Anxiety</option><option value="tiredness">Tiredness</option><option value="rejection">Rejection</option><option value="pressure">Pressure</option><option value="numbness">Numbness</option><option value="other">Other</option>
						</select>
					</label>
					<label><?php echo esc_html__( 'Trigger Context', 'rebuildos' ); ?>
						<select name="context" required>
							<option value="alone in room">Alone in room</option><option value="late night">Late night</option><option value="after scrolling">After scrolling</option><option value="after work">After work</option><option value="after argument">After argument</option><option value="after failure">After failure</option><option value="after boredom">After boredom</option><option value="after stress">After stress</option><option value="after triggering content">After triggering content</option><option value="other">Other</option>
						</select>
					</label>
					<label><?php echo esc_html__( 'Device', 'rebuildos' ); ?>
						<select name="device" required><option value="phone">Phone</option><option value="laptop">Laptop</option><option value="desktop">Desktop</option><option value="tablet">Tablet</option><option value="TV">TV</option><option value="other">Other</option></select>
					</label>
					<label><?php echo esc_html__( 'Location', 'rebuildos' ); ?><input type="text" name="location" required></label>
					<label><?php echo esc_html__( 'App/Site Before Urge', 'rebuildos' ); ?><input type="text" name="appBeforeUrge"></label>
					<label><?php echo esc_html__( 'First Small Compromise', 'rebuildos' ); ?><input type="text" name="firstCompromise"></label>
					<label><?php echo esc_html__( 'Response Action', 'rebuildos' ); ?>
						<select name="responseAction" required>
							<option value="left room">Left room</option><option value="cold water">Cold water</option><option value="walk">Walk</option><option value="body movement">Body movement</option><option value="wrote one sentence">Wrote one sentence</option><option value="messaged someone">Messaged someone</option><option value="cleaned one surface">Cleaned one surface</option><option value="read one page">Read one page</option><option value="other">Other</option>
						</select>
					</label>
					<label><?php echo esc_html__( 'Outcome', 'rebuildos' ); ?>
						<select name="outcome" required><option value="survived">Survived</option><option value="relapsed">Relapsed</option><option value="unresolved">Unresolved</option></select>
					</label>
					<label><?php echo esc_html__( 'Notes', 'rebuildos' ); ?><textarea name="notes" rows="3"></textarea></label>
				</div>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Urge Event', 'rebuildos' ); ?></button>
			</form>
			<div class="rebuildos-card" data-rebuildos-urge-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="emergency-reset" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Emergency Reset', 'rebuildos' ); ?></h3>
			<button type="button" class="rebuildos-btn rebuildos-btn--large" data-rebuildos-reset-start><?php echo esc_html__( 'I’m at risk', 'rebuildos' ); ?></button>
			<ol class="rebuildos-steps">
				<li><?php echo esc_html__( 'Step away from the device.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Name the urge.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Rate intensity.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Identify the lie.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Choose one replacement action.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Start a 12-minute timer.', 'rebuildos' ); ?></li>
				<li><?php echo esc_html__( 'Log outcome.', 'rebuildos' ); ?></li>
			</ol>
			<form class="rebuildos-form" data-rebuildos-form="reset">
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'Name the Urge', 'rebuildos' ); ?><input type="text" name="urgeName" required></label>
					<label><?php echo esc_html__( 'Intensity (1-10)', 'rebuildos' ); ?><input type="range" min="1" max="10" name="intensity" value="6" required></label>
					<label><?php echo esc_html__( 'Lie Identified', 'rebuildos' ); ?><input type="text" name="lie" required></label>
					<label><?php echo esc_html__( 'Replacement Action', 'rebuildos' ); ?>
						<select name="replacementAction" required><option value="leave the room">Leave the room</option><option value="cold water">Cold water</option><option value="short walk">Short walk</option><option value="push-ups or movement">Push-ups or movement</option><option value="write one honest sentence">Write one honest sentence</option><option value="message a trusted person">Message a trusted person</option><option value="clean one surface">Clean one surface</option><option value="read one page">Read one page</option><option value="make tea or water">Make tea or water</option><option value="sit outside">Sit outside</option><option value="complete one closed-loop task">Complete one closed-loop task</option></select>
					</label>
					<label><?php echo esc_html__( 'Outcome', 'rebuildos' ); ?><select name="outcome" required><option value="survived">Survived</option><option value="relapsed">Relapsed</option><option value="unresolved">Unresolved</option></select></label>
				</div>
				<div class="rebuildos-actions">
					<button type="button" class="rebuildos-btn rebuildos-btn--secondary" data-rebuildos-reset-timer><?php echo esc_html__( 'Start 12-Minute Timer', 'rebuildos' ); ?></button>
					<span class="rebuildos-timer" data-rebuildos-timer>12:00</span>
					<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Reset Outcome', 'rebuildos' ); ?></button>
				</div>
			</form>
			<div class="rebuildos-card" data-rebuildos-reset-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="relapse-autopsy" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Relapse Autopsy', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="autopsy">
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'What happened?', 'rebuildos' ); ?><textarea name="whatHappened" rows="2" required></textarea></label>
					<label><?php echo esc_html__( 'First small compromise', 'rebuildos' ); ?><input type="text" name="firstCompromise" required></label>
					<label><?php echo esc_html__( 'Boundary that failed', 'rebuildos' ); ?><input type="text" name="boundaryFailed" required></label>
					<label><?php echo esc_html__( 'Emotion avoided', 'rebuildos' ); ?><input type="text" name="emotionAvoided" required></label>
					<label><?php echo esc_html__( 'Lie believed', 'rebuildos' ); ?><input type="text" name="lieBelieved" required></label>
					<label><?php echo esc_html__( 'Replacement needed', 'rebuildos' ); ?><input type="text" name="replacementNeeded" required></label>
					<label><?php echo esc_html__( 'System change for today', 'rebuildos' ); ?><input type="text" name="systemChange" required></label>
					<label><?php echo esc_html__( 'Next clean action', 'rebuildos' ); ?><input type="text" name="nextCleanAction" required></label>
				</div>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Relapse Autopsy', 'rebuildos' ); ?></button>
			</form>
			<div class="rebuildos-card" data-rebuildos-autopsy-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="control-audit" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Control Audit', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="control">
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'What feels out of control right now?', 'rebuildos' ); ?><input type="text" name="outOfControl" required></label>
					<label><?php echo esc_html__( 'What has too much power today?', 'rebuildos' ); ?><input type="text" name="topDependency" required></label>
					<label><?php echo esc_html__( 'One controllable action within reach', 'rebuildos' ); ?><input type="text" name="controllableAction" required></label>
					<label><?php echo esc_html__( 'Boundary to set today', 'rebuildos' ); ?><input type="text" name="boundary" required></label>
					<label><?php echo esc_html__( 'Closed-loop task for today', 'rebuildos' ); ?><input type="text" name="closedLoopTask" required></label>
				</div>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Control Audit', 'rebuildos' ); ?></button>
			</form>
			<div class="rebuildos-card" data-rebuildos-control-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="dashboard" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Insight Dashboard', 'rebuildos' ); ?></h3>
			<div class="rebuildos-empty" data-rebuildos-dashboard-empty><?php echo esc_html__( 'Your dashboard will become useful after a few check-ins or urge logs.', 'rebuildos' ); ?></div>
			<div class="rebuildos-cards" data-rebuildos-dashboard-cards></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="weekly-review" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Weekly Rebuild Review', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="weekly">
				<div class="rebuildos-grid">
					<label><?php echo esc_html__( 'Biggest win', 'rebuildos' ); ?><input type="text" name="biggestWin" required></label>
					<label><?php echo esc_html__( 'Highest-risk moment', 'rebuildos' ); ?><input type="text" name="highRiskMoment" required></label>
					<label><?php echo esc_html__( 'Strongest trigger', 'rebuildos' ); ?><input type="text" name="strongestTrigger" required></label>
					<label><?php echo esc_html__( 'Best replacement action', 'rebuildos' ); ?><input type="text" name="bestReplacementAction" required></label>
					<label><?php echo esc_html__( 'Repeated pattern', 'rebuildos' ); ?><input type="text" name="repeatedPattern" required></label>
					<label><?php echo esc_html__( 'System weakness', 'rebuildos' ); ?><input type="text" name="systemWeakness" required></label>
					<label><?php echo esc_html__( 'Next boundary', 'rebuildos' ); ?><input type="text" name="nextBoundary" required></label>
					<label><?php echo esc_html__( 'One sentence review', 'rebuildos' ); ?><textarea name="sentenceReview" rows="2" required></textarea></label>
				</div>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Weekly Review', 'rebuildos' ); ?></button>
			</form>
			<div class="rebuildos-card" data-rebuildos-weekly-result></div>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="export" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Export & Privacy Controls', 'rebuildos' ); ?></h3>
			<p class="rebuildos-panel__text"><?php echo esc_html__( 'Guest data is stored only in this browser unless you export it.', 'rebuildos' ); ?></p>
			<div class="rebuildos-actions">
				<button type="button" class="rebuildos-btn" data-rebuildos-export-json><?php echo esc_html__( 'Export JSON', 'rebuildos' ); ?></button>
				<button type="button" class="rebuildos-btn rebuildos-btn--secondary" data-rebuildos-export-csv><?php echo esc_html__( 'Export CSV', 'rebuildos' ); ?></button>
				<label class="rebuildos-btn rebuildos-btn--secondary" for="rebuildos-import-file"><?php echo esc_html__( 'Import JSON', 'rebuildos' ); ?></label>
				<input id="rebuildos-import-file" type="file" accept="application/json" data-rebuildos-import-file />
				<button type="button" class="rebuildos-btn rebuildos-btn--danger" data-rebuildos-clear><?php echo esc_html__( 'Clear Local Data', 'rebuildos' ); ?></button>
			</div>
			<div class="rebuildos-cards" data-rebuildos-export-summary></div>
		</section>
	</div>

	<p class="rebuildos-feedback" data-rebuildos-feedback aria-live="polite"></p>

	<footer class="rebuildos-app__footer">
		<p class="rebuildos-app__disclaimer"><?php echo esc_html__( 'RebuildOS is a self-directed reflection and rebuilding tool. It is not therapy, medical advice, diagnosis, or crisis support. If you feel unable to control your behavior, feel unsafe, or are in severe distress, consider reaching out to a qualified professional, a trusted support person, or local emergency/crisis support.', 'rebuildos' ); ?></p>
	</footer>
</div>
