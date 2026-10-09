<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://civicopilot.com
 * @since      0.1-alpha
 *
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Loads sidebar assets and the native command-palette relocation for site admin.
 *
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/admin
 * @author     Andy Burns <andy@civicopilot.com>
 */
class ASO_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1-alpha
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/** @var string Plugin release version used for asset cache busting. */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1-alpha
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version           The plugin release version.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1-alpha
	 */
	public function enqueue_styles() {
		if ( is_blog_admin() && ( Admin_Sidebar_Organizer_Wp_Settings::wp_dashboard_flyout_items() ||
			( function_exists( 'civi_wp' ) && Admin_Sidebar_Organizer_Wp_Settings::civicrm_dashboard_flyout_items() ) ) ) {
			wp_enqueue_style( 'aso-wp-dashboard-flyout', plugin_dir_url( __FILE__ ) . 'css/dashboard-flyout.css', array(), $this->version );
		}
		if ( ! Admin_Sidebar_Organizer_Wp_Menu_Sections::get_sections() ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin-sidebar-organizer-wp-admin.css', array(), $this->version );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1-alpha
	 */
	public function enqueue_scripts() {
		if ( ! Admin_Sidebar_Organizer_Wp_Menu_Sections::get_sections() ) {
			return;
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin-sidebar-organizer-wp-admin.js', array( 'jquery' ), $this->version, false );
	}

	/** Relocate the native command palette when WordPress provides it. */
	public function enqueue_menu_search() {
		if ( ! Admin_Sidebar_Organizer_Wp_Settings::move_command_palette() || ! is_blog_admin() || ! wp_script_is( 'wp-core-commands', 'enqueued' ) ) {
			return;
		}
		wp_enqueue_script(
			'aso-wp-menu-search',
			plugin_dir_url( __FILE__ ) . 'js/menu-search.js',
			array( 'wp-dom-ready', 'wp-i18n', 'wp-core-commands' ),
			$this->version,
			true
		);
		wp_set_script_translations( 'aso-wp-menu-search', 'admin-sidebar-organizer-wp', dirname( __DIR__ ) . '/languages' );
	}

	/** Show the setup reminder outside Settings, only where it can be resolved. */
	public function configuration_notice() {
		if ( ! Admin_Sidebar_Organizer_Wp_Settings::can_manage() || is_user_admin() || ( is_multisite() && ! is_network_admin() ) ) {
			return;
		}
		if ( Admin_Sidebar_Organizer_Wp_Menu_Sections::has_configuration() ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || in_array( $screen->id, array( 'settings_page_admin-sidebar-organizer-wp', 'settings_page_admin-sidebar-organizer-wp-network' ), true ) ) {
			return;
		}
		$url = is_multisite() ? network_admin_url( 'settings.php?page=admin-sidebar-organizer-wp' ) : admin_url( 'options-general.php?page=admin-sidebar-organizer-wp' );
		?>
		<div class="notice notice-warning"><p>
			<?php esc_html_e( 'Admin Sidebar Organizer needs your attention. Section configuration has not been supplied.', 'admin-sidebar-organizer-wp' ); ?>
			<a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Visit Settings for setup instructions.', 'admin-sidebar-organizer-wp' ); ?></a>
		</p></div>
		<?php
	}

	/** Scope portable colors so network-specific schemes can override them. */
	public function body_class( $classes ) {
		if ( Admin_Sidebar_Organizer_Wp_Menu_Sections::get_sections() ) {
			$classes .= ' aso-sidebar-enabled';
		}
		if ( is_blog_admin() && Admin_Sidebar_Organizer_Wp_Settings::wp_dashboard_flyout_items() ) {
			$classes .= ' aso-wp-dashboard-flyout';
		}
		if ( is_blog_admin() && function_exists( 'civi_wp' ) && Admin_Sidebar_Organizer_Wp_Settings::civicrm_dashboard_flyout_items() ) {
			$classes .= ' aso-civicrm-dashboard-flyout';
		}
		return $classes;
	}
}
