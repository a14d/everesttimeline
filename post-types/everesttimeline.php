<?php

function everesttimeline_init() {
	register_post_type( 'everesttimeline', array(
		'labels'            => array(
			'name'                => __( 'Timeline', 'everesttimeline' ),
			'singular_name'       => __( 'Timeline', 'everesttimeline' ),
			'all_items'           => __( 'Timelines', 'everesttimeline' ),
			'new_item'            => __( 'New Timeline', 'everesttimeline' ),
			'add_new'             => __( 'Add New', 'everesttimeline' ),
			'add_new_item'        => __( 'Add New Timeline', 'everesttimeline' ),
			'edit_item'           => __( 'Edit Timeline', 'everesttimeline' ),
			'view_item'           => __( 'View Timeline', 'everesttimeline' ),
			'search_items'        => __( 'Search timeline', 'everesttimeline' ),
			'not_found'           => __( 'No timeline found', 'everesttimeline' ),
			'not_found_in_trash'  => __( 'No timeline found in trash', 'everesttimeline' ),
			'parent_item_colon'   => __( 'Parent Timeline', 'everesttimeline' ),
			'menu_name'           => __( 'Timeline', 'everesttimeline' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
	) );

}
add_action( 'init', 'everesttimeline_init' );

function everesttimeline_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['everesttimeline'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Everesttimeline updated. <a target="_blank" href="%s">View everesttimeline</a>', 'everesttimeline'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'everesttimeline'),
		3 => __('Custom field deleted.', 'everesttimeline'),
		4 => __('Everesttimeline updated.', 'everesttimeline'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Everesttimeline restored to revision from %s', 'everesttimeline'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Everesttimeline published. <a href="%s">View everesttimeline</a>', 'everesttimeline'), esc_url( $permalink ) ),
		7 => __('Everesttimeline saved.', 'everesttimeline'),
		8 => sprintf( __('Everesttimeline submitted. <a target="_blank" href="%s">Preview everesttimeline</a>', 'everesttimeline'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Everesttimeline scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview everesttimeline</a>', 'everesttimeline'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Everesttimeline draft updated. <a target="_blank" href="%s">Preview everesttimeline</a>', 'everesttimeline'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'everesttimeline_updated_messages' );
