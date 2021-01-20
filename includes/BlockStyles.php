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
	 * The class constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'inline_core_block_styles' ], 1 );
	}

	/**
	 * Inline core block styles.
	 *
	 * @access public
	 * @since 0.6.1
	 * @return void
	 */
	public function inline_core_block_styles() {
		global $wp_styles;
		foreach ( $wp_styles->queue as $queued ) {
			if ( 0 === strpos( $queued, 'wp-block-' ) && isset( $wp_styles->registered[ $queued ] ) ) {
				$style = $wp_styles->registered[ $queued ];

				$path = str_replace( trailingslashit( site_url() ), trailingslashit( ABSPATH ), $style->src );

				if ( file_exists( $path ) ) {

					echo '<style id="' . esc_attr( $queued ) . '-css">';
					echo $this->minify( file_get_contents( $path ) ); // phpcs:ignore WordPress.Security.EscapeOutput, WordPress.WP.AlternativeFunctions

					if ( is_array( $style->extra ) && isset( $style->extra['after'] ) ) {
						echo implode( '', $style->extra['after'] ); // phpcs:ignore WordPress.Security.EscapeOutput
					}

					echo '</style>';

					unset( $wp_styles->registered[ $queued ] );
				}
			}
		}
	}

	/**
	 * Slightly minify styles.
	 *
	 * Removes inline comments and whitespace.
	 *
	 * @since 0.6.1
	 * @param string $styles The styles we want to minify.
	 * @return string
	 */
	public function minify( $styles ) {
		$re1 = '(?sx)("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|/\\* (?> .*? \\*/ )';
		$re2 = '(?six)("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|\\s*+ ; \\s*+ ( } ) \\s*+|\\s*+ ( [*$~^|]?+= | [{};,>~+-] | !important\\b ) \\s*+|( [[(:] ) \\s++|\\s++ ( [])] )|\\s++ ( : ) \\s*+(?!(?>[^{}"\']++|"(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')*+{)|^ \\s++ | \\s++ \\z|(\\s)\\s+';

		$styles = preg_replace( "%$re1%", '$1', $styles );
		return preg_replace( "%$re2%", '$1$2$3$4$5$6$7', $styles );
	}
}
