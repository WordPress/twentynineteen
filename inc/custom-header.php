<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

 *
 * @package twentynineteen
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses twentynineteen_header_style()
 */
function twentynineteen_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'twentynineteen_custom_header_args', array(
		'width'                  => 2000,
		'height'                 => 280,
		'flex-height'            => true,
		'wp-head-callback'       => 'twentynineteen_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'twentynineteen_custom_header_setup' );

if ( ! function_exists( 'twentynineteen_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see twentynineteen_custom_header_setup().
	 */
	function twentynineteen_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
			.site-title {
				color: #111;
			}
			.site-description {
				color: #767676;
			}
		<?php
		// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;