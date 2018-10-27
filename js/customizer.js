/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( value ) {

	// Primary color...
	wp.customize( 'primary-color', function( value ) {
		value.bind( function( newval ) {
			var css = `
				/* Set background for:
				 * - featured image :before
				 * - featured image :before
				 * - post thumbmail :before
				 * - post thumbmail :before
				 * - wp block button
				 * - other buttons
				 * - Submenu
				 * - Sticky Post
				 * - WP Block Button
				 */
				.site-header.featured-image .hentry:before,
				.site-header.featured-image .hentry:after,
				.image-filters-enabled .hentry .post-thumbnail:before,
				.image-filters-enabled .hentry .post-thumbnail:after,
				.entry-content .wp-block-button .wp-block-button__link,
				.button, button, input[type="button"], input[type="reset"], input[type="submit"],
				.main-navigation .sub-menu,
				.sticky-post,
				.entry-content .wp-block-button .wp-block-button__link,
				.entry-content .wp-block-pullquote.is-style-solid-color:not(.has-background-color),
				.entry-content .wp-block-file .wp-block-file__button {
				  background: ${newval};
				}

				/* Set Color for:
				 * - all links
				 * - main navigation links
				 * - Post navigation links
				 * - Post entry meta hover
				 * - Post entry header more-link hover
				 * - main navigation svg
				 * - comment navigation
				 * - Comment edit link hover
				 * - Site Footer Link hover
				 */
				a,
				a:visited,
				.main-navigation ul.main-menu > li > a,
				.post-navigation .post-title,
				.hentry .entry-meta a:hover, .hentry .entry-footer a:hover,
				.hentry .entry-content .more-link:hover,
				.main-navigation .main-menu > li > a + svg,
				.comment-navigation .nav-previous a:hover,
				.comment-navigation .nav-next a:hover,
				.comment .comment-metadata .comment-edit-link:hover,
				.site-footer a:hover,
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link:not(.has-text-color),
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link:focus:not(.has-text-color),
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link:active:not(.has-text-color)  {
					color: ${newval};
				}

				/* Set left border color for:
				 * wp block quote
				 */
				.entry-content .wp-block-quote:not(.is-large), .entry-content .wp-block-quote:not(.is-style-large) {
					border-left-color: ${newval};
				}

				/* Set border color for:
				 * :focus
				 */
				input[type="text"]:focus,
				input[type="email"]:focus,
				input[type="url"]:focus,
				input[type="password"]:focus,
				input[type="search"]:focus,
				input[type="number"]:focus,
				input[type="tel"]:focus,
				input[type="range"]:focus,
				input[type="date"]:focus,
				input[type="month"]:focus,
				input[type="week"]:focus,
				input[type="time"]:focus,
				input[type="datetime"]:focus,
				input[type="datetime-local"]:focus,
				input[type="color"]:focus,
				textarea:focus,
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link,
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link:focus,
				.entry-content .wp-block-button.is-style-outline .wp-block-button__link:active {
					border-color: ${newval}
				}

				.gallery-item > div > a:focus {
					box-shadow: 0 0 0 2px ${newval};
				}
  			`,
	    	head = document.head || document.getElementsByTagName( 'head' )[0],
	   		style = document.createElement( 'style' );

			style.type = 'text/css';
			if ( style.styleSheet ){
			  // This is required for IE8 and below.
			  style.styleSheet.cssText = css;
			} else {
			  style.appendChild( document.createTextNode( css ) );
			}
			head.appendChild( style );

		} );
	} );

	// Primary hover color...
	wp.customize( 'primary-color-hover', function( value ) {
		value.bind( function( newval ) {

			var css = `
				a:hover, a:active,
				.main-navigation .main-menu > li > a:hover,
				.main-navigation .main-menu > li > a:hover + svg,
				.post-navigation .nav-links a:hover,
				.comment .comment-author .fn a:hover,
				.comment-reply-link:hover,
				#cancel-comment-reply-link:hover {
					color: ${newval};
				}

				.main-navigation .sub-menu > li > a:hover, .main-navigation .sub-menu > li > a:focus,
				.main-navigation .sub-menu > li > a:hover:after, .main-navigation .sub-menu > li > a:focus:after {
					background: ${newval};
				}
  			`,
	    	head = document.head || document.getElementsByTagName( 'head' )[0],
	   		style = document.createElement( 'style' );

			style.type = 'text/css';
			if ( style.styleSheet ){
			  // This is required for IE8 and below.
			  style.styleSheet.cssText = css;
			} else {
			  style.appendChild( document.createTextNode( css ) );
			}
			head.appendChild( style );

		} );
	} );

	// Image filter.
	wp.customize( 'image_filter', function( value ) {
		value.bind( function( to ) {
			if ( 'active' === to ) {
				$( 'body' ).addClass( 'image-filters-enabled' );
			} else {
				$( 'body' ).removeClass( 'image-filters-enabled' );
			}
		} );
	} );

} )()