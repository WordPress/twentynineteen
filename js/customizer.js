/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( value ) {
	//Update site header overlay color...
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
				 */
				.site-header.featured-image .hentry:before,
				.site-header.featured-image .hentry:after,
				.image-filters-enabled .hentry .post-thumbnail:before,
				.image-filters-enabled .hentry .post-thumbnail:after,
				.entry-content .wp-block-button .wp-block-button__link,
				.button, button, input[type="button"], input[type="reset"], input[type="submit"] {
				  background: ${newval};
				}

				/* Set Color for:
				 * - all links
				 * - main navigation links
				 * - Post navigation links
				 * - Post entry meta hover
				 */
				a,
				.main-navigation ul.main-menu > li > a,
				.post-navigation .post-title,
				.hentry .entry-meta a:hover, .hentry .entry-footer a:hover {
					color: ${newval};
				}

				/* Set left border color for:
				 * wp block quote
				 */
				.entry-content .wp-block-quote:not(.is-large), .entry-content .wp-block-quote:not(.is-style-large) {
					border-left-color: ${newval};
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
} )()