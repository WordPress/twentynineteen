/**
 * Touch navigation.
 *
 * Contains handlers for navigation on Touch devices.
 */

(function() {

	// Debounce
	function debounce(func, wait, immediate) {
		'use strict';

		var timeout;
		wait      = (typeof wait !== 'undefined') ? wait : 20;
		immediate = (typeof immediate !== 'undefined') ? immediate : true;

		return function() {

			var context = this, args = arguments;
			var later = function() {
				timeout = null;

				if (!immediate) {
					func.apply(context, args);
				}
			};

			var callNow = immediate && !timeout;

			clearTimeout(timeout);
			timeout = setTimeout(later, wait);

			if (callNow) {
				func.apply(context, args);
			}
		};
	}

	// Toggle Aria Expanded state for screenreaders
	function toggleAriaExpandedState( ariaItems ) {
		'use strict';

		var i;

    	for (i = 0; i < ariaItems.length; i++) {

			var state = ariaItems[i].getAttribute('aria-expanded');

			if (state === 'true') {
				state = 'false';
			} else {
				state = 'true';
			}

			ariaItems[i].setAttribute('aria-expanded', state);
		}
	}

	// Find first ancestor of an element by tagName
	function getCurrentParent( child, tagName ) {

		tagName = tagName.toLowerCase();

		while ( child && child.parentNode ) {
			child = child.parentNode;

			if ( child.tagName && child.tagName.toLowerCase() === tagName ) {
				return child;
			}
		}
		return null;
	}

	// Toggle `focus` class to allow submenu access on touch screens.
	function toggleSubmenuTouchScreen() {
		'use strict';

		var siteNavigation = document.querySelector('.main-navigation > div > ul');
		var subMenuExpand  = document.querySelectorAll('.submenu-expand');
		var subMenuReturn  = document.querySelectorAll('.menu-item-link-return');
		var parentMenuLink = siteNavigation.querySelectorAll('.menu-item-has-children a[aria-expanded]');
		var i;

		// Check for submenus and bail if none exist
		if ( ! siteNavigation || ! siteNavigation.children ) {
			return;
		}

		// Remove focus states
		function removeFocus( element ) {
			element.blur();
		}

		// Open Sub-menu
		function openSubMenu( currentSubmenu ) {

			currentSubmenu.addEventListener('touchend', function(event) {

				var menuItem     = currentSubmenu.closest('.menu-item'); // this.parentNode
				var menuItemAria = menuItem.querySelectorAll('a[aria-expanded]');

				// Update classes
				// classList.add is not supported in IE11
				menuItem.className += ' focus';
				menuItem.lastElementChild.className += ' expanded-true';

				// Update aria-expanded state
				toggleAriaExpandedState( menuItemAria );

				// Disable focus when using touchdevices
				event.preventDefault();
				document.querySelectorAll(':hover, :focus, :focus-within').forEach(function(item) {
					item.blur();
				});
			});
		}

		// Open Sub-menu
		function focusSubMenu( currentMenuItem ) {

			var menuItem     = currentMenuItem.closest('.menu-item'); // this.parentNode
			var menuItemAria = menuItem.querySelector('a[aria-expanded]');

			// Update aria-expanded state
			toggleAriaExpandedState( menuItemAria );
		}

		function closeSubMenu( currentSubmenu ) {

			currentSubmenu.addEventListener('touchend', function(event) {

				var menuItem       = currentSubmenu.closest('.menu-item'); // this.parentNode
				var menuItemAria   = menuItem.querySelectorAll('a[aria-expanded]');
				var menuItemToggle = menuItem.querySelector('.mobile-submenu-expand');
				var subMenu        = currentSubmenu.closest('.sub-menu');

				// If this is in a sub-sub-menu, go back to parent sub-menu
				if ( getCurrentParent( this, 'ul' ).classList.contains( 'sub-menu' ) ) {

					// Update classes
					// classList.remove is not supported in IE11
					menuItem.className = menuItem.className.replace( 'focus', '' );
					subMenu.className = subMenu.className.replace( 'expanded-true', '' );

					// Update aria-expanded and :focus states
					toggleAriaExpandedState( menuItemAria );

				// Or else close all sub-menus
				} else {

					// Update classes
					// classList.remove is not supported in IE11
					menuItem.className = menuItem.className.replace( 'focus', '' );
					menuItem.lastElementChild.className = menuItem.lastElementChild.className.replace( 'expanded-true', '' );

					// Update aria-expanded and :focus states
					toggleAriaExpandedState( menuItemAria );
				}

				// Prevent click on link at touchend position after menu was closed and un-focus sub menu toggle
				event.preventDefault();
				document.querySelectorAll(':hover, :focus, :focus-within').forEach(function(item) {
					item.blur();
				});
			});
		}

		// Open submenus on touch
		for ( i = 0; i < subMenuExpand.length; i++) {
			subMenuExpand[i].addEventListener('touchstart', openSubMenu( subMenuExpand[i] ) );
		}

		// Close sub-menus or sub-sub-menus on touch
		for ( i = 0; i < subMenuReturn.length; i++) {
			subMenuReturn[i].addEventListener('touchstart', closeSubMenu( subMenuReturn[i] ) );
		}

		// Prevent :focus-within on menu-item links when using touch devices
		for ( i = 0; i < parentMenuLink.length; i++) {
			parentMenuLink[i].addEventListener('touchstart', function( event ) {

				// Stop link behavior
				event.preventDefault();

				// Go to link without openning submenu
				window.location = this.getAttribute('href');

			});

			parentMenuLink[i].addEventListener('focus', focusSubMenu( parentMenuLink[i] ) );
		}
	}

	// Run our functions once the window has loaded fully
	window.addEventListener( 'load', function() {
		toggleSubmenuTouchScreen();
	});

	// Annnnnd also every time the window resizes
	var isResizing = false;
	window.addEventListener( 'resize',
		debounce( function() {
			if ( isResizing ) {
				return;
			}

			isResizing = true;
			setTimeout( function() {
				toggleSubmenuTouchScreen();
				isResizing = false;
			}, 150 );
		} )
	);

})();
