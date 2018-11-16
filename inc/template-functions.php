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
		$title = __( 'Category Archives: ', 'twentynineteen' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'twentynineteen' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentynineteen' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentynineteen' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'twentynineteen' ) . '<span class="page-description">' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = __( 'Post Type Archives: ', 'twentynineteen' ) . '<span class="page-description">' . post_type_archive_title( '', false ) . '</span>';
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: %s: Taxonomy singular name */
		$title = sprintf( esc_html__( '%s Archives:', 'twentynineteen' ), $tax->labels->singular_name );
	} else {
		$title = __( 'Archives:', 'twentynineteen' );
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
 * Add custom sizes attribute to responsive image functionality for post thumbnails.
 *
 * @origin Twenty Nineteen 1.0
 *
 * @param array $attr  Attributes for the image markup.
 * @return string Value for use in post thumbnail 'sizes' attribute.
 */
function twentynineteen_post_thumbnail_sizes_attr( $attr ) {

	if ( is_admin() ) {
		return $attr;
	}

	if ( ! is_singular() ) {
		$attr['sizes'] = '(max-width: 34.9rem) calc(100vw - 2rem), (max-width: 53rem) calc(8 * (100vw / 12)), (min-width: 53rem) calc(6 * (100vw / 12)), 100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentynineteen_post_thumbnail_sizes_attr', 10, 1 );

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
	if ( $current_post_id === $post_id ) {
		return $discussion; /* If we have discussion information for post ID, return cached object */
	} else {
		$post_id = $current_post_id;
	}

	$comments = get_comments(
		array(
			'post_id' => $current_post_id,
			'orderby' => 'comment_date_gmt',
			'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
			'status'  => 'approve',
			'number'  => 20, /* Only retrieve the last 20 comments, as the end goal is just 6 unique authors */
		)
	);

	$authors = array();
	foreach ( $comments as $comment ) {
		$authors[] = ( (int) $comment->user_id > 0 ) ? (int) $comment->user_id : $comment->comment_author_email;
	}

	$authors    = array_unique( $authors );
	$discussion = (object) array(
		'authors'   => array_slice( $authors, 0, 6 ),           /* Six unique authors commenting on the post. */
		'responses' => get_comments_number( $current_post_id ), /* Number of responses. */
	);

	return $discussion;
}

/**
 * Add an extra menu to our nav for our priority+ navigation to use
 *
 * @param object $nav_menu  Nav menu.
 * @param object $args      Nav menu args.
 * @return string More link for hidden menu items.
 */
function twentynineteen_add_ellipses_to_nav( $nav_menu, $args ) {

	if ( 'menu-1' === $args->theme_location ) :

		$nav_menu .= '<div class="main-menu-more"	>';
		$nav_menu .= '<ul class="main-menu" tabindex="0">';
		$nav_menu .= '<li class="menu-item menu-item-has-children">';
		$nav_menu .= '<a href="#" class="screen-reader-text" aria-label="More" aria-haspopup="true" aria-expanded="false">' . esc_html__( 'More', 'twentynineteen' ) . '</a>';
		$nav_menu .= '<span class="submenu-expand main-menu-more-toggle" tabindex="-1">';
		$nav_menu .= twentynineteen_get_icon_svg( 'arrow_drop_down_ellipsis' );
		$nav_menu .= '</span>';
		$nav_menu .= '<ul class="sub-menu hidden-links is-hidden">';
		$nav_menu .= '<li id="menu-item--1" class="mobile-parent-nav-menu-item menu-item--1">';
		$nav_menu .= '<span class="menu-item-link-return">';
		$nav_menu .= twentynineteen_get_icon_svg( 'chevron_left' );
		$nav_menu .= esc_html__( 'Back', 'twentynineteen' );
		$nav_menu .= '</span>';
		$nav_menu .= '</li>';
		$nav_menu .= '</ul>';
		$nav_menu .= '</li>';
		$nav_menu .= '</ul>';
		$nav_menu .= '</div>';

	endif;

	return $nav_menu;
}
add_filter( 'wp_nav_menu', 'twentynineteen_add_ellipses_to_nav', 10, 2 );

/**
 * WCAG 2.0 Attributes for Dropdown Menus
 *
 * Adjustments to menu attributes tot support WCAG 2.0 recommendations
 * for flyout and dropdown menus.
 *
 * @ref https://www.w3.org/WAI/tutorials/menus/flyout/
 */
function twentynineteen_nav_menu_link_attributes( $atts, $item, $args, $depth ) {

	// Add [aria-haspopup] and [aria-expanded] to menu items that have children
	$item_has_children = in_array( 'menu-item-has-children', $item->classes );
	if ( $item_has_children ) {
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'twentynineteen_nav_menu_link_attributes', 10, 4 );

/**
 * Add a dropdown icon to top-level menu items.
 *
 * @param string $output Nav menu item start element.
 * @param object $item   Nav menu item.
 * @param int    $depth  Depth.
 * @param object $args   Nav menu args.
 * @return string Nav menu item start element.
 * Add a dropdown icon to top-level menu items
 */
function twentynineteen_add_dropdown_icons( $output, $item, $depth, $args ) {

	// Only add class to 'top level' items on the 'primary' menu.
	if ( ! isset( $args->theme_location ) || 'menu-1' !== $args->theme_location ) {
		return $output;
	}

	if ( in_array( 'mobile-parent-nav-menu-item', $item->classes, true ) && isset( $item->original_id ) ) {
		// Inject the keyboard_arrow_left SVG inside the parent nav menu item, and let the item link to the parent item.
		// @todo Only do this for nested submenus? If on a first-level submenu, then really the link could be "#" since the desire is to remove the target entirely.
		$link = sprintf(
			'<span class="menu-item-link-return" tabindex="-1">%s',
			twentynineteen_get_icon_svg( 'chevron_left', 24 )
		);

		// replace opening <a> with <span>
		$output = preg_replace(
			'/<a\s.*?>/',
			$link,
			$output,
			1 // Limit.
		);

		// replace closing </a> with </span>
		$output = preg_replace(
			'#</a>#i',
			'</span>',
			$output,
			1 // Limit.
		);

	} elseif ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

		// Add SVG icon to parent items.
		$icon = twentynineteen_get_icon_svg( 'keyboard_arrow_down', 24 );

		$output .= sprintf(
			'<span class="submenu-expand" tabindex="-1">%s</span>',
			$icon
		);
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'twentynineteen_add_dropdown_icons', 10, 4 );

/**
 * Create a nav menu item to be displayed on mobile to navigate from submenu back to the parent.
 *
 * This duplicates each parent nav menu item and makes it the first child of itself.
 *
 * @param array  $sorted_menu_items Sorted nav menu items.
 * @param object $args              Nav menu args.
 * @return array Amended nav menu items.
 */
function twentynineteen_add_mobile_parent_nav_menu_items( $sorted_menu_items, $args ) {
	static $pseudo_id = 0;
	if ( ! isset( $args->theme_location ) || 'menu-1' !== $args->theme_location ) {
		return $sorted_menu_items;
	}

	$amended_menu_items = array();
	foreach ( $sorted_menu_items as $nav_menu_item ) {
		$amended_menu_items[] = $nav_menu_item;
		if ( in_array( 'menu-item-has-children', $nav_menu_item->classes, true ) ) {
			$parent_menu_item                   = clone $nav_menu_item;
			$parent_menu_item->original_id      = $nav_menu_item->ID;
			$parent_menu_item->ID               = --$pseudo_id;
			$parent_menu_item->db_id            = $parent_menu_item->ID;
			$parent_menu_item->object_id        = $parent_menu_item->ID;
			$parent_menu_item->classes          = array( 'mobile-parent-nav-menu-item' );
			$parent_menu_item->menu_item_parent = $nav_menu_item->ID;

			$amended_menu_items[] = $parent_menu_item;
		}
	}

	return $amended_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'twentynineteen_add_mobile_parent_nav_menu_items', 10, 2 );

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

/**
 * Define and register starter content to showcase the theme on new sites.
 *
 * @return array $starter_content Array of starter content.
 */
function twentynineteen_starter_content() {

	$starter_content = array(

		// Specify the core-defined and custom pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'front' => array(
				'post_type' => 'page',
				'post_title' => _x( 'Business site example', 'Theme starter content', 'twentynineteen' ),
				'post_content' => join(
					'',
					array(
						'<!-- wp:cover {"url":"' . get_theme_file_uri( '/img/placeholder-image-landscape.png' ) . '","align":"full","contentAlign":"left","id":784,"dimRatio":70} -->',
						'<div class="wp-block-cover has-background-dim-70 has-background-dim has-left-content alignfull has-undefined-content" style="background-image:url(' . get_theme_file_uri( '/img/placeholder-image-landscape.png' ) . ')"><p class="wp-block-cover-text">Digital strategies for unique small businesses</p></div>',
						'<!-- /wp:cover -->',

						'<!-- wp:paragraph -->',
						'<p>' . _x( 'We help startups define a clear brand identity and digital strategy that will carry them through their financing rounds and scale as their business grows. This is an example of a page. Unlike posts, which are displayed on your blog&rsquo;s front page in the order they&rsquo;re published, pages are better suited for more timeless content that you want to be easily accessible, like your About or Contact information. Click the Edit link to make changes to this page or add another page after that one.', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:heading -->',
						'<h2>' . _x( 'Services', 'Theme starter content', 'twentynineteen' ) . '</h2>',
						'<!-- /wp:heading -->',

						'<!-- wp:columns --><div class="wp-block-columns has-2-columns">',
						'<!-- wp:column -->',
						'<div class="wp-block-column">',
						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Website Design', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Mobile  Apps', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Social Media Strategy', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',
						'</div><!-- /wp:column -->',

						'<!-- wp:column -->',
						'<div class="wp-block-column">',
						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Marketing', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Copywriting', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:paragraph -->',
						'<p>' . _x( 'Content Strategy', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph --></div>',
						'<!-- /wp:column -->',
						'</div><!-- /wp:columns -->',

						'<!-- wp:heading -->',
						'<h2>' . _x( 'Case Studies', 'Theme starter content', 'twentynineteen' ) . '</h2>',
						'<!-- /wp:heading -->',

						'<!-- wp:pullquote {"align":"full","className":"is-style-solid-color"} -->',
						'<figure class="wp-block-pullquote alignfull is-style-solid-color"><blockquote><p><strong>' . _x( 'Redifining a Brand', 'Theme starter content', 'twentynineteen' ) . '</strong></p><cite>' . _x( 'We help startups define a clear brand identity and digital strategy that will scale as their business grows.', 'Theme starter content', 'twentynineteen' ) . '</cite></blockquote></figure>',
						'<!-- /wp:pullquote -->',

						'<!-- wp:pullquote {"customMainColor":"#0e0f0f","align":"full","className":"is-style-solid-color"} -->',
						'<figure class="wp-block-pullquote alignfull is-style-solid-color" style="background-color:#0e0f0f"><blockquote><p><strong>' . _x( 'Activating new customers', 'Theme starter content', 'twentynineteen' ) . '</strong></p><cite>' . _x( 'We help startups define a clear brand identity and digital strategy that will scale as their business grows.', 'Theme starter content', 'twentynineteen' ) . '</cite></blockquote></figure>',
						'<!-- /wp:pullquote -->',

						'<!-- wp:pullquote {"customMainColor":"#e8e8e8","customTextColor":"#18191a","align":"full","className":"is-style-solid-color"} -->',
						'<figure class="wp-block-pullquote alignfull is-style-solid-color" style="background-color:#e8e8e8"><blockquote class="has-text-color" style="color:#18191a"><p><strong>' . _x( 'Sparking interest on social media', 'Theme starter content', 'twentynineteen' ) . '</strong></p><cite>' . _x( 'We help startups define a clear brand identity and digital strategy that will scale as their business grows.', 'Theme starter content', 'twentynineteen' ) . '</cite></blockquote></figure>',
						'<!-- /wp:pullquote -->',

						'<!-- wp:heading -->',
						'<h2>' . _x( 'Our Leadership', 'Theme starter content', 'twentynineteen' ) . '</h2>',
						'<!-- /wp:heading -->',

						'<!-- wp:gallery {"columns":3,"align":"wide"} -->',
						'<ul class="wp-block-gallery alignwide columns-3 is-cropped"><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri( '/img/placeholder-image-portrait.png' ) . '" alt="placeholder" data-id="" data-link="" class=""/><figcaption>' . _x( 'Ava Young, Founder', 'Theme starter content', 'twentynineteen' ) . '</figcaption></figure></li><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri( '/img/placeholder-image-portrait.png' ) . '" alt="placeholder" data-id="" data-link="" class=""/><figcaption>' . _x( 'Doug Watson, Creative Director', 'Theme starter content', 'twentynineteen' ) . '</figcaption></figure></li><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri( '/img/placeholder-image-portrait.png' ) . '" alt="placeholder" data-id="" data-link="" class=""/><figcaption>' . _x( 'Taco, Good Dog', 'Theme starter content', 'twentynineteen' ) . '</figcaption></figure></li></ul>',
						'<!-- /wp:gallery -->',

						'<!-- wp:pullquote {"align":"wide"} -->',
						'<figure class="wp-block-pullquote alignwide"><blockquote><p>' . _x( '&rdquo;Ava&rsquo;s team was essential to our online success&rdquo;', 'Theme starter content', 'twentynineteen' ) . '</p><cite>' . _x( '&emdash; A Happy Customer', 'Theme starter content', 'twentynineteen' ) . '<br></cite></blockquote></figure>',
						'<!-- /wp:pullquote -->',

						'<!-- wp:columns -->',
						'<div class="wp-block-columns has-2-columns">',
						'<!-- wp:column -->',
						'<div class="wp-block-column">',
						'<!-- wp:heading -->',
						'<h2>' . _x( 'Get in touch', 'Theme starter content', 'twentynineteen' ) . '</h2>',
						'<!-- /wp:heading -->',
						
						'<!-- wp:paragraph {"fontSize":"medium"} -->',
						'<p class="has-medium-font-size">' . _x( 'Discover how we can boost your brand with a unique and powerful digital marketing strategy.', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:button -->',
						'<div class="wp-block-button"><a class="wp-block-button__link" href="#">' . _x( 'Contact Us', 'Theme starter content', 'twentynineteen' ) . '</a></div>',
						'<!-- /wp:button -->',
						'</div><!-- /wp:column -->',

						'<!-- wp:column -->',
						'<div class="wp-block-column">',
						'<!-- wp:heading -->',
						'<h2>' . _x( 'Visit our office', 'Theme starter content', 'twentynineteen' ) . '</h2>',
						'<!-- /wp:heading -->',

						'<!-- wp:paragraph {"fontSize":"medium"} -->',
						'<p class="has-medium-font-size">' . _x( '4324 Buena Vista Drive<br>San Francisco, CA 01234', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',

						'<!-- wp:paragraph {"fontSize":"medium"} -->',
						'<p class="has-medium-font-size">' . _x( 'Monday–Friday: 8AM-6PM<br>Saturday–Sunday: By Appointment', 'Theme starter content', 'twentynineteen' ) . '</p>',
						'<!-- /wp:paragraph -->',
						'</div><!-- /wp:column -->',
						'</div><!-- /wp:columns -->',
					)
				),
			),
			'blog',
			'about' => array(
				'thumbnail' => '{{placeholder-landscape}}',
			),
			'contact',
		),
		
		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'placeholder-landscape' => array(
				'post_title' => _x( 'Landscape image', 'Theme starter content', 'twentynineteen' ),
				'file' => 'img/placeholder-image-landscape.png', // URL relative to the template directory.
			),
			'placeholder-portrait' => array(
				'post_title' => _x( 'Portrait image', 'Theme starter content', 'twentynineteen' ),
				'file' => 'img/placeholder-image-portrait.png',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{front}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "menu-1" location.
			'menu-1' => array(
				'name' => __( 'Primary', 'twentynineteen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_blog',
					'page_about',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name' => __( 'Social Links', 'twentynineteen' ),
				'items' => array(
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Nineteen array of starter content.
	 *
	 * @param array $starter_content Array of starter content.
	 */
	return apply_filters( 'twentynineteen_starter_content', $starter_content );
}
