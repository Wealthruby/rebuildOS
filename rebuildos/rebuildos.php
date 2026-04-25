<?php
/**
 * Plugin Name: RebuildOS
 * Plugin URI: https://rebuildwithintention.com/
 * Description: Private, self-directed rebuilding dashboard via shortcode [rebuild_os].
 * Version: 0.1.0
 * Author: Rebuild With Intention
 * Text Domain: rebuildos
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package RebuildOS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'REBUILDOS_VERSION', '0.1.0' );
define( 'REBUILDOS_PLUGIN_FILE', __FILE__ );
define( 'REBUILDOS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'REBUILDOS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once REBUILDOS_PLUGIN_DIR . 'includes/class-rebuildos-shortcode.php';
require_once REBUILDOS_PLUGIN_DIR . 'includes/class-rebuildos-admin.php';

/**
 * Initialize plugin bootstrap.
 *
 * @return void
 */
function rebuildos_init() {
	RebuildOS_Shortcode::init();
	RebuildOS_Admin::init();
}
add_action( 'plugins_loaded', 'rebuildos_init' );
