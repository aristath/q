<?php
/**
 * Functions and hooks for the Q theme.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

// Require Gutenberg to be installed as a plugin with the FSE experiment enabled.
require_once 'includes/RequireGutenberg.php';
( new \QTheme\RequireGutenberg() )->run();

/**
 * Add theme-supports.
 */
add_action(
	'after_setup_theme',
	function() {

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-units' );
		add_theme_support( 'experimental-link-color' );
		add_theme_support( 'experimental-custom-spacing' );
		add_theme_support( 'widgets-block-editor' );
		add_theme_support( 'block-nav-menus' );
		add_theme_support( 'wp-block-styles' );
	}
);

add_filter(
	'comment_form_defaults',
	function( $fields ) {
		$fields['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s wp-block-button__link" value="%4$s" />';
		$fields['submit_field']  = '<p class="form-submit wp-block-button">%1$s %2$s</p>';

		return $fields;
	}
);

/**
 * Show '(no title)' in frontend if post has no title to make it selectable
 */
add_filter(
	'the_title',
	function( $title ) {
		if ( ! is_admin() && empty( $title ) ) {
			$title = __( '(no title)', 'q' );
		}

		return $title;
	}
);

if ( ! function_exists( 'gutenberg_do_block_template_part' ) ) {
	/**
	 * Print a template-part.
	 *
	 * @param string $part The template-part to print. Use "header" or "footer".
	 *
	 * @see https://github.com/WordPress/gutenberg/pull/30345
	 *
	 * @return void
	 */
	function gutenberg_do_block_template_part( $part ) {
		if ( ! function_exists( 'gutenberg_get_block_template' ) ) {
			return;
		}
		$template_part = gutenberg_get_block_template( get_stylesheet() . '//' . $part, 'wp_template_part' ); // @phpstan-ignore-line
		if ( ! $template_part || empty( $template_part->content ) ) {
			return;
		}
		echo do_blocks( $template_part->content ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();
