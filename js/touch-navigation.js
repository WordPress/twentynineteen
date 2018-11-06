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
	function getCurrentParent( child, selector, stopSelector ) {

		var currentParent = null;

		while (child) {
			if (child.matches(selector)) {
				currentParent = child;
				break
			} else if (stopSelector && child.matches(stopSelector)) {
				break
			}
			child = child.parentElement;
		}

		return currentParent;
	}

	// Open Sub-menu
	function openSubMenu( currentSubMenu ) {

		var menuItem     = currentSubMenu.parentElement; // this.parentNode
		var menuItemAria = menuItem.querySelectorAll('a[aria-expanded]');

		// Update classes
		// classList.add is not supported in IE11
		menuItem.className += ' focus';
		menuItem.lastElementChild.className += ' expanded-true';

		// Update aria-expanded state
		toggleAriaExpandedState( menuItemAria );
	}

	// Close Sub-menu
	function closeSubMenu( currentSubMenu ) {

		var menuItem       = currentSubMenu.closest('.menu-item'); // this.parentNode
		var menuItemAria   = menuItem.querySelectorAll('a[aria-expanded]');
		var subMenu        = currentSubMenu.closest('.sub-menu');

		// If this is in a sub-sub-menu, go back to parent sub-menu
		if ( getCurrentParent( currentSubMenu, 'ul' ).classList.contains( 'sub-menu' ) ) {

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
	}

	// Focus Sub-menu
	function setAriaState( currentMenuItem ) {

		var menuItem     = currentMenuItem.closest('.menu-item'); // this.parentNode
		var menuItemAria = menuItem.querySelector('a[aria-expanded]');

		// Update aria-expanded state
		toggleAriaExpandedState( menuItemAria );
	}

	// Remove all focus states
	function removeAllFocusStates() {

		var focusedElements = document.querySelectorAll(':hover, :focus, :focus-within');
		var i;

		for ( i = 0; i < focusedElements.length; i++) {
			focusedElements[i].blur();
		}
	}

	var siteNavigation = document.querySelector('.main-navigation > div > ul');
	var subMenuExpand  = document.querySelectorAll('.submenu-expand');
	var subMenuReturn  = document.querySelectorAll('.menu-item-link-return');
	var parentMenuLink = siteNavigation.querySelectorAll('.menu-item-has-children a[aria-expanded]');
	var i;

	// Toggle `focus` class to allow submenu access on touch screens.
	function toggleSubmenuTouchScreen() {
		'use strict';

		// Check for submenus and bail if none exist
		if ( ! siteNavigation || ! siteNavigation.children ) {
			return;
		}

		// Open submenus on touch
		for ( i = 0; i < subMenuExpand.length; i++) {
			subMenuExpand[i].addEventListener('touchstart', function( event ) {
				// Open menu
				openSubMenu( event.currentTarget );

				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});

			subMenuExpand[i].addEventListener('touchend', function( event ) {
				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});
		}

		// Close sub-menus or sub-sub-menus on touch
		for ( i = 0; i < subMenuReturn.length; i++) {

			subMenuReturn[i].addEventListener('touchstart', function( event ) {
				// Close menu
				closeSubMenu( event.currentTarget );

				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});

			subMenuReturn[i].addEventListener('touchend', function( event ) {
				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});

		}

		// Prevent :focus-within on menu-item links when using touch devices
		for ( i = 0; i < parentMenuLink.length; i++) {
			parentMenuLink[i].addEventListener('touchstart', function( event ) {
				// Go to link without openning submenu
				window.location = this.getAttribute('href');

				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});

			parentMenuLink[i].addEventListener('touchend', function( event ) {
				// Prevent default mouse events
				event.preventDefault();
				removeAllFocusStates();
			});

			// Aria state
			parentMenuLink[i].addEventListener('focus', setAriaState( parentMenuLink[i] ) );
		}
	}

	// Run our submenu function as soon as the document is `ready`
	document.addEventListener( 'DOMContentLoaded', function() {
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
