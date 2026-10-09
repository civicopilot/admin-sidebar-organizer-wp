# Configuration

## Install MU-plugin

1. Install this directory as `wp-content/plugins/admin-sidebar-organizer-wp/`.
2. Activate **Admin Sidebar Organizer for WP**. On multisite, network-activate it.
3. Copy [examples/admin-sidebar-config.php](examples/admin-sidebar-config.php) directly to `wp-content/mu-plugins/admin-sidebar-config.php`.
4. Edit the copied configuration to supply your section labels, icons, and menu slugs.

Deactivating the organizer restores native navigation; the configuration file can remain in place harmlessly.

## Define sections

Use `admin_sidebar_organizer_wp/admin_menu_sections` to return an ordered map of section IDs. Each section contains:

| Key | Value |
| --- | --- |
| `label` | Visible section name. |
| `icon` | Optional Dashicon class, such as `dashicons-admin-post`. |
| `items` | Ordered array of menu slugs to place in the section. |

For example, in your configuration MU-plugin:

```php
<?php
/** Plugin Name: Admin Sidebar Configuration */

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
```

## Define standalone items

Add this to the same configuration file:

```php
/** Keep Dashboard standalone above the sections. */
function aso_wp_config_excluded( $excluded ) {
	$excluded[] = 'index.php';
	return $excluded;
}
add_filter( 'admin_sidebar_organizer_wp/admin_menu_excluded', 'aso_wp_config_excluded' );
```

The exclusion filter keeps matching items in their native relative order above the sections. Unassigned items appear under **See More** in their native relative order. An item listed in multiple sections belongs to the first matching section; please list each item once.

## Initial section state

Sections start collapsed in a new browser tab, except the section containing the current page. Subsequent toggles are remembered per site/user for that tab. 

## Command Palette

Command-palette relocation can be moved to the sidebar on the Sidebar Organizer settings page.
