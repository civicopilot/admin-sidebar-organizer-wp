<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://civicopilot.com
 * @since      0.1-alpha
 *
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1-alpha
 * @package    Admin_Sidebar_Organizer_Wp
 * @subpackage Admin_Sidebar_Organizer_Wp/includes
 * @author     Andy Burns <andy@civicopilot.com>
 */
class Admin_Sidebar_Organizer_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1-alpha
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'admin-sidebar-organizer-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
