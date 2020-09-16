<?php

namespace QTheme;

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
    ];

    /**
     * Constructor.
     * 
     * @access public
     * @since 1.0
     */
    public function __construct() {
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
    }
}