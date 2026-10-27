# Developer Guide

## Customize your sidebar

Change section labels, Dashicons, order, and menu assignments directly in
`wp-content/mu-plugins/admin-sidebar-config.php`.

Styling means colors, spacing, font appearance, and icon size. Change these with admin CSS through your preferred plugin or color scheme.

| Want to change… | Where to change it |
| --- | --- |
| Section names and icons | Edit `label` and `icon` in your configuration MU-plugin; no CSS needed. |
| Section order | Reorder the section entries in the returned array. |
| Item order or membership | Reorder or move menu slugs in each section's `items` array. |
| Standalone items | Add or remove slugs in `aso_wp_config_standalone()`. |
| Colors, spacing, or typography | Add admin CSS through your preferred plugin or color scheme. |
| Command-palette placement | Use Settings → Sidebar Organizer; Network Admin on multisite. |

See the [configuration guide](configuration.md) for the complete callbacks and
[copyable example](examples/admin-sidebar-config.php).

### Add a section or another plugin's menu

To add a section, add an entry like this to your MU-plugin’s section array:

```php
'events' => array(
    'label' => __( 'Events', 'admin-sidebar-organizer-wp' ),
    'icon'  => 'dashicons-calendar-alt',
    'items' => array( 'edit.php?post_type=event' ),
),
```

Use the plugin's menu slug in `items`. Unassigned menus appear under **See More**.

## Styling and accessibility

The organizer supplies slate section backgrounds and component layout styles. Hover and keyboard focus use WordPress’s `--wp-admin-theme-color`, with the standard blue fallback when that variable is unavailable.

Custom schemes can override `--aso-sidebar-hover-background` and `--aso-sidebar-hover-color` on `#adminmenu.aso-sidebar`.

### Customize appearance with CSS

Add these rules through your own plugin or an admin CSS plugin. Load overrides after the organizer's styles. If using
`wp_enqueue_style()` on `admin_enqueue_scripts`, set `admin-sidebar-organizer-wp`
as the stylesheet dependency. 

```css
#adminmenu.aso-sidebar {
    --aso-sidebar-hover-background: #135e96;
    --aso-sidebar-hover-color: #fff;
}

/* Optional: change the resting section surface and label treatment. */
#adminmenu.aso-sidebar .aso-sidebar__toggle {
    background-color: #253340;
    color: #fff;
    text-transform: none;
    letter-spacing: normal;
}
```

The script adds `aso-sidebar` BEM classes to section controls and organized menu items. Inspect the rendered sidebar for specific selectors and keep overrides in your own admin CSS.

## Code layout

| File | Responsibility |
| --- | --- |
| `admin-sidebar-organizer-wp.php` | Plugin bootstrap and metadata. |
| `includes/class-admin-sidebar-organizer-wp.php` | Dependency loading and hook registration. |
| `includes/class-admin-sidebar-organizer-wp-menu-sections.php` | Configuration filters and discovery of the final native menu. |
| `admin/templates/` | Settings page and Sidebar Options markup. |
| `admin/class-aso-admin.php` | Conditional asset loading and admin body class. |
| `admin/js/admin-sidebar-organizer-wp-admin.js` | Section grouping and collapse behavior. |
| `admin/js/menu-search.js` | Native command-palette trigger relocation. |
| `admin/css/admin-sidebar-organizer-wp-admin.css` | Component layout and default colors. |
| `admin/css/menu-flyout.css` | Desktop submenu flyout styles. |
| `docs/examples/admin-sidebar-config.php` | Portable configuration example; not loaded automatically. |
