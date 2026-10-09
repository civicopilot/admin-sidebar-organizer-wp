<?php
/**
 * Plugin Name: Admin Sidebar Configuration
 * Description: Example central configuration for Admin Sidebar Organizer for WP.
 *
 * Copy this file directly into wp-content/mu-plugins/admin-sidebar-config.php,
 * then edit section labels and menu slugs for your installation.
 * The organizer remains a regular plugin; network activate it on multisite.
 * This example is not loaded from the examples directory.
 */

defined( 'ABSPATH' ) || exit;

/** Supply this installation's section order and menu assignments. */
function aso_wp_config_sections( $sections ) {
	return array(
		'content' => array(
			'label' => __( 'Content', 'admin-sidebar-organizer-wp' ),
			'icon'  => 'dashicons-admin-post',
			'items' => array( 'edit.php', 'upload.php', 'edit.php?post_type=page', 'edit-comments.php' ),
		),
		'design' => array(
			'label' => __( 'Design', 'admin-sidebar-organizer-wp' ),
			'icon'  => 'dashicons-art',
			'items' => array( 'themes.php' ),
		),
		'utilities' => array(
			'label' => __( 'Utilities', 'admin-sidebar-organizer-wp' ),
			'icon'  => 'dashicons-admin-tools',
			'items' => array( 'users.php', 'profile.php', 'plugins.php', 'tools.php', 'options-general.php' ),
		),
	);
}
add_filter( 'admin_sidebar_organizer_wp/admin_menu_sections', 'aso_wp_config_sections' );

/** Keep Dashboard standalone above the sections. */
function aso_wp_config_excluded( $excluded ) {
	$excluded[] = 'index.php';
	return $excluded;
}
add_filter( 'admin_sidebar_organizer_wp/admin_menu_excluded', 'aso_wp_config_excluded' );
