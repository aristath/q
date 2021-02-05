<?php
/**
 * Handle styles enqueuing and inlining.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

namespace QTheme;

/**
 * Styles handler.
 */
class Styles {

	/**
	 * An array of styles to load.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $styles = [
		'base',
		'typography',
		'layout',
		'a11y',
		'comment-form',
		'forms',
		'colors',
	];

	/**
	 * Webfonts URLs.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $webfonts = [
		'https://fonts.googleapis.com/css2?family=Literata:wght@200..900&display=optional',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		require_once get_theme_file_path( 'includes/wptt-webfont-loader.php' );

		add_action( 'wp_head', [ $this, 'head' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Add inline styles for blocks.
	 *
	 * @access public
	 * @since 0.6.2
	 * @return void
	 */
	public function enqueue_scripts() {
		foreach ( glob( get_template_directory() . '/styles/blocks/core/*.css' ) as $filename ) {
			$block = str_replace(
				[ get_template_directory() . '/styles/blocks/core/', '.css' ],
				'',
				$filename
			);
			ob_start();
			include get_template_directory() . "/styles/blocks/core/$block.css";
			$styles = ob_get_clean();
			if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
				$styles = self::minify( $styles );
			}
			wp_add_inline_style( "wp-block-$block", $styles );
		}
	}

	/**
	 * Print styles in <head>.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function head() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}
		echo '<style>';
		ob_start();
		foreach ( $this->styles as $style ) {
			include get_theme_file_path( "styles/$style.css" );
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			echo ob_get_clean(); // phpcs:ignore
		} else {
			echo self::minify( ob_get_clean() ); // phpcs:ignore
		}

		// Add webfonts.
		foreach ( $this->webfonts as $webfont ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				echo wptt_get_webfont_styles( $webfont ); // phpcs:ignore
			} else {
				echo self::minify( wptt_get_webfont_styles( $webfont ) ); // phpcs:ignore
			}
		}
		echo '</style>';
	}

	/**
	 * Enqueue assets for the editor.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function block_editor_assets() {
		foreach ( $this->styles as $style ) {
			wp_enqueue_style(
				"q-$style",
				get_theme_file_uri( "styles/$style.css" ),
				[],
				filemtime( get_theme_file_path( "styles/$style.css" ) )
			);

			if ( file_exists( get_theme_file_path( "styles/$style-editor.css" ) ) ) {
				wp_enqueue_style(
					"q-$style-editor",
					get_theme_file_uri( "styles/$style-editor.css" ),
					[],
					filemtime( get_theme_file_path( "styles/$style.css" ) )
				);
			}
		}

		// Enqueue webfonts.
		foreach ( $this->webfonts as $webfont ) {
			wp_enqueue_style(
				md5( $webfont ),
				wptt_get_webfont_url( $webfont ),
				[],
				filemtime( get_theme_file_path( 'style.css' ) )
			);
		}
	}

	/**
	 * Slightly minify styles.
	 *
	 * Removes inline comments and whitespace.
	 *
	 * @static
	 * @since 0.6.1
	 * @param string $styles The styles we want to minify.
	 * @return string
	 */
	public static function minify( $styles ) {
		$re1 = '(?sx)("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|/\\* (?> .*? \\*/ )';
		$re2 = '(?six)("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|\\s*+ ; \\s*+ ( } ) \\s*+|\\s*+ ( [*$~^|]?+= | [{};,>~+-] | !important\\b ) \\s*+|( [[(:] ) \\s++|\\s++ ( [])] )|\\s++ ( : ) \\s*+(?!(?>[^{}"\']++|"(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')*+{)|^ \\s++ | \\s++ \\z|(\\s)\\s+';

		$styles = preg_replace( "%$re1%", '$1', $styles );
		return preg_replace( "%$re2%", '$1$2$3$4$5$6$7', $styles );
	}
}
