<?php
/** Editable sidebar options. @package Admin_Sidebar_Organizer_Wp */
defined( 'ABSPATH' ) || exit;
		?>
		<p><label for="aso-wp-move-command-palette">
			<input type="checkbox" id="aso-wp-move-command-palette" name="move_command_palette" value="1" <?php checked( $move_command_palette ); ?> />
			<?php esc_html_e( 'Move command palette to sidebar', 'admin-sidebar-organizer-wp' ); ?>
		</label></p>
		<p class="description"><?php
			esc_html_e( 'Show WordPress’s command palette as Go to… at the top of the sidebar.', 'admin-sidebar-organizer-wp' );
			if ( is_multisite() ) {
				echo ' ';
				esc_html_e( 'This setting applies to all sites in this network.', 'admin-sidebar-organizer-wp' );
			}
		?></p>

<p><label for="aso-wp-dashboard-flyout">
	<input type="checkbox" id="aso-wp-dashboard-flyout" name="dashboard_flyout" value="1" <?php checked( $dashboard_flyout ); ?> />
	<?php esc_html_e( 'Use a flyout for the WordPress Dashboard submenu', 'admin-sidebar-organizer-wp' ); ?>
</label></p>
<p class="description"><?php
	esc_html_e( 'Experimental: show Home, Updates, and other Dashboard links in a flyout instead of expanding them inline while viewing the main WordPress Dashboard.', 'admin-sidebar-organizer-wp' );
	if ( is_multisite() ) {
		echo ' ';
		esc_html_e( 'This setting applies to all sites in this network.', 'admin-sidebar-organizer-wp' );
	}
?></p>
