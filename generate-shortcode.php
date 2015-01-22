<?php

class Everest_Shortcode_Generator
{
    /**
     *
     */
    private $_post_type = 'everesttimeline';

    /**
     *
     */
    public function __construct()
    {
        add_action( 'init', array( &$this, 'everest_check_roles' ) );
        add_action( 'init', array( &$this, 'register_shortcode' ) );

        add_action( 'wp_ajax_everest_timeline_generator_ajax', array( &$this, 'everest_timeline_generator_ajax' ) );
    }

    /**
     *
     */
    public function everest_check_roles()
    {
        if ( current_user_can( 'manage_options' ) ) {

            add_action( 'admin_menu', array( &$this, 'add_shortcode_generator_page' ) );
            add_action( 'admin_menu', array( &$this, 'register_settings' ) );

        }
    }

    /**
     *
     */
    public function register_settings()
    {
        register_setting(
            $this->_post_type,                 // $option_group
            $this->_post_type                  // $option_name
        );

        //$this->_options[$post_type] = get_option( $this->_post_type, $this->set_default_options() );

    }

    /**
     *
     */
    public function add_shortcode_generator_page()
    {
        add_submenu_page(
            'edit.php?post_type='. $this->_post_type,
            'Shortcode Generator',
            'Shortcode Generator',
            'manage_options',
            $this->_post_type .'-shortcode-generator',
            array( &$this, 'shortcode_generator_page' )
        );
    }

    /**
     *
     */
    public function shortcode_generator_page()
    {
        ?><div class="wrap">

            <form id="" action="options.php" method="post">

                <?php do_settings_sections( 'everest-timeline-options' ); ?>

                <?php settings_fields( 'everest-timeline-options' ); ?>

                <?php submit_button(); ?>
            </form>

        </div><!-- /.wrap --><?php
    }

    /**
     *
     */
    public function register_shortcode()
    {
        add_shortcode( 'everest-timeline', array( &$this, 'everest_timeline_shortcode' ) );
    }

    /**
     *
     */
    public function everest_timeline_shortcode( $atts )
    {
        global $post;

        $args = array(
            'post_type' => $this->_post_type
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {

            foreach ( $query->posts as $key => $post ) {

                echo get_post_meta( $post->ID, '_tet_date', true );

            }
        }
    }

    /**
     * TODO
     */
    public function everest_timeline_generator_ajax()
    {

    }
}

new Everest_Shortcode_Generator;
