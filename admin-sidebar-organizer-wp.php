<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * defines a function that starts the plugin.
 *
 * @link              https://civicopilot.com
 * @since             0.1-alpha
 * @package           Admin_Sidebar_Organizer_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Sidebar Organizer for WP
 * Plugin URI:        https://github.com/civicopilot/admin-sidebar-organizer-wp
 * Description:       Organizes the WordPress admin sidebar into configurable, collapsible sections. Supports centralized multisite configuration.
 * Version:           0.1-alpha
 * Author:            Andy Burns
 * Author URI:        https://civicopilot.com/
 * License:           GPL-3.0-only
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       admin-sidebar-organizer-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Update this value when releasing a new version.
 */
define( 'ADMIN_SIDEBAR_ORGANIZER_WP_VERSION', '0.1-alpha' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-admin-sidebar-organizer-wp-activator.php
 */
function activate_admin_sidebar_organizer_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-sidebar-organizer-wp-activator.php';
	Admin_Sidebar_Organizer_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-admin-sidebar-organizer-wp-deactivator.php
 */
function deactivate_admin_sidebar_organizer_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-sidebar-organizer-wp-deactivator.php';
	Admin_Sidebar_Organizer_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_admin_sidebar_organizer_wp' );
register_deactivation_hook( __FILE__, 'deactivate_admin_sidebar_organizer_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin assets, menu sections, and settings hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-admin-sidebar-organizer-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1-alpha
 */
function run_admin_sidebar_organizer_wp() {

	$plugin = new Admin_Sidebar_Organizer_Wp();
	$plugin->run();

}
run_admin_sidebar_organizer_wp();
