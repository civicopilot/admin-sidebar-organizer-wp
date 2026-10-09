/**
 * Network-configured sections over native WordPress menu siblings.
 * Native menu parents and submenus remain intact.
 */
window.asoWpInitMenuSections = function () {
	'use strict';
	const data = window.asoWpMenuSections;
	const menu = document.getElementById( 'adminmenu' );
	if ( ! data || ! menu || menu.querySelector( '.aso-sidebar__heading' ) ) {
		return;
	}
	const key = 'aso-sidebar:sections:' + data.site + ':' + data.user;
	let stored = {};
	try {
		const value = JSON.parse( sessionStorage.getItem( key ) || '{}' );
		if ( value && typeof value === 'object' && ! Array.isArray( value ) ) {
			stored = value;
		}
	} catch ( error ) {
		// Navigation remains usable when storage is unavailable.
	}
	const available = data.items.map( ( item ) => ( {
		...item,
		node: document.getElementById( item.id ),
	} ) ).filter( ( item ) => item.node && item.node.parentElement === menu );
	const claimed = new Set();
	const excluded = available.filter( ( item ) => item.slugs.some( ( slug ) => data.excluded.includes( slug ) ) );
	excluded.forEach( ( item ) => claimed.add( item.id ) );
	const sections = [];
	Object.entries( data.sections ).forEach( ( [ id, section ] ) => {
		const items = [];
		section.items.forEach( ( slug ) => {
			available.forEach( ( item ) => {
				if ( ! claimed.has( item.id ) && item.slugs.includes( slug ) ) {
					items.push( item );
					claimed.add( item.id );
				}
			} );
		} );
		if ( items.length ) {
			sections.push( { id, label: section.label, icon: section.icon, items } );
		}
	} );
	const other = available.filter( ( item ) => ! claimed.has( item.id ) );
	if ( other.length ) {
		sections.push( { id: 'other', label: data.other, icon: 'dashicons-category', items: other } );
	}
	// Unmodelled DOM additions are left untouched; no links are manufactured.
	const anchor = document.getElementById( 'collapse-menu' );
	excluded.forEach( ( item ) => menu.insertBefore( item.node, anchor ) );
	const narrow = window.matchMedia( '(min-width: 783px) and (max-width: 960px)' );
	const controls = [];
	const isNarrow = () => document.body.classList.contains( 'folded' ) ||
		( narrow.matches && document.body.classList.contains( 'auto-fold' ) );
	sections.forEach( ( section, index ) => {
		const heading = document.createElement( 'li' );
		heading.className = 'aso-sidebar__heading';
		if ( index === 0 && excluded.length ) {
			heading.classList.add( 'aso-sidebar__heading--after-standalone' );
		}
		const button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'aso-sidebar__toggle';
		const label = document.createElement( 'span' );
		label.className = 'aso-sidebar__label';
		if ( /^dashicons-[a-z0-9-]+$/.test( section.icon || '' ) ) {
			const icon = document.createElement( 'span' );
			icon.className = 'dashicons ' + section.icon;
			icon.setAttribute( 'aria-hidden', 'true' );
			label.appendChild( icon );
		}
		const text = document.createElement( 'span' );
		text.textContent = section.label;
		label.appendChild( text );
		button.appendChild( label );
		button.setAttribute( 'aria-controls', section.items.map( ( item ) => item.id ).join( ' ' ) );
		heading.appendChild( button );
		menu.insertBefore( heading, anchor );
		section.items.forEach( ( item ) => {
			item.node.classList.add( 'aso-sidebar__item' );
			menu.insertBefore( item.node, anchor );
		} );
		let expanded = section.items.some( ( item ) => item.node.matches( '.current, .wp-has-current-submenu' ) ) || stored[ section.id ] === true;
		const paint = () => {
			button.setAttribute( 'aria-expanded', String( expanded ) );
			section.items.forEach( ( item ) => {
				// Narrow mode retains WordPress's full icon rail and flyouts.
				item.node.classList.toggle( 'aso-sidebar__item--collapsed', ! expanded && ! isNarrow() );
			} );
		};
		button.addEventListener( 'click', () => {
			expanded = ! expanded;
			stored[ section.id ] = expanded;
			try {
				sessionStorage.setItem( key, JSON.stringify( stored ) );
			} catch ( error ) {
				// Storage is optional; never prevent toggling.
			}
			paint();
		} );
		controls.push( paint );
	} );
	menu.classList.add( 'aso-sidebar' );
	let wasNarrow;
	const update = () => {
		const narrowState = isNarrow();
		if ( narrowState === wasNarrow ) {
			return;
		}
		wasNarrow = narrowState;
		menu.classList.toggle( 'aso-sidebar--narrow', narrowState );
		controls.forEach( ( paint ) => paint() );
	};
	update();
	window.addEventListener( 'resize', update );
	new MutationObserver( update ).observe( document.body, { attributes: true, attributeFilter: [ 'class' ] } );
};
