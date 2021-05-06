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

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();
