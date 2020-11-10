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
		'https://fonts.googleapis.com/css2?family=Literata:wght@200..900&display=swap',
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
		foreach ( $this->styles as $style ) {
			include get_theme_file_path( "styles/$style.css" );
		}

		// Add webfonts.
		foreach ( $this->webfonts as $webfont ) {
			echo wptt_get_webfont_styles( $webfont ); // phpcs:ignore
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
}
