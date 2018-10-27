<?php
/**
 * Twenty Nineteen Theme Customizer
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
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

	// Add page primary color hover setting and control.
	$wp_customize->add_setting( 'primary-color-hover', array(
		'default'           => '#005177',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary-color-hover', array(
		'label'       => __( 'Primary Color Hover' ),
		'description' => __( 'Changes the Hover State color of Buttons, Links etc.' ),
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
				  background: %1$s;
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
					color: %1$s;
				}

				/* Set left border color for:
				 * wp block quote
				 */
				.entry-content .wp-block-quote:not(.is-large), .entry-content .wp-block-quote:not(.is-style-large) {
					border-left-color: %1$s;
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
					border-color: %1$s
				}

				.gallery-item > div > a:focus {
					box-shadow: 0 0 0 2px %1$s;
				}
	';

	wp_add_inline_style( 'twentynineteen-style', sprintf( $css, $primary_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentynineteen_primary_color_css', 11 );


/**
 * Enqueues front-end CSS for the page background color.
 *
 * @since Twenty Sixteen 1.0
 *
 * @see wp_add_inline_style()
 */
function twentynineteen_primary_color_hover_css() {
	$default_color         = '#005177';
	$primary_color_hover = get_theme_mod( 'primary-color-hover', $default_color );

	// Don't do anything if the current color is the default.
	if ( $primary_color_hover === $default_color ) {
		return;
	}

	$css = '
		a:hover, a:active,
		.main-navigation .main-menu > li > a:hover,
		.main-navigation .main-menu > li > a:hover + svg,
		.post-navigation .nav-links a:hover,
		.comment .comment-author .fn a:hover,
		.comment-reply-link:hover,
		#cancel-comment-reply-link:hover {
			color: %1$s;
		}

		.main-navigation .sub-menu > li > a:hover, .main-navigation .sub-menu > li > a:focus,
		.main-navigation .sub-menu > li > a:hover:after, .main-navigation .sub-menu > li > a:focus:after {
			background: %1$s;
		}
	';

	wp_add_inline_style( 'twentynineteen-style', sprintf( $css, $primary_color_hover ) );
}
add_action( 'wp_enqueue_scripts', 'twentynineteen_primary_color_hover_css', 11 );