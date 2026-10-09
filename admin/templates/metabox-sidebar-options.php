<?php
/** Editable sidebar options. @package Admin_Sidebar_Organizer_Wp */
defined( 'ABSPATH' ) || exit;
?>
<?php if ( is_multisite() ) : ?>
<p class="description"><?php esc_html_e( 'These settings apply to all sites in this network.', 'admin-sidebar-organizer-wp' ); ?></p>
<?php endif; ?>

		<p><label for="aso-wp-move-command-palette">
			<input type="checkbox" id="aso-wp-move-command-palette" name="move_command_palette" value="1" <?php checked( $move_command_palette ); ?> />
			<?php esc_html_e( 'Move command palette to sidebar', 'admin-sidebar-organizer-wp' ); ?>
		</label></p>
		<p class="description"><?php
			esc_html_e( 'Show WordPress’s command palette as Go to… at the top of the sidebar.', 'admin-sidebar-organizer-wp' );
		?></p>

<p><label for="aso-wp-dashboard-flyout">
	<input type="checkbox" id="aso-wp-dashboard-flyout" name="wp_dashboard_flyout" value="1" <?php checked( $wp_dashboard_flyout ); ?> />
	<?php esc_html_e( 'Use a flyout for the WordPress Dashboard submenu', 'admin-sidebar-organizer-wp' ); ?>
</label></p>
<p class="description"><?php
	esc_html_e( 'Experimental: show Home, Updates, and other Dashboard links in a flyout instead of expanding them inline while viewing the main WordPress Dashboard.', 'admin-sidebar-organizer-wp' );
?></p>

<?php if ( $civicrm_active ) : ?>
<p><label for="aso-civicrm-dashboard-flyout">
	<input type="checkbox" id="aso-civicrm-dashboard-flyout" name="civicrm_menu_flyout" value="1" <?php checked( $civicrm_menu_flyout ); ?> />
	<?php esc_html_e( 'Use a flyout for the CiviCRM submenu', 'admin-sidebar-organizer-wp' ); ?>
</label></p>
<p class="description"><?php
	esc_html_e( 'Experimental: show CiviCRM and its integration links in a flyout instead of expanding them inline while viewing CiviCRM pages.', 'admin-sidebar-organizer-wp' );
?></p>
<?php endif; ?>
