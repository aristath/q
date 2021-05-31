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
		add_action( 'wp_head', [ $this, 'remove_embed_script' ] );
		add_action( 'wp_footer', [ $this, 'footer' ] );
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

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function footer() {
		echo '<script>';
		include get_theme_file_path( 'scripts/prefetch-on-hover.js' );
		echo '</script>';
	}
}
