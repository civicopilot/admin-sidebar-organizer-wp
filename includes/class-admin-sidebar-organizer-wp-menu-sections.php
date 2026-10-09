<?php
/**
 * Group the current user's existing site-admin menu without changing access.
 *
 * @package Admin_Sidebar_Organizer_Wp
 */

defined( 'ABSPATH' ) || exit;

/** Discover and group the native menu without changing permissions. */
class Admin_Sidebar_Organizer_Wp_Menu_Sections {

	/** Detect a configuration callback independently of screen and section contents. */
	public static function has_configuration() {
		return false !== has_filter( 'admin_sidebar_organizer_wp/admin_menu_sections' );
	}

	/** Retrieve network-supplied sections; an empty configuration disables grouping. */
	public static function get_sections() {
		if ( is_network_admin() || is_user_admin() ) {
			return array();
		}
		return apply_filters( 'admin_sidebar_organizer_wp/admin_menu_sections', array() );
	}

	/**
	 * Publish only items left after core and User Role Editor's admin_head filtering.
	 * Menu hook IDs match the IDs emitted by WordPress's menu-header.php.
	 */
	public function output_data() {
		global $menu, $_wp_real_parent_file;

		$sections = self::get_sections();
		if ( empty( $sections ) ) {
			return;
		}
		$items = array();
		foreach ( $menu as $item ) {
			if ( empty( $item[5] ) || false !== strpos( $item[4], 'wp-menu-separator' ) ) {
				continue;
			}
			$aliases = array( $item[2] );
			// URE can replace the destination while retaining the original hook ID.
			if ( 0 === strpos( $item[5], 'toplevel_page_' ) ) {
				$aliases[] = substr( $item[5], strlen( 'toplevel_page_' ) );
			}
			// Core can replace a parent slug with its first accessible submenu.
			foreach ( (array) $_wp_real_parent_file as $original => $replacement ) {
				if ( $replacement === $item[2] ) {
					$aliases[] = $original;
				}
			}
			$items[] = array(
				'id'    => preg_replace( '|[^a-zA-Z0-9_:.]|', '-', $item[5] ),
				'slugs' => $aliases,
			);
		}
		$data = array(
			'sections' => $sections,
			'items'    => $items,
			'excluded' => apply_filters( 'admin_sidebar_organizer_wp/admin_menu_excluded', array() ),
			'other'    => __( 'See More', 'admin-sidebar-organizer-wp' ),
			'site'     => get_current_blog_id(),
			'user'     => get_current_user_id(),
		);
		wp_print_inline_script_tag(
			'window.asoWpMenuSections = ' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '; if (window.asoWpInitMenuSections) { window.asoWpInitMenuSections(); }'
		);
	}
}
