/** Move WordPress's existing palette trigger; keep its click handler and modal. */
/* global wp */
wp.domReady(function () {
	var sidebar = document.getElementById('adminmenu');
	if (document.getElementById('aso-sidebar-menu-search')) {
		return;
	}
	var toolbarItem = document.getElementById('wp-admin-bar-command-palette');
	var link = toolbarItem && toolbarItem.querySelector('a');
	if (!sidebar || !link) {
		// Leave the native toolbar trigger untouched when relocation is unavailable.
		return;
	}

	var item = document.createElement('li');
	item.id = 'aso-sidebar-menu-search';
	item.className = 'menu-top';
	link.className = 'menu-top';
	link.setAttribute('role', 'button');
	link.setAttribute('aria-haspopup', 'dialog');
	var icon = document.createElement('div');
	icon.className = 'wp-menu-image dashicons-before dashicons-menu';
	icon.setAttribute('aria-hidden', 'true');
	var label = document.createElement('div');
	label.className = 'wp-menu-name';
	label.textContent = wp.i18n.__('Go to…', 'admin-sidebar-organizer-wp');
	var shortcut = /Mac|iPhone|iPad|iPod/.test(navigator.platform) ? '⌘K' : 'Ctrl+K';
	var description = wp.i18n.sprintf(
		/* translators: %s: platform-specific keyboard shortcut. */
		wp.i18n.__('Open command palette (%s)', 'admin-sidebar-organizer-wp'),
		shortcut
	);
	link.setAttribute('title', description);
	link.setAttribute('aria-label', description);
	link.replaceChildren(icon, label);
	// Links already support Enter; add Space for the button role.
	link.addEventListener('keydown', function (event) {
		if (event.key === ' ') {
			event.preventDefault();
			link.click();
		}
	});
	item.appendChild(link);
	sidebar.prepend(item);
	// Remove the old container only after its working link is in the sidebar.
	toolbarItem.remove();
	document.body.classList.add('aso-sidebar-menu-search-ready');
});
