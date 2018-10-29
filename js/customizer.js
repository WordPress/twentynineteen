/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) { // jshint ignore:line

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

} )( jQuery );
