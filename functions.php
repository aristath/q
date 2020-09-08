<?php

// Require Gutenberg to be installed as a plugin with the FSE experiment enabled.`
require_once 'includes/require-gutenberg.php';

add_action( 'after_setup_theme', function() {

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Alignwide and alignfull classes in the block editor
    add_theme_support( 'align-wide' );

    // Adding support for core block visual styles.
    // add_theme_support( 'wp-block-styles' );

    // Adding support for responsive embedded content.
    add_theme_support( 'responsive-embeds' );

    // Add support for custom line height controls.
    add_theme_support( 'custom-line-height' );

    // Add support for experimental link color control.
    add_theme_support( 'experimental-link-color' );

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
	wp_enqueue_style( 'q-styles', get_stylesheet_uri() );
} );

// Enqueue editor styles.
add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_style( 'q-styles', get_stylesheet_uri() );
    wp_enqueue_style( 'q-block-styles', get_template_directory_uri() . '/editor.css' );
} );
