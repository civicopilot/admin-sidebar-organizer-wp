<?php
/**
 * Meta-box settings for Admin Sidebar Organizer for WP.
 *
 * Follows CiviCRM Admin Utilities' network settings pattern: add_submenu_page,
 * load-page save routing, and native meta boxes. No CiviCRM dependency.
 * Reference: civicrm-admin-utilities/includes/civicrm-admin-utilities-multisite.php.
 *
 * @package Admin_Sidebar_Organizer_Wp
 * @since 0.1-alpha
 */

defined( 'ABSPATH' ) || exit;

class Admin_Sidebar_Organizer_Wp_Settings {
	const OPTION = 'aso_wp_settings';
	private $page_hook = '';

	/** Read shared settings, defaulting to the existing palette relocation behavior. */
	public static function move_command_palette() {
		$settings = is_multisite() ? get_network_option( null, self::OPTION, array() ) : get_option( self::OPTION, array() );
		return ! is_array( $settings ) || ! array_key_exists( 'move_command_palette', $settings ) || ! empty( $settings['move_command_palette'] );
	}

	/** Opt-in Dashboard flyout presentation; native behavior is the default. */
	public static function wp_dashboard_flyout() {
		$settings = is_multisite() ? get_network_option( null, self::OPTION, array() ) : get_option( self::OPTION, array() );
		return is_array( $settings ) && ! empty( $settings['wp_dashboard_flyout'] );
	}

	/** Opt-in CiviCRM flyout presentation. */
	public static function civicrm_menu_flyout() {
		$settings = is_multisite() ? get_network_option( null, self::OPTION, array() ) : get_option( self::OPTION, array() );
		return is_array( $settings ) && ! empty( $settings['civicrm_menu_flyout'] );
	}

	/** Match network/site settings permissions without allowing subsite overrides. */
	public static function can_manage() {
		return is_multisite()
			? is_super_admin() && current_user_can( 'manage_network_options' )
			: current_user_can( 'manage_options' );
	}

	/** Register only in Network Admin on multisite, or Settings on single sites. */
	public function admin_menu() {
		if ( is_multisite() && ! is_network_admin() ) {
			return;
		}
		if ( ! self::can_manage() ) {
			return;
		}
		$this->page_hook = add_submenu_page(
			is_multisite() ? 'settings.php' : 'options-general.php',
			__( 'Admin Sidebar Organizer', 'admin-sidebar-organizer-wp' ),
			__( 'Sidebar Organizer', 'admin-sidebar-organizer-wp' ),
			is_multisite() ? 'manage_network_options' : 'manage_options',
			'admin-sidebar-organizer-wp',
			array( $this, 'render_page' )
		);
		if ( $this->page_hook ) {
			add_action( 'load-' . $this->page_hook, array( $this, 'load_page' ) );
		}
	}

	/** Scope the save nonce to this installation/network. */
	private function nonce_action() {
		return 'aso_wp_save_settings_' . ( is_multisite() ? get_current_network_id() : get_current_blog_id() );
	}

	/** Route saves before output and register native meta-box controls. */
	public function load_page() {
		if ( ! self::can_manage() ) {
			wp_die( esc_html__( 'You do not have permission to manage these settings.', 'admin-sidebar-organizer-wp' ) );
		}
		if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
			check_admin_referer( $this->nonce_action(), 'aso_wp_settings_nonce' );
			$enabled = isset( $_POST['move_command_palette'] ) && '1' === wp_unslash( $_POST['move_command_palette'] );
			$settings = is_multisite() ? get_network_option( null, self::OPTION, array() ) : get_option( self::OPTION, array() );
			$settings = is_array( $settings ) ? $settings : array();
			$settings['move_command_palette'] = $enabled;
			$settings['wp_dashboard_flyout'] = isset( $_POST['wp_dashboard_flyout'] ) && '1' === wp_unslash( $_POST['wp_dashboard_flyout'] );
			if ( function_exists( 'civi_wp' ) ) {
				$settings['civicrm_menu_flyout'] = isset( $_POST['civicrm_menu_flyout'] ) && '1' === wp_unslash( $_POST['civicrm_menu_flyout'] );
			}
			if ( is_multisite() ) {
				update_network_option( null, self::OPTION, $settings );
			} else {
				update_option( self::OPTION, $settings, false );
			}
			$url = is_multisite() ? network_admin_url( 'settings.php?page=admin-sidebar-organizer-wp' ) : admin_url( 'options-general.php?page=admin-sidebar-organizer-wp' );
			wp_safe_redirect( add_query_arg( 'updated', '1', $url ) );
			exit;
		}
		add_screen_option( 'layout_columns', array( 'max' => 2, 'default' => 2 ) );
		add_meta_box( 'aso-wp-sidebar-options', __( 'Sidebar Options', 'admin-sidebar-organizer-wp' ), array( $this, 'render_options' ), $this->page_hook, 'normal', 'default' );
		add_meta_box( 'aso-wp-save', __( 'Save Settings', 'admin-sidebar-organizer-wp' ), array( $this, 'render_save' ), $this->page_hook, 'side', 'high' );
		wp_enqueue_script( 'postbox' );
		wp_add_inline_script( 'postbox', 'jQuery(function () { postboxes.add_postbox_toggles(' . wp_json_encode( $this->page_hook ) . '); });' );
	}

	/** Render a self-posting form using native WordPress postbox containers. */
	public function render_page() {
		if ( ! self::can_manage() ) {
			return;
		}
		$page_hook = $this->page_hook;
		$nonce_action = $this->nonce_action();
		$columns = 1 === (int) get_current_screen()->get_columns() ? '1' : '2';
		$configured = Admin_Sidebar_Organizer_Wp_Menu_Sections::has_configuration();
		$guide_url = 'https://github.com/civicopilot/admin-sidebar-organizer-wp/blob/main/docs/configuration.md';
		$example_path = dirname( __DIR__ ) . '/docs/examples/admin-sidebar-config.php';
		$example = is_readable( $example_path ) ? file_get_contents( $example_path ) : '';
		$example = false === $example ? '' : $example;
		include dirname( __DIR__ ) . '/admin/templates/settings-page.php';
	}

	/** Prepare sidebar options for their presentation template. */
	public function render_options() {
		$move_command_palette = self::move_command_palette();
		$wp_dashboard_flyout = self::wp_dashboard_flyout();
		$civicrm_active = function_exists( 'civi_wp' );
		$civicrm_menu_flyout = self::civicrm_menu_flyout();
		include dirname( __DIR__ ) . '/admin/templates/metabox-sidebar-options.php';
	}

	/** Render WordPress's standard submit control. */
	public function render_save() {
		submit_button( __( 'Save Settings', 'admin-sidebar-organizer-wp' ), 'primary', 'submit', false );
	}
}
