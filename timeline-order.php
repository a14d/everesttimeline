<?php


/**
*
*/
class Timeline_Order
{
    /**
     *
     */
    private $_version = '0.2';

    /**
     *
     */
    public function __construct()
    {
        add_filter( 'posts_orderby', array( &$this, 'everest_timeline_order' ) );
        add_action( 'load-edit.php', array( &$this, 'load_edit_screen' ) );
        add_action( 'wp_ajax_everest_timeline_ordering', array( &$this, 'everest_timeline_ordering' ) );
    }

    /**
    * Call this when WP try to order the posts
    */
    public function everest_timeline_order( $order_by )
    {
        global $wpdb;

        if ( is_post_type_archive( 'everesttimeline' ) ) {

            $order_by = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";

        }

        return $order_by;
    }

    /**
    *
    */
    public function load_edit_screen()
    {
        $screen = get_current_screen();

        if ( $screen->post_type == 'everesttimeline' ) {

            add_filter( 'views_' . $screen->id, array( &$this, 'sort_by_order_link' )  );
            add_action( 'wp', array( &$this, 'load_admin_assets' ) );

        }
    }

    /**
    *
    */
    public function load_admin_assets()
    {
        if ( $this->is_sorting_activated() ) {
            // wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer )
            wp_enqueue_script(
            'everest-timeline-ordering',
            plugins_url( 'assets/js/everest-timeline-ordering.js', __FILE__ ),
            array( 'jquery-ui-sortable' ), '1.0.0', true
            );
        }

        // wp_enqueue_style( $handle, $src, $deps, $ver, $media )
        // wp_enqueue_style(
        //     'everest-team-style', plugins_url( 'css/main.css', __FILE__ ), array(), '1.0.0'
        // );
    }

    /**
    * Generate the link for sorting
    */
    public function sort_by_order_link( $views )
    {
        $css_class = ( get_query_var( 'order' ) == 'true' ) ? 'everest-dragdrop-active' : 'everest-dragdrop-inactive';

        // Build the query to activate the sorting
        $order_query = remove_query_arg( array( 'order' ) );
        $order_query = add_query_arg( 'order', 'true', $order_query );

        // Build the query for inactivating the sorting
        $order_query_inactive = remove_query_arg( array( 'order' ) );

        // Generate the views:  All | Published | ...
        if ( $this->is_sorting_activated() ) {

            $views['order'] = "<a class='$css_class' href='$order_query_inactive'>Drag and Drop Sorting Activated</a>";

        } else {

            $views['order'] = "<a class='$css_class' href='$order_query'>Activate Drag and Drop Sorting</a>";

        }

        return $views;
    }

    /**
    *
    */
    public function everest_timeline_ordering()
    {
        global $wpdb;

        // Check if the wanted data is available
        if (! isset( $_POST['order'] ) )
            return;

        $order = explode( ',', $_POST['order'] );

        foreach( $order as $key => $post_id ) {

            // delete the "post-" part
            $post_id = substr( $post_id, 5 );

            $wpdb->update(
                $wpdb->posts, array( 'menu_order' => $key ), array( 'ID' => ( int ) $post_id )
            );

        }

        exit();
    }

    /**
    * Whether the sorting is activated or not
    */
    private function is_sorting_activated()
    {
        return ( ( strpos( get_query_var( 'order' ), 'true') == 0 ) && isset( $_GET['order'] ) && ( $_GET['order'] == 'true' ) );
    }

    /**
    *
    */
    private function store_post_types_ids( $post_types )
    {
        foreach( $post_types as $post_type => $args ) {

            $this->_post_types_ids[] = $post_type;

        }
    }
}


new Timeline_Order();
