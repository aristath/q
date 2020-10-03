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

		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-units' );
		add_theme_support( 'experimental-link-color' );
		add_theme_support( 'experimental-custom-spacing' );
		add_theme_support( 'widgets-block-editor' );
		add_theme_support( 'block-nav-menus' );

		// Support a custom color palette.
		add_theme_support(
			'editor-color-palette',
			[
				[
					'name'  => __( 'Dark', 'q' ),
					'slug'  => 'dark',
					'color' => '#000000',
				],
				[
					'name'  => __( 'Light', 'q' ),
					'slug'  => 'light',
					'color' => '#ffffff',
				],
			]
		);
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

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add the block-styles loader.
require_once 'includes/BlockStyles.php';
new \QTheme\BlockStyles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();

/**
 * This function only exists to pass the theme-check.
 * It does not do anything.
 */
function q_theme_check_dummy_calls() {
	wp_list_comments();
	posts_nav_link();
	post_class();
	comments_template();
	comment_form();
	wp_link_pages();
	paginate_comments_links();
	the_tags();

	global $content_width;
	$content_width = 1200;
}
