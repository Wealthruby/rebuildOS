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
		<p class="rebuildos-app__subtitle">
			<?php echo esc_html__( 'One clean action, one real boundary, one practical system adjustment.', 'rebuildos' ); ?>
		</p>
		<p class="rebuildos-app__privacy-note">
			<?php echo esc_html__( 'Guest data is stored only in this browser unless you export it.', 'rebuildos' ); ?>
		</p>
	</header>

	<nav class="rebuildos-tabs" aria-label="<?php echo esc_attr__( 'RebuildOS Sections', 'rebuildos' ); ?>">
		<ul class="rebuildos-tabs__list">
			<?php foreach ( $tabs as $tab_slug => $tab_label ) : ?>
				<li class="rebuildos-tabs__item">
					<button
						type="button"
						class="rebuildos-tabs__button<?php echo ( 'today' === $tab_slug ) ? ' is-active' : ''; ?>"
						data-rebuildos-tab="<?php echo esc_attr( $tab_slug ); ?>"
					>
						<?php echo esc_html( $tab_label ); ?>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="rebuildos-panels">
		<section class="rebuildos-panel is-active" data-rebuildos-panel="today" aria-live="polite">
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Today Check-In (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="dailyCheckins">
				<label for="rebuildos-today-note"><?php echo esc_html__( 'Today note', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-today-note" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Check-In', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="urge-log" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Urge Log (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="urgeLogs">
				<label for="rebuildos-urge-note"><?php echo esc_html__( 'Urge context', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-urge-note" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Urge Log', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="emergency-reset" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Emergency Reset (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="closedLoopActions">
				<label for="rebuildos-reset-action"><?php echo esc_html__( 'Next clean action', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-reset-action" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Reset Action', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="relapse-autopsy" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Relapse Autopsy (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="relapseAutopsies">
				<label for="rebuildos-autopsy-note"><?php echo esc_html__( 'System insight', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-autopsy-note" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Autopsy Note', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="control-audit" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Control Audit (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="controlAudits">
				<label for="rebuildos-control-note"><?php echo esc_html__( 'One controllable action', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-control-note" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Control Audit', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="dashboard" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Guest Data Snapshot', 'rebuildos' ); ?></h3>
			<ul class="rebuildos-stats" data-rebuildos-stats></ul>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="weekly-review" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Weekly Review (Quick Save)', 'rebuildos' ); ?></h3>
			<form class="rebuildos-form" data-rebuildos-form="weeklyReviews">
				<label for="rebuildos-weekly-note"><?php echo esc_html__( 'Weekly reflection', 'rebuildos' ); ?></label>
				<textarea id="rebuildos-weekly-note" name="note" rows="3" required></textarea>
				<button type="submit" class="rebuildos-btn"><?php echo esc_html__( 'Save Weekly Review', 'rebuildos' ); ?></button>
			</form>
		</section>

		<section class="rebuildos-panel" data-rebuildos-panel="export" hidden>
			<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Local Data Tools', 'rebuildos' ); ?></h3>
			<div class="rebuildos-actions">
				<button type="button" class="rebuildos-btn" data-rebuildos-export><?php echo esc_html__( 'Export JSON', 'rebuildos' ); ?></button>
				<label class="rebuildos-btn rebuildos-btn--secondary" for="rebuildos-import-file">
					<?php echo esc_html__( 'Import JSON', 'rebuildos' ); ?>
				</label>
				<input id="rebuildos-import-file" type="file" accept="application/json" data-rebuildos-import-file />
				<button type="button" class="rebuildos-btn rebuildos-btn--danger" data-rebuildos-clear><?php echo esc_html__( 'Clear Local Data', 'rebuildos' ); ?></button>
			</div>
			<p class="rebuildos-panel__text"><?php echo esc_html__( 'Use import with care. It will overwrite current browser data after confirmation.', 'rebuildos' ); ?></p>
		</section>
	</div>

	<p class="rebuildos-feedback" data-rebuildos-feedback aria-live="polite"></p>

	<footer class="rebuildos-app__footer">
		<p class="rebuildos-app__disclaimer">
			<?php
			echo esc_html__( 'RebuildOS is a self-directed reflection and rebuilding tool. It is not therapy, medical advice, diagnosis, or crisis support. If you feel unable to control your behavior, feel unsafe, or are in severe distress, consider reaching out to a qualified professional, a trusted support person, or local emergency/crisis support.', 'rebuildos' );
			?>
		</p>
	</footer>
</div>
