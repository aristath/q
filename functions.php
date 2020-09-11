<?php

// Require Gutenberg to be installed as a plugin with the FSE experiment enabled.`
require_once 'includes/require-gutenberg.php';

add_action( 'after_setup_theme', function() {

    $supports = [
        'title-tag',
        'align-wide',
        // 'wp-block-styles',
        'responsive-embeds',
        'custom-line-height',
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
    add_theme_support( 'editor-color-palette', [
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
    ] );
} );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'q-styles', get_stylesheet_uri(), [], filemtime( get_theme_file_path( 'style.css' ) ) );
} );

// Enqueue editor styles.
add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_style( 'q-styles', get_stylesheet_uri() );
    wp_enqueue_style( 'q-styles-editor', get_template_directory_uri() . '/editor.css' );
} );
