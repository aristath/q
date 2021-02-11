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
}
