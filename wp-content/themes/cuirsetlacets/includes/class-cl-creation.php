<?php

class CL_Creation {

	public function update_creation_meta( $post_id, $key, $value ) {
	
		global $wpdb;
		
		if ( $key === NULL ) {
			return ( false );
		}
		$data = array(
			$key => $value
		);
		$return = $wpdb->update( 'cl_creations', $data, $post_id );
		error_log( 'retour' );
		if ( ! $return ) {
			error_log( 'true' );
		}
	}
	
	public function add_creation_meta( $post_id, $key, $value ) {

		global $wpdb;
		
		if ( $key === NULL ) {
			return ( false );
		}
		$data = array(
			'post_id' 	=> $post_id,
			$key		=> $value
		);
		
		$wpdb->insert( 'cl_creations', $data );
	}
	
	public function get_creation_meta( $post_id = 0 ) {
	
		global $wpdb;
		
		if ( $post_id == 0 ) {
			return ( false );
		}
		return $wpdb->get_row( $wpdb->prepare(
			"
			SELECT	*
			FROM	cl_creations
			WHERE	%d
			LIMIT	1
			",
			$post_id
		) );
	}

}