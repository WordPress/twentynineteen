<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function twentynineteen_body_classes( $classes ) {

	if ( is_singular() ) {
		// Adds `singular` to singular pages.
		$classes[] = 'singular';
	} else {
		// Adds `hfeed` to non singular pages.
		$classes[] = 'hfeed';
	}

	// Adds a class if image filters are enabled.
	if ( twentynineteen_image_filters_enabled() ) {
		$classes[] = 'image-filters-enabled';
	}

	return $classes;
}
add_filter( 'body_class', 'twentynineteen_body_classes' );

/**
 * Adds custom class to the array of posts classes.
 */
function twentynineteen_post_classes( $classes, $class, $post_id ) {
	$classes[] = 'entry';

	return $classes;
}
add_filter( 'post_class', 'twentynineteen_post_classes', 10, 3 );


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function twentynineteen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'twentynineteen_pingback_header' );

/**
 * Changes comment form default fields.
 */
function twentynineteen_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'twentynineteen_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function twentynineteen_get_the_archive_title() {
	if ( is_category() ) {
		$title = esc_html__( 'Category Archives: ', 'twentynineteen' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = esc_html__( 'Tag Archives: ', 'twentynineteen' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = esc_html__( 'Author Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = esc_html__( 'Yearly Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentynineteen' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = esc_html__( 'Monthly Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentynineteen' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = esc_html__( 'Daily Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = esc_html__( 'Post Type Archives: ', 'twentynineteen' ) . '<span class="page-description">' . post_type_archive_title( '', false ) . '</span>';
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: %s: Taxonomy singular name */
		$title = sprintf( esc_html__( '%s Archives:', 'twentynineteen' ), $tax->labels->singular_name );
	} else {
		$title = esc_html__( 'Archives:', 'twentynineteen' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'twentynineteen_get_the_archive_title' );

/**
 * Determines if post thumbnail can be displayed.
 */
function twentynineteen_can_show_post_thumbnail() {
	return apply_filters( 'twentynineteen_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns true if image filters are enabled on the theme options.
 */
function twentynineteen_image_filters_enabled() {
	if ( 'inactive' === get_theme_mod( 'image_filter' ) ) {
		return false;
	}
	return true;
}

/**
 * Returns the size for avatars used in the theme.
 */
function twentynineteen_get_avatar_size() {
	return 60;
}

/**
 * Returns true if comment is by author of the post.
 *
 * @see get_comment_class()
 */
function twentynineteen_is_comment_by_post_author( $comment = null ) {
	if ( is_object( $comment ) && $comment->user_id > 0 ) {
		$user = get_userdata( $comment->user_id );
		$post = get_post( $comment->comment_post_ID );
		if ( ! empty( $user ) && ! empty( $post ) ) {
			return $comment->user_id === $post->post_author;
		}
	}
	return false;
}

/**
 * Returns information about the current post's discussion, with cache support.
 */
function twentynineteen_get_discussion_data() {
	static $discussion, $post_id;
	$current_post_id = get_the_ID();
	if ( $current_post_id === $post_id ) { /* If we have discussion information for post ID, return cached object */
		return $discussion;
	}
	$authors    = array();
	$commenters = array();
	$user_id    = -1; // is_user_logged_in() ? get_current_user_id() : -1;
	$comments   = get_comments(
		array(
			'post_id' => $current_post_id,
			'orderby' => 'comment_date_gmt',
			'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
			'status'  => 'approve',
		)
	);
	foreach ( $comments as $comment ) {
		$comment_user_id = (int) $comment->user_id;
		if ( $comment_user_id !== $user_id ) {
			$authors[]    = ( $comment_user_id > 0 ) ? $comment_user_id : $comment->comment_author_email;
			$commenters[] = $comment->comment_author_email;
		}
	}
	$authors    = array_unique( $authors );
	$responses  = count( $commenters );
	$commenters = array_unique( $commenters );
	$post_id    = $current_post_id;
	$discussion = (object) array(
		'authors'    => array_slice( $authors, 0, 6 ), /* Unique authors commenting on post (a subset of), excluding current user. */
		'commenters' => count( $commenters ),          /* Number of commenters involved in discussion, excluding current user. */
		'responses'  => $responses,                    /* Number of responses, excluding responses from current user. */
	);
	return $discussion;
}

/**
 * Add a dropdown icon to top-level menu items
 */
function twentynineteen_add_dropdown_icons( $output, $item, $depth, $args ) {

	// Only add class to 'top level' items on the 'primary' menu.
	if ( 'menu-1' == $args->theme_location && 0 === $depth ) {

		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$output .= twentynineteen_get_icon_svg( 'arrow_drop_down_circle', 16 );
		}
	} else if ( 'menu-1' == $args->theme_location && $depth >= 1 ) {

		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$output .= twentynineteen_get_icon_svg( 'keyboard_arrow_right', 24 );
		}
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'twentynineteen_add_dropdown_icons', 10, 4 );

/**
 * Convert HSL to HEX colors
 */
function twentynineteen_hsl_hex( $h, $s, $l, $to_hex = true ) {

	$h /= 360;
	$s /= 100;
	$l /= 100;

	$r = $l;
	$g = $l;
	$b = $l;
	$v = ( $l <= 0.5 ) ? ( $l * ( 1.0 + $s ) ) : ( $l + $s - $l * $s );
	if ( $v > 0 ) {
		$m;
		$sv;
		$sextant;
		$fract;
		$vsf;
		$mid1;
		$mid2;

		$m = $l + $l - $v;
		$sv = ( $v - $m ) / $v;
		$h *= 6.0;
		$sextant = floor( $h );
		$fract = $h - $sextant;
		$vsf = $v * $sv * $fract;
		$mid1 = $m + $vsf;
		$mid2 = $v - $vsf;

		switch ( $sextant ) {
			case 0:
				$r = $v;
				$g = $mid1;
				$b = $m;
				break;
			case 1:
				$r = $mid2;
				$g = $v;
				$b = $m;
				break;
			case 2:
				$r = $m;
				$g = $v;
				$b = $mid1;
				break;
			case 3:
				$r = $m;
				$g = $mid2;
				$b = $v;
				break;
			case 4:
				$r = $mid1;
				$g = $m;
				$b = $v;
				break;
			case 5:
				$r = $v;
				$g = $m;
				$b = $mid2;
				break;
		}
	}
	$r = round( $r * 255, 0 );
	$g = round( $g * 255, 0 );
	$b = round( $b * 255, 0 );

	if ( $to_hex ) {

		$r = ( $r < 15 ) ? '0' . dechex( $r ) : dechex( $r );
		$g = ( $g < 15 ) ? '0' . dechex( $g ) : dechex( $g );
		$b = ( $b < 15 ) ? '0' . dechex( $b ) : dechex( $b );

		return "#$r$g$b";

	} else {

		return "rgb($r, $g, $b)";
	}
}
