/* global twentynineteenScreenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

(function( $ ) {
	var masthead, menuToggle, siteNavContain, siteNavigation;

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $( '<button />', { 'class': 'dropdown-toggle', 'aria-expanded': false })
			.append( twentynineteenScreenReaderText.icon )
			.append( $( '<span />', { 'class': 'screen-reader-text', text: twentynineteenScreenReaderText.expand }) );

		container.find( '.menu-item-has-children > a, .page_item_has_children > a' ).after( dropdownToggle );

		// Set the active submenu dropdown toggle button initial state.
		container.find( '.current-menu-ancestor > button' )
			.addClass( 'toggled-on' )
			.attr( 'aria-expanded', 'true' )
			.find( '.screen-reader-text' )
			.text( twentynineteenScreenReaderText.collapse );
		// Set the active submenu initial state.
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' );

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );

			screenReaderSpan.text( screenReaderSpan.text() === twentynineteenScreenReaderText.expand ? twentynineteenScreenReaderText.collapse : twentynineteenScreenReaderText.expand );
		});
	}

	// initMainNavigation( $( '.main-navigation' ) );

	masthead       = $( '#masthead' );
	menuToggle     = masthead.find( '.mobile-submenu-expand' );
	siteNavContain = masthead.find( '.main-navigation' );
	siteNavigation = masthead.find( '.main-navigation > div > ul' );

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	(function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( 'none' === $( '.mobile-submenu-expand' ).css( 'display' ) ) {

				$( document.body ).on( 'touchstart.twentynineteen', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
					//	$( '.main-navigation li' ).removeClass( 'focus' );
					}
				});

				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' )
					.on( 'touchstart.twentynineteen', function( e ) {
						var el = $( this ).parent( 'li' );

						if ( ! el.hasClass( 'focus' ) ) {
							e.preventDefault();
						//	el.toggleClass( 'focus' );
							el.siblings( '.focus' ).removeClass( 'focus' );
						}

						$( this ).siblings( '.sub-menu' ).toggleClass( 'open' );
					});

			} else {
				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' ).unbind( 'touchstart.twentynineteen' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.twentynineteen', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( '.mobile-submenu-expand' ).on( 'focus.twentynineteen blur.twentynineteen', function() {
			$( this ).parents( '.menu-item, .page_item' ).toggleClass( 'focus' );
			$( this ).siblings( '.sub-menu' ).toggleClass( 'open' );
		});
	})();
})( jQuery );
