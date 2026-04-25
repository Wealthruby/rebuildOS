<?php
/**
 * Admin class for RebuildOS settings.
 *
 * @package RebuildOS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RebuildOS admin bootstrap.
 */
class RebuildOS_Admin {

	/**
	 * Option name for settings storage.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'rebuildos_settings';

	/**
	 * Default disclaimer text.
	 *
	 * @var string
	 */
	const DEFAULT_DISCLAIMER = 'RebuildOS is a self-directed reflection and rebuilding tool. It is not therapy, medical advice, diagnosis, or crisis support. If you feel unable to control your behavior, feel unsafe, or are in severe distress, consider reaching out to a qualified professional, a trusted support person, or local emergency/crisis support.';

	/**
	 * Hook registrations.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Register options page.
	 *
	 * @return void
	 */
	public static function register_menu() {
		add_options_page(
			__( 'RebuildOS Settings', 'rebuildos' ),
			__( 'RebuildOS', 'rebuildos' ),
			'manage_options',
			'rebuildos-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Register setting.
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting(
			'rebuildos_settings_group',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
				'default'           => self::get_defaults(),
			)
		);
	}

	/**
	 * Defaults for settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_defaults() {
		return array(
			'show_disclaimer' => 1,
			'disclaimer_text' => self::DEFAULT_DISCLAIMER,
			'accent_style'    => 'warm',
			'guest_mode'      => 1,
			'export_enabled'  => 1,
		);
	}

	/**
	 * Retrieve merged settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_settings() {
		$stored = get_option( self::OPTION_NAME, array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		return wp_parse_args( $stored, self::get_defaults() );
	}

	/**
	 * Sanitize settings input.
	 *
	 * @param mixed $input Raw input.
	 * @return array<string,mixed>
	 */
	public static function sanitize_settings( $input ) {
		$defaults = self::get_defaults();
		$input    = is_array( $input ) ? $input : array();

		$sanitized = array();

		$sanitized['show_disclaimer'] = ! empty( $input['show_disclaimer'] ) ? 1 : 0;
		$sanitized['guest_mode']      = ! empty( $input['guest_mode'] ) ? 1 : 0;
		$sanitized['export_enabled']  = ! empty( $input['export_enabled'] ) ? 1 : 0;

		$sanitized['disclaimer_text'] = isset( $input['disclaimer_text'] )
			? sanitize_textarea_field( (string) $input['disclaimer_text'] )
			: $defaults['disclaimer_text'];

		$allowed_accents = array( 'warm', 'calm', 'stone' );
		$accent          = isset( $input['accent_style'] ) ? sanitize_key( (string) $input['accent_style'] ) : $defaults['accent_style'];
		$sanitized['accent_style'] = in_array( $accent, $allowed_accents, true ) ? $accent : $defaults['accent_style'];

		return $sanitized;
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = self::get_settings();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'RebuildOS Settings', 'rebuildos' ); ?></h1>
			<p><?php echo esc_html__( 'Configure privacy and display defaults for the RebuildOS shortcode app.', 'rebuildos' ); ?></p>

			<form action="options.php" method="post">
				<?php settings_fields( 'rebuildos_settings_group' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php echo esc_html__( 'Display disclaimer', 'rebuildos' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[show_disclaimer]" value="1" <?php checked( 1, (int) $settings['show_disclaimer'] ); ?> />
								<?php echo esc_html__( 'Show disclaimer in app footer', 'rebuildos' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Disclaimer text', 'rebuildos' ); ?></th>
						<td>
							<textarea name="<?php echo esc_attr( self::OPTION_NAME ); ?>[disclaimer_text]" rows="5" class="large-text"><?php echo esc_textarea( (string) $settings['disclaimer_text'] ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Accent style', 'rebuildos' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[accent_style]">
								<option value="warm" <?php selected( 'warm', (string) $settings['accent_style'] ); ?>><?php echo esc_html__( 'Warm', 'rebuildos' ); ?></option>
								<option value="calm" <?php selected( 'calm', (string) $settings['accent_style'] ); ?>><?php echo esc_html__( 'Calm', 'rebuildos' ); ?></option>
								<option value="stone" <?php selected( 'stone', (string) $settings['accent_style'] ); ?>><?php echo esc_html__( 'Stone', 'rebuildos' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Guest mode', 'rebuildos' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[guest_mode]" value="1" <?php checked( 1, (int) $settings['guest_mode'] ); ?> />
								<?php echo esc_html__( 'Enable guest local browser mode', 'rebuildos' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Export tools', 'rebuildos' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[export_enabled]" value="1" <?php checked( 1, (int) $settings['export_enabled'] ); ?> />
								<?php echo esc_html__( 'Enable Export tab and import/export actions', 'rebuildos' ); ?>
							</label>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
