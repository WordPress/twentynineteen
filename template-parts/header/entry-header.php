<?php
/**
 * TODO: File doc comment.
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

if ( ! is_page() ) {
	$discussion = twentynineteen_can_show_post_thumbnail() ? twentynineteen_get_discussion_data() : null;
}

the_title( '<h1 class="entry-title">', '</h1>' );

if ( ! is_page() ) :
?>

	<div class="<?php echo ( ! empty( $discussion ) && count( $discussion->authors ) > 0 ) ? 'entry-meta has-discussion' : 'entry-meta'; ?>">
		<?php 
		twentynineteen_posted_by();
		twentynineteen_estimated_read_time(); 
		?>
		<span class="comment-count">
			<?php
			if ( ! empty( $discussion ) ) {
				twentynineteen_discussion_avatars_list( $discussion->authors );
			}
			twentynineteen_comment_count();
			?>
		</span>
	</div><!-- .entry-meta -->

<?php
endif;
