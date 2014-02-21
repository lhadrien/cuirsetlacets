<?php

function cuirs_init() {

	register_nav_menus(
		array(
		  'menu-principal' => __( 'Menu Principal' ),
		  'menu-boutique' => __( 'Menu Boutique' )
		)
	);
	wp_enqueue_script( 'bootstrap', WP_THEME . '/bootstrap/' . BOOTSTRAP_VERSION . '/js/bootstrap.min.js', null, null, true);
	
	// Creations
	register_post_type( 'creations', array(
			'labels' => array(
				'name'					=> __( 'Creations Wapapa' ),
				'singular_name' 		=> __( 'Creation' ),
				'add_new'				=> __( 'Ajouter une Creation' ),
				'edit_item'				=> __( 'Editer une Creation' ),
				'menu_name'				=> __( 'Creations' ),
				'view_item'				=> __( 'Voir les Creations' ),
				'search_items'			=> __( 'Gno, on cherche' ),
				'not_found'				=> __( "Ouin, rien n'a ete trouve" ),
				'not_found_in_trash'	=> __( 'Rien dans la corbeille' )
			),
			'public'				=> true,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'menu_position'			=> 5,
			'has_archive'			=> true,
			'hierarchical'			=> true,
			'label'					=> 'Question',
			'rewrite'				=> array(
				'slug'			=> 'creations',
				'with_front' 	=> true
			),
			'supports'		=> array(
				'title',
				'editor',
				'custom-fields',
				'thumbnail'
			),
			'register_meta_box_cb' => 'add_events_metaboxes'
		)
	);
	register_taxonomy("type", array("creations"), array(
				"hierarchical"		=> true,
				"label"				=> "Types",
				"singular_label" 	=> "Type",
				"rewrite" 			=> true
				)
	);

}


function save_creations_meta( $post_id, $post ) {

	global $cl_creation;
	$creation_meta = array();
	// check if we save from the editor
	if ( ! wp_verify_nonce( $_POST[ 'edit_custom_creation' ], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	// authorized ?
	if ( ! current_user_can( 'edit_post', $post->ID ) )
		return $post->ID;
	// make an array
	$creation_meta[ 'content_fr' ] = $_POST[ 'content_fr' ];
	$creation_meta[ 'content_en' ] = $_POST[ 'content_en' ];
	// Add values of $events_meta as custom fields
	foreach ( $creation_meta as $key => $value ) { // Cycle through the $events_meta array!
	
		if ( $post->post_type == 'revision' ) {
			return ( false ); // Don't store custom data twice
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		
		if ( $cl_creation->get_creation_meta( $post->ID ) ) { // If the post exist
			error_log( "im here" );
			error_log( $value);
			error_log( $key);
			$cl_creation->update_creation_meta( $post->ID, $key, $value );
		} else { // If the does not exist
			$cl_creation->add_creation_meta( $post->ID, $key, $value );
		}
	}

}

add_action( 'save_post', 'save_creations_meta', 1, 2 ); // save the custom fields


function description_meta() {

	global $post, $cl_creation;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="edit_custom_creation" id="edit_custom_creation" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	$metas = $cl_creation->get_creation_meta( $post->ID );
	$content_fr = $metas->content_fr;
	$content_en = $metas->content_en;
	?>
		<p><label>Description FR :</label><br />
		<textarea cols="50" rows="5" name="content_fr" placeholder="description en francais"><?php echo $content_fr; ?></textarea></p>
		<p><label>Description EN :</label><br />
		<textarea cols="50" rows="5" name="content_en" placeholder="description en anglais"><?php echo $content_en; ?></textarea></p>
	<?php
}

function year_completed(){

	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="edit_custom_creation" id="edit_custom_creation" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$location = get_post_meta( $post->ID, '_location', true );
	// Echo out the field
	echo '<input type="text" name="_location" value="' . $location  . '" class="widefat" />';
}

function add_events_metaboxes() {


}