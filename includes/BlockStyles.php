<?php
/**
 * Handle block styles enqueuing and inlining.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

namespace QTheme;

/**
 * Handle blocks styles.
 */
class BlockStyles {

	/**
	 * An array of block styles already added.
	 *
	 * @static
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected static $blocks = [];

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'render_block', [ $this, 'render_block' ], 10, 2 );
	}

	/**
	 * Print inline styles for blocks that need it.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $html  The block HTML contents.
	 * @param array  $block The block.
	 * @return string       Returns the HTML.
	 */
	public function render_block( $html, $block ) {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		if ( ! in_array( $block['blockName'], self::$blocks, true ) ) {
			$path = get_theme_file_path( "styles/blocks/{$block['blockName']}.css" );
			if ( file_exists( $path ) ) {
				echo '<style>';
				include $path;
				echo '</style>';
			}
			self::$blocks[] = $block['blockName'];
		}
		return $html;
	}
}
