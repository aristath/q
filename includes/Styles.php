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
		'forms',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );

		add_action( 'admin_init', [ $this, 'block_editor_assets' ] );
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

		foreach ( $this->styles as $style ) {
			wp_enqueue_style(
				"q-$style",
				get_theme_file_uri( "assets/styles-min/$style.css" ),
				[],
				wp_get_theme()->get( 'Version' )
			);
			wp_style_add_data( "q-$style", 'path', get_theme_file_path( "assets/styles-min/$style.css" ) );
		}

		foreach ( glob( get_template_directory() . '/assets/styles-min/blocks/core/*.css' ) as $filename ) {
			$block = str_replace(
				[ get_template_directory() . '/assets/styles-min/blocks/core/', '.css' ],
				'',
				$filename
			);

			$styles = file_get_contents( get_template_directory() . "/assets/styles-min/blocks/core/$block.css" );
			wp_add_inline_style( "wp-block-$block", $styles );
		}
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
			add_editor_style( "assets/styles/$style.css" );

			if ( file_exists( get_theme_file_path( "assets/styles/$style-editor.css" ) ) ) {
				add_editor_style( "assets/styles/$style-editor.css" );
			}
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
		$re2 = '(?six)("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|\\s*+ ; \\s*+ ( } ) \\s*+|\\s*+ ( [*$~^|]?+= | [{};,>~] | !important\\b ) \\s*+|\\s*([+-])\\s*(?=[^}]*{)|( [[(:] ) \\s++|\\s++ ( [])] )|\\s+(:)(?![^\\}]*\\{)|^ \\s++ | \\s++ \\z|(\\s)\\s+';
		return preg_replace( [ "%{$re1}%", "%{$re2}%" ], [ '$1', '$1$2$3$4$5$6$7$8' ], $styles );
	}
}
