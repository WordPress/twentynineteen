<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

<<<<<<< HEAD
if ( ! is_page() ) :
	?>
	<?php $discussion = twentynineteen_can_show_post_thumbnail() ? twentynineteen_get_discussion_data() : null; ?>
<?php endif; ?>
=======
$discussion = ! is_page() && twentynineteen_can_show_post_thumbnail() ? twentynineteen_get_discussion_data() : null; ?>

>>>>>>> master
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

<?php if ( ! is_page() ) : ?>
<div class="entry-meta">
	<?php twentynineteen_posted_by(); ?>
	<?php twentynineteen_posted_on(); ?>
	<span class="comment-count">
		<?php
		if ( ! empty( $discussion ) ) {
<<<<<<< HEAD
			twentynineteen_discussion_avatars_list( $discussion->authors );}
=======
			twentynineteen_discussion_avatars_list( $discussion->authors );
		}
>>>>>>> master
		?>
		<?php twentynineteen_comment_count(); ?>
	</span>
	<?php
	// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">' . twentynineteen_get_icon_svg( 'edit', 16 ),
			'</span>'
		);
	?>
</div><!-- .meta-info -->
<?php endif; ?>
