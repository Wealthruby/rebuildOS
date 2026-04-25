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

	<section class="rebuildos-panel" aria-live="polite">
		<h3 class="rebuildos-panel__title"><?php echo esc_html__( 'Welcome to your rebuild workspace', 'rebuildos' ); ?></h3>
		<p class="rebuildos-panel__text">
			<?php echo esc_html__( 'This is the Phase 1 shell. Functional forms and data storage are added in upcoming tasks.', 'rebuildos' ); ?>
		</p>
	</section>

	<footer class="rebuildos-app__footer">
		<p class="rebuildos-app__disclaimer">
			<?php
			echo esc_html__( 'RebuildOS is a self-directed reflection and rebuilding tool. It is not therapy, medical advice, diagnosis, or crisis support. If you feel unable to control your behavior, feel unsafe, or are in severe distress, consider reaching out to a qualified professional, a trusted support person, or local emergency/crisis support.', 'rebuildos' );
			?>
		</p>
	</footer>
</div>
