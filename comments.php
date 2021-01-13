<?php
/**
 * Comments template
 *
 * @package aristath/q
 *
 * @since 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$tt1_blocks_comment_count = get_comments_number();
?>

<div id="comments" class="comments-area <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">

	<?php
	if ( have_comments() ) :
		;
		?>
		<h2 class="comments-title">
			<?php if ( '1' === $tt1_blocks_comment_count ) : ?>
				<?php esc_html_e( '1 comment', 'q' ); ?>
			<?php else : ?>
				<?php
				printf(
					/* translators: %s: comment count number. */
					esc_html( _nx( '%s comment', '%s comments', $tt1_blocks_comment_count, 'Comments title', 'q' ) ),
					esc_html( number_format_i18n( $tt1_blocks_comment_count ) )
				);
				?>
			<?php endif; ?>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
			wp_list_comments(
				[
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
				]
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_pagination();
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'q' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

</div><!-- #comments -->
