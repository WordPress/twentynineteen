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

	/**
	 * Theme options.
	 */
	$wp_customize->add_section(
		'theme_options',
		array(
			'title'    => __( 'Theme Options', 'twentynineteen' ),
			'priority' => 130, // Before Additional CSS.
		)
	);

	$wp_customize->add_setting(
		'image_filter',
		array(
			'default'           => 'active',
			'sanitize_callback' => 'twentynineteen_sanitize_image_filter',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'image_filter',
		array(
			'label'       => __( 'Featured Image Color Filter', 'twentynineteen' ),
			'section'     => 'theme_options',
			'type'        => 'radio',
			'description' => __( "By default, Twenty Nineteen adds a duotone-like effect to featured images using your site's primary color. This effect can be turned off. When the color filter is disabled, a standard black overlay will be used on single post pages to preserve readability of the text that sits on top of the featured image.", 'twentynineteen' ),
			'choices'     => array(
				'active'   => __( 'Have a color filter applied.', 'twentynineteen' ),
				'inactive' => __( 'Not have a color filter applied.', 'twentynineteen' ),
			),
		)
	);
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
 * Sanitize image filter choice.
 *
 * @param string $choice Whether image filter is active.
 *
 * @return string
 */
function twentynineteen_sanitize_image_filter( $choice ) {
	$valid = array(
		'active',
		'inactive',
	);

	if ( in_array( $choice, $valid, true ) ) {
		return $choice;
	}

	return 'active';
}
