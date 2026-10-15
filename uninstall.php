<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * No data is removed during uninstall; saved settings are retained.
 *
 * @link       https://civicopilot.com
 * @since      0.1
 *
 * @package    Admin_Sidebar_Organizer_Wp
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
