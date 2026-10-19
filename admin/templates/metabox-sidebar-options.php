<?php
/** Editable sidebar options. @package Admin_Sidebar_Organizer_Wp */
defined( 'ABSPATH' ) || exit;
?>
<?php if ( is_multisite() ) : ?>
<p class="description"><?php esc_html_e( 'These settings apply to all sites in this network.', 'admin-sidebar-organizer-wp' ); ?></p>
<?php endif; ?>

		<p><label for="aso-move-command-palette">
			<input type="checkbox" id="aso-move-command-palette" name="move_command_palette" value="1" <?php checked( $move_command_palette ); ?> />
			<?php esc_html_e( 'Move command palette to sidebar', 'admin-sidebar-organizer-wp' ); ?>
		</label></p>
		<p class="description"><?php
			esc_html_e( 'Show WordPress’s command palette as Go to… at the top of the sidebar.', 'admin-sidebar-organizer-wp' );
		?></p>

<p><label for="aso-standalone-menu-flyout">
	<input type="checkbox" id="aso-standalone-menu-flyout" name="standalone_menu_flyout" value="1" <?php checked( $standalone_menu_flyout ); ?> />
	<?php esc_html_e( 'Use flyouts for standalone menu items', 'admin-sidebar-organizer-wp' ); ?>
</label></p>
<p class="description"><?php
	esc_html_e( 'Show submenus as flyouts instead of inline on desktop.', 'admin-sidebar-organizer-wp' );
?></p>

<p><label for="aso-section-menu-flyout">
	<input type="checkbox" id="aso-section-menu-flyout" name="section_menu_flyout" value="1" <?php checked( $section_menu_flyout ); ?> />
	<?php esc_html_e( 'Use flyouts for menu items within sections', 'admin-sidebar-organizer-wp' ); ?>
</label></p>
<p class="description"><?php
	esc_html_e( 'Show submenus as flyouts instead of inline on desktop for the most compact sidebar layout.', 'admin-sidebar-organizer-wp' );
?></p>
