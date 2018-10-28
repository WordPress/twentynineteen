/* global twentynineteenScreenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

(function( $ ) {
	var masthead, siteNavContain, siteNavigation;

	masthead       = $( '#masthead' );
	siteNavContain = masthead.find( '.main-navigation' );
	siteNavigation = masthead.find( '.main-navigation > div > ul' );

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	(function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleSubmenuTouchScreen() {

			// change this to test on screen
			// .on( 'focus.twentynineteen blur.twentynineteen',
			siteNavigation.find( '.mobile-submenu-expand' ).on( 'touchstart.twentynineteen', function() {
				$( this ).parents( '.menu-item, .page_item' ).addClass( 'focus' );
				$( this ).siblings( '.sub-menu' ).addClass( 'open' );
			});

			siteNavigation.find( '.menu-item-link-return' ).on( 'touchstart.twentynineteen', function() {

				// If not already a sub-menu, close all menus
				if ( $( this ).parents( 'ul' ).hasClass( 'sub-menu' ) ) {

					$( this ).closest( '.sub-menu' ).removeClass( 'open' );

				} else {

					$( this ).parents( '.menu-item, .page_item' ).removeClass( 'focus' );
					$( this ).siblings( '.sub-menu' ).removeClass( 'open' );
				}
			});
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.twentynineteen', toggleSubmenuTouchScreen );
			toggleSubmenuTouchScreen();
		}

	})();
})( jQuery );
