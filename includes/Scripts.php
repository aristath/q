<?php
/**
 * Handle scripts enqueuing and inlining.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

namespace QTheme;

/**
 * Scripts handler.
 */
class Scripts {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'skip_link' ] );
		add_action( 'wp_head', [ $this, 'remove_embed_script' ] );
	}

	/**
	 * Print a skip link.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function skip_link() {
		echo '<script>';
		echo 'window.skipToContent="' . esc_html__( 'Skip to content', 'q' ) . '";';
		include get_theme_file_path( 'scripts/skip-link.js' );
		echo '</script>';
	}

	/**
	 * Removes the embed script if there are no iframes.
	 *
	 * @access public
	 * @since 0.6.3
	 * @return void
	 */
	public function remove_embed_script() {
		global $template_html;

		if ( $template_html && ! strpos( $template_html, '<iframe' ) ) {
			wp_dequeue_script( 'wp-embed' );
		}
	}
}
