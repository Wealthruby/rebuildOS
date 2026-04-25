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
	 * User meta key for persisted app data.
	 *
	 * @var string
	 */
	const USER_META_KEY = 'rebuildos_v1_data';

	/**
	 * Hook registrations.
	 *
	 * @return void
	 */
	public static function init() {
		add_shortcode( 'rebuild_os', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_assets' ) );

		add_action( 'wp_ajax_rebuildos_save_user_data', array( __CLASS__, 'ajax_save_user_data' ) );
		add_action( 'wp_ajax_rebuildos_load_user_data', array( __CLASS__, 'ajax_load_user_data' ) );
		add_action( 'wp_ajax_rebuildos_migrate_guest_data', array( __CLASS__, 'ajax_migrate_guest_data' ) );
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

		wp_localize_script(
			'rebuildos-script',
			'rebuildosConfig',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'rebuildos_user_data' ),
				'isLoggedIn' => is_user_logged_in(),
			)
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

	/**
	 * AJAX: load logged-in user data.
	 *
	 * @return void
	 */
	public static function ajax_load_user_data() {
		self::verify_ajax_request();

		$user_id = get_current_user_id();
		$data    = get_user_meta( $user_id, self::USER_META_KEY, true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}

		wp_send_json_success(
			array(
				'data' => self::normalize_data_shape( $data ),
			)
		);
	}

	/**
	 * AJAX: save logged-in user data.
	 *
	 * @return void
	 */
	public static function ajax_save_user_data() {
		self::verify_ajax_request();

		$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
		$decoded  = json_decode( (string) $raw_data, true );
		if ( ! is_array( $decoded ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid data payload.', 'rebuildos' ) ), 400 );
		}

		$sanitized = self::sanitize_data( $decoded );
		$normalized = self::normalize_data_shape( $sanitized );

		update_user_meta( get_current_user_id(), self::USER_META_KEY, $normalized );
		wp_send_json_success( array( 'message' => __( 'Saved.', 'rebuildos' ) ) );
	}

	/**
	 * AJAX: migrate guest data into account.
	 *
	 * @return void
	 */
	public static function ajax_migrate_guest_data() {
		self::verify_ajax_request();

		$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
		$decoded  = json_decode( (string) $raw_data, true );
		if ( ! is_array( $decoded ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid migration payload.', 'rebuildos' ) ), 400 );
		}

		$sanitized = self::sanitize_data( $decoded );
		$normalized = self::normalize_data_shape( $sanitized );

		update_user_meta( get_current_user_id(), self::USER_META_KEY, $normalized );
		wp_send_json_success( array( 'message' => __( 'Guest data migrated to your account.', 'rebuildos' ) ) );
	}

	/**
	 * Verify nonce/auth for ajax requests.
	 *
	 * @return void
	 */
	private static function verify_ajax_request() {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Authentication required.', 'rebuildos' ) ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rebuildos_user_data' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'rebuildos' ) ), 403 );
		}
	}

	/**
	 * Normalize data shape to required arrays.
	 *
	 * @param array<string,mixed> $data Input data.
	 * @return array<string,array>
	 */
	private static function normalize_data_shape( $data ) {
		$shape = array(
			'dailyCheckins'    => array(),
			'urgeLogs'         => array(),
			'relapseAutopsies' => array(),
			'controlAudits'    => array(),
			'closedLoopActions'=> array(),
			'weeklyReviews'    => array(),
		);

		foreach ( $shape as $key => $default ) {
			if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
				$shape[ $key ] = array_values( $data[ $key ] );
			}
		}

		return $shape;
	}

	/**
	 * Recursively sanitize user data payload.
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed
	 */
	private static function sanitize_data( $value ) {
		if ( is_array( $value ) ) {
			$sanitized = array();
			foreach ( $value as $key => $item ) {
				$safe_key = is_string( $key ) ? sanitize_key( $key ) : $key;
				$sanitized[ $safe_key ] = self::sanitize_data( $item );
			}
			return $sanitized;
		}

		if ( is_bool( $value ) || is_int( $value ) || is_float( $value ) ) {
			return $value;
		}

		if ( is_string( $value ) ) {
			return sanitize_text_field( $value );
		}

		return '';
	}
}
