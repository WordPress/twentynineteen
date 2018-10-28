/**
 * Touch navigation.
 *
 * Contains handlers for navigation on Touch devices.
 */

(function() {

	// Toggle `focus` class to allow submenu access on tablets.
	function toggleSubmenuTouchScreen() {

		var masthead, siteNavContain, siteNavigation;

		masthead       = document.querySelector( "#masthead" );
		siteNavigation = document.querySelector( '.main-navigation > div > ul' );

		// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
		if ( ! siteNavigation || ! siteNavigation.children ) {
			console.log( 'fail' );
			return;
		}

		console.log( 'running' );

		const openSubMenu = document.querySelectorAll(".mobile-submenu-expand");

		for (let i = 0; i < toggle.length; i++) {
			toggle[i].addEventListener("click", function() {
				console.log( 'open test'+ this );
				this.closest(".menu-item").classList.add("focus");
				this.parentNode.lastElementChild.classList.add("open");
			});
		}

		const closeSubMenu = document.querySelectorAll(".menu-item-link-return");

		for (let i = 0; i < toggleSubMenu.length; i++) {
			toggleSubMenu[i].addEventListener("click", function() {
				console.log( 'close test'+ this );
				this.closest(".menu-item").classList.add("focus");
				this.parentNode.lastElementChild.classList.add("open");

				// If not already a sub-menu, close all menus
				if ( this.parents( 'ul' ).hasClass( 'sub-menu' ) ) {

					$( this ).closest( '.sub-menu' ).removeClass( 'open' );

				} else {

					$( this ).parents( '.menu-item, .page_item' ).removeClass( 'focus' );
					$( this ).siblings( '.sub-menu' ).removeClass( 'open' );
				}

			});
		}

		// change this to test on screen
		// .on( 'focus.twentynineteen blur.twentynineteen',
	//	siteNavigation.querySelector( '.mobile-submenu-expand' ).addEventListener( 'touchstart.twentynineteen', function(el) {
/*
		document.querySelectorAll(".mobile-submenu-expand").addEventListener("click", function(el) {
			console.log('test'+ el);
			el.parentNode.classList.add("focus");
			el.nextSibling.classList.add("open");
		});
*/

/*
		siteNavigation.find( '.menu-item-link-return' ).on( 'touchstart.twentynineteen', function() {

			// If not already a sub-menu, close all menus
			if ( $( this ).parents( 'ul' ).hasClass( 'sub-menu' ) ) {

				$( this ).closest( '.sub-menu' ).removeClass( 'open' );

			} else {

				$( this ).parents( '.menu-item, .page_item' ).removeClass( 'focus' );
				$( this ).siblings( '.sub-menu' ).removeClass( 'open' );
			}
		});
*/

	}

toggleSubmenuTouchScreen();

//	if ( 'ontouchstart' in window ) {
//		window.addEventListener("resize.twentynineteen", toggleSubmenuTouchScreen );
//		toggleSubmenuTouchScreen();
//	}

})();
