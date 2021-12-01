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
		add_action( 'wp_footer', [ $this, 'footer' ] );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function footer() {
		echo '<script>';
		include get_theme_file_path( 'assets/scripts/prefetch-on-hover.js' );
		echo '</script>';
	}
}
