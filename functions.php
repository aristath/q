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
add_theme_support( 'responsive-embeds' );

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();

// Opt-in to separate styles loading.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );
add_filter(
	'styles_inline_size_limit',
	function( $size ) {
		return 50000;
	}
);
