<?php
/*
    Plugin Name: Everest Timeline
    Version: 0.1-alpha
    Description: PLUGIN DESCRIPTION HERE
    Author: Abderrahmane AYAD
    Author URI: YOUR SITE HERE
    Plugin URI: PLUGIN SITE HERE
    Text Domain: everesttimeline
    Domain Path: /languages
*/

require dirname( __FILE__ ) . '/post-types/everesttimeline.php';
require 'generate-shortcode.php';
require 'timeline-order.php';


class EverestTimeline
{
    /**
     *
     */
    private $_field_name = '_tet_date';

    /**
     *
     */
    private $_post_type = 'everesttimeline';

    /**
     *
     */
    public function __construct()
    {
        // Add metaboxes and saving
        add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );
        add_action( 'save_post', array( &$this, 'save_all_metaboxes' ) );
    }

    /**
     *
     */
    public function add_metaboxes( $post_type )
    {
        if ( $post_type != $this->_post_type )
            return;

        // add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args )
        add_meta_box(
            $this->_field_name,
            'Date',
            array( &$this, 'render_metaboxes_fields' ),
            $post_type, 'normal', 'high'
        );
    }

    /**
     *
     */
    public function render_metaboxes_fields( $post )
    {
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'everest_timeline_plugin', 'everest_timeline_plugin_nonce' );

        // Use get_post_meta to retrieve an existing value from the database.
        $timeline_date = get_post_meta( $post->ID, $this->_field_name, true ); ?>

        <table>
            <tr>
                <td>
                    <label for="timelinedate">Date</label>
                </td>
                <td>
                    <input type="text" id="timelinedate" name="<?php echo $this->_field_name; ?>" value="<?php echo esc_attr( $timeline_date ); ?>">
                </td>
            </tr>
        </table>

        <?php
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_all_metaboxes( $post_id )
    {
        global $post;

        if ( ! isset( $_POST['everest_timeline_plugin_nonce'] ) )
            return;

        $value_to_store = sanitize_text_field( $_POST[ $this->_field_name ] );

        update_post_meta( $post_id, $this->_field_name, $value_to_store );
    }
}

new EverestTimeline;
