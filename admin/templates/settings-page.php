<?php
/** Settings form and read-only configuration guidance. @package Admin_Sidebar_Organizer_Wp */
defined( 'ABSPATH' ) || exit;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Admin Sidebar Organizer', 'admin-sidebar-organizer-wp' ); ?></h1>
			<?php if ( isset( $_GET['updated'] ) && '1' === $_GET['updated'] ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'admin-sidebar-organizer-wp' ); ?></p></div>
			<?php endif; ?>
				<?php if ( ! $configured ) : ?>
					<div class="notice notice-warning"><p><?php esc_html_e( 'No section configuration has been supplied. Section grouping is inactive. See Section Configuration below for setup instructions.', 'admin-sidebar-organizer-wp' ); ?></p></div>
				<?php endif; ?>
			<form method="post">
				<?php
				wp_nonce_field( $nonce_action, 'aso_wp_settings_nonce' );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-<?php echo $columns; ?>">
						<div id="postbox-container-1" class="postbox-container"><?php do_meta_boxes( $page_hook, 'side', null ); ?></div>
						<div id="postbox-container-2" class="postbox-container"><?php do_meta_boxes( $page_hook, 'normal', null ); ?></div>
					</div>
					<br class="clear" />
				</div>
			</form>
			<hr />
			<section aria-labelledby="aso-wp-section-configuration">
				<h2 id="aso-wp-section-configuration"><?php esc_html_e( 'Section Configuration', 'admin-sidebar-organizer-wp' ); ?></h2>
					<?php if ( $configured ) : ?>
					<p><?php esc_html_e( 'Section configuration has been supplied.', 'admin-sidebar-organizer-wp' ); ?></p>
					<?php endif; ?>
				<p><?php esc_html_e( 'Copy the bundled example to wp-content/mu-plugins/admin-sidebar-config.php and edit it to define your sections. Create the mu-plugins directory if needed.', 'admin-sidebar-organizer-wp' ); ?></p>
				<p><a href="<?php echo esc_url( $guide_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Read the configuration guide', 'admin-sidebar-organizer-wp' ); ?></a></p>
				<?php if ( '' !== $example ) : ?>
					<details <?php echo ! $configured ? 'open' : ''; ?>>
						<summary><?php esc_html_e( 'View example configuration', 'admin-sidebar-organizer-wp' ); ?></summary>
						<p><label for="aso-wp-example-config"><?php esc_html_e( 'Bundled example — not your installed configuration', 'admin-sidebar-organizer-wp' ); ?></label></p>
						<textarea id="aso-wp-example-config" class="large-text code" rows="20" readonly spellcheck="false"><?php echo esc_textarea( $example ); ?></textarea>
					</details>
				<?php endif; ?>
			</section>
		</div>
		<?php
