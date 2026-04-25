<?php
/**
 * Shortcode handling for RebuildOS.
 *
 * @package RebuildOS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RebuildOS shortcode class.
 */
class RebuildOS_Shortcode {

	/**
	 * Hook registrations.
	 *
	 * @return void
	 */
	public static function init() {
		add_shortcode( 'rebuild_os', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_assets' ) );
	}

	/**
	 * Enqueue front-end assets only when shortcode is detected.
	 *
	 * @return void
	 */
	public static function maybe_enqueue_assets() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;

		if ( empty( $post ) || ! isset( $post->post_content ) ) {
			return;
		}

		if ( ! has_shortcode( $post->post_content, 'rebuild_os' ) ) {
			return;
		}

		wp_enqueue_style(
			'rebuildos-style',
			REBUILDOS_PLUGIN_URL . 'assets/css/rebuildos.css',
			array(),
			REBUILDOS_VERSION
		);

		wp_enqueue_script(
			'rebuildos-script',
			REBUILDOS_PLUGIN_URL . 'assets/js/rebuildos.js',
			array(),
			REBUILDOS_VERSION,
			true
		);
	}

	/**
	 * Render shortcode output.
	 *
	 * @return string
	 */
	public static function render_shortcode() {
		ob_start();
		include REBUILDOS_PLUGIN_DIR . 'templates/app-shell.php';
		return (string) ob_get_clean();
	}
}
