<?php
/**
 * This file is empty on purpose to avoid errors with WP-Core
 * not allowing themes without a comments.php file.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

if ( have_comments() ) :
	?>
	<h3 id="comments">
		<?php
		if ( 1 === get_comments_number() ) {
			printf(
				/* translators: %s: Post title. */
				esc_html__( 'One response to %s', 'q' ),
				'&#8220;' . esc_html( get_the_title() ) . '&#8221;'
			);
		} else {
			printf(
				/* translators: 1: Number of comments, 2: Post title. */
				esc_html( _n( '%1$s response to %2$s', '%1$s responses to %2$s', get_comments_number(), 'q' ) ),
				esc_html( number_format_i18n( get_comments_number() ) ),
				'&#8220;' . esc_html( get_the_title() ) . '&#8221;'
			);
		}
		?>
	</h3>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments(); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
<?php else : // This is displayed if there are no comments so far. ?>

	<?php if ( ! comments_open() ) : ?>
		<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'q' ); ?></p>
	<?php endif; ?>
<?php endif; ?>