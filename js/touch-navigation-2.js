/**
 * Touch navigation.
 *
 * Contains handlers for navigation on Touch devices.
 */

(function() {

	// Toggle `focus` class to allow submenu access on tablets.
	function toggleSubmenuTouchScreen() {

		// Check for submenus and bail if none exist
		var siteNavigation = document.querySelector( '.main-navigation > div > ul' );

		if ( ! siteNavigation || ! siteNavigation.children ) {
			return;
		}

		// Open submenus on touch
		const openSubMenu = document.querySelectorAll(".mobile-submenu-expand");

		for (let i = 0; i < openSubMenu.length; i++) {

			openSubMenu[i].addEventListener("touchstart", function() {

				this.addEventListener("touchend", function() {
					this.closest(".menu-item").classList.add("focus");
					this.parentNode.lastElementChild.classList.add("open");
				});
			});
		}

		// Close sub-menus or sub-sub-menus on touch
		const closeSubMenu = document.querySelectorAll(".menu-item-link-return");

		for (let i = 0; i < closeSubMenu.length; i++) {

			closeSubMenu[i].addEventListener("touchstart", function() {

				this.addEventListener("touchend", function() {

					// If this is in a sub-sub-menu, go back to parent sub-menu
					if ( this.closest("ul").classList.contains("sub-menu") ) {

						this.closest(".sub-menu").classList.remove("open");

					// Or else close all sub-menus
					} else {

						this.closest(".menu-item").classList.remove("focus");
						this.parentNode.lastElementChild.classList.remove("open");
					}
				});
			});
		}

		siteNavigation.blur();
	}

	// Debounce
	function debounce(func, wait = 20, immediate = true) {

		var timeout;

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

	// Run our functions once the window has loaded fully
	window.addEventListener("load", function() {
		toggleSubmenuTouchScreen();
	});

	// Annnnnd also every time the window resizes
	var isResizing = false;
	window.addEventListener("resize", debounce( function() {
		if (isResizing) {
			return;
		}

		isResizing = true;
		setTimeout( function() {
			toggleSubmenuTouchScreen();
			isResizing = false;
		}, 150 );
	}));

})();
