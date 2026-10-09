<?php

/**
 * The file that defines the core plugin class
 *
 * Loads and registers admin sidebar, settings, and translation features.
 *
 * @link       https://civicopilot.com
 * @since      0.1
 *
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and settings hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/includes
 * @author     Andy Burns <andy@civicopilot.com>
 */
class Admin_Sidebar_Organizer_Wp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      Admin_Sidebar_Organizer_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area.
	 *
	 * @since    0.1
	 */
	public function __construct() {
		if ( defined( 'ADMIN_SIDEBAR_ORGANIZER_WP_VERSION' ) ) {
			$this->version = ADMIN_SIDEBAR_ORGANIZER_WP_VERSION;
		} else {
			$this->version = '0.1';
		}
		$this->plugin_name = 'admin-sidebar-organizer-wp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Admin_Sidebar_Organizer_Wp_Loader. Orchestrates the hooks of the plugin.
	 * - Admin_Sidebar_Organizer_Wp_i18n. Defines internationalization functionality.
	 * - ASO_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-sidebar-organizer-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-sidebar-organizer-wp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aso-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-sidebar-organizer-wp-menu-sections.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-sidebar-organizer-wp-settings.php';

		$this->loader = new Admin_Sidebar_Organizer_Wp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Admin_Sidebar_Organizer_Wp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Admin_Sidebar_Organizer_Wp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$settings = new Admin_Sidebar_Organizer_Wp_Settings();
		$this->loader->add_action( 'admin_menu', $settings, 'admin_menu' );
		$this->loader->add_action( 'network_admin_menu', $settings, 'admin_menu' );

		$menu_sections = new Admin_Sidebar_Organizer_Wp_Menu_Sections();
		$this->loader->add_action( 'adminmenu', $menu_sections, 'output_data', 20 );

		$plugin_admin = new ASO_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'admin_body_class', $plugin_admin, 'body_class' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'configuration_notice' );
		$this->loader->add_action( 'network_admin_notices', $plugin_admin, 'configuration_notice' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_menu_search', 20 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1
	 * @return    Admin_Sidebar_Organizer_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
