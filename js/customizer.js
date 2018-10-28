/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( value ) {

	// Primary color.
	wp.customize( 'primary-color', function( value ) {
		value.bind( function( to ) {

			// Update custom color CSS
			var style = $( '#custom-theme-colors' ), // Selector of output style element.
				primary_color = style.data( 'primary-color' ),
				css = style.html();
				// Equivalent to css.replaceAll, with hue followed by comma to prevent values with units from being changed.
				css = css.split( primary_color ).join( to ); // equivalent to css.replaceAll.
				style.html( css )
					.data( 'primary-color', to );
		});
	});

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