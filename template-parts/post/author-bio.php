<?php
/**
 * The template for displaying Author info
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

if ( (bool) get_the_author_meta( 'description' ) ) : ?>
<div class="author-bio">
	<h2 class="author-title">
<<<<<<< HEAD:template-parts/post/author-info.php
		<span class="author-heading">
			<?php
			printf(
				/* translators: %s: post author */
				__( 'Published by %s', 'twentynineteen' ),
				esc_html( get_the_author() )
			);
			?>
		</span>
=======
		<?php /* translators: %s: author name */ ?>
		<span class="author-heading"><?php echo esc_html( sprintf( __( 'Published by %s', 'twentynineteen' ), get_the_author() ) ); ?></span>
>>>>>>> master:template-parts/post/author-bio.php
	</h2>
	<p class="author-description">
		<?php the_author_meta( 'description' ); ?>
		<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			<?php _e( 'View more posts', 'twentynineteen' ); ?>
		</a>
	</p><!-- .author-description -->
</div><!-- .author-bio -->
<?php endif; ?>
