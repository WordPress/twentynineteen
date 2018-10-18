<?php
/**
 * Twenty Nineteen Theme Customizer
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function twentynineteen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'twentynineteen_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'twentynineteen_customize_partial_blogdescription',
			)
		);
	}

	// Add page primary color setting and control.
	$wp_customize->add_setting( 'primary-color', array(
		'default'           => '#0073aa',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary-color', array(
		'label'       => __( 'Primary Color' ),
		'description' => __( 'Changes the Color of the Featured Image, Buttons, Links etc.' ),
		'section'     => 'colors',
	) ) );
}
add_action( 'customize_register', 'twentynineteen_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function twentynineteen_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function twentynineteen_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function twentynineteen_customize_preview_js() {
	wp_enqueue_script( 'twentynineteen-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'twentynineteen_customize_preview_js' );


/**
 * Enqueues front-end CSS for the page background color.
 *
 * @since Twenty Sixteen 1.0
 *
 * @see wp_add_inline_style()
 */
function twentynineteen_primary_color_css() {
	$default_color         = '#0073aa';
	$primary_color = get_theme_mod( 'primary-color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $primary_color === $default_color ) {
		return;
	}

	$css = '

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
		  background: %1$s;
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
			color: %1$s;
		}

		/* Set left border color for:
		 * wp block quote
		 */
		.entry-content .wp-block-quote:not(.is-large), .entry-content .wp-block-quote:not(.is-style-large) {
			border-left-color: %1$s;
		}
	';

	wp_add_inline_style( 'twentynineteen-style', sprintf( $css, $primary_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentynineteen_primary_color_css', 11 );