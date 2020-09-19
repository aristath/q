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
$q_gutenberg_require = new \QTheme\RequireGutenberg();
$q_gutenberg_require->run();

/**
 * Add theme-supports.
 */
add_action(
	'after_setup_theme',
	function() {

		$supports = [
			'title-tag',
			'align-wide',
			// 'wp-block-styles',
			'responsive-embeds',
			'custom-units',
			'experimental-link-color',
			'experimental-custom-spacing',
			'widgets-block-editor',
			'block-nav-menus',
		];
		foreach ( $supports as $support ) {
			add_theme_support( $support );
		}

		// Support a custom color palette.
		add_theme_support(
			'editor-color-palette',
			[
				[
					'name'  => __( 'Black', 'q' ),
					'slug'  => 'black',
					'color' => '#000000',
				],
				[
					'name'  => __( 'White', 'q' ),
					'slug'  => 'white',
					'color' => '#ffffff',
				],
			]
		);
	}
);

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add the block-styles loader.
require_once 'includes/BlockStyles.php';
new \QTheme\BlockStyles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();
