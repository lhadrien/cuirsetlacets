<?php

class CL_Custom_type extends CL_Abstract {

    public $type_post = '';
    public $arr_post_type = array(
        'fourreaux'                 => 8,
        'scabbards'                 => 8,
        'ceintures'                 => 9,
        'girdles'                   => 9,
        'escarcelles-et-bourses'    => 10,
        'purses_pouchs'             => 10,
        'divers'                    => 12,
        'miscellaneous'             => 12
    );
	
    private $arr_name_type = array();
	
    public function update_cuirs_meta( $post_id, $key, $value, $table )
	{
        if ( $key === NULL ) {
            return ( false );
        }
        $data = array(
            $key => $value
        );
        $where = array(
            'post_id' => $post_id
        );
        $return = $this->wpdb->update( 'cl_' . $table, $data, $where );
        return ( $return );
    }
	
    public function add_cuirs_meta( $post_id, $key, $value, $table )
	{

        global $wpdb;

        if ( $key === NULL ) {
            return ( false );
        }
        $data = array(
            'post_id'   => $post_id,
            $key        => $value
        );
        $wpdb->insert( 'cl_' . $table, $data );
    }
	
    public function get_cuirs_meta( $post_id, $table )
	{

        global $wpdb;

        if ( $post_id == null ) {
            return ( false );
        }
        if ( ! $table ) {
            $table = $this->type_post;
        }
        $table = 'cl_' . $table;
        return $wpdb->get_row( $wpdb->prepare(
            "
            SELECT  *
            FROM    " . $table . "
            WHERE   post_id = %d
            LIMIT   1
            ",
            $post_id
        ) );
    }
	
    public function get_sites_amis()
	{

        global $wpdb;

        $limit = 100;

        return $wpdb->get_results( $wpdb->prepare(
            "
            SELECT  *
            FROM    cl_sites_amis
            LIMIT   %d
            ",
            $limit
        ) );
    }
	
    private function get_all_creations()
	{

        global $wpdb;

        $limit = 1000;

        return $wpdb->get_results( $wpdb->prepare(
            "
            SELECT  *
            FROM    cl_term_relationships r
            JOIN    cl_creations c ON c.post_id = r.object_id
            LIMIT   %d
            ",
            $limit
        ) );
    }
    
    private function get_creation_by_id( $id )
	{

        return $this->wpdb->get_row( $this->wpdb->prepare(
            "
            SELECT  *
            FROM    cl_creations
            WHERE   post_id = %d
            ",
            $id
        ) );
    }
	
    public function get_creations_by_type( $tax_id, $limit = 1000 )
	{
        return $this->wpdb->get_results( $this->wpdb->prepare(
            "
            SELECT  *
            FROM    cl_term_relationships r
            JOIN    cl_creations c ON c.post_id = r.object_id
            WHERE   r.term_taxonomy_id = %d
            LIMIT   %d
            ",
            $tax_id,
            $limit
        ) );
    }
	
    public function get_creations()
	{
        if ( ! isset( $this->post->post_name ) ) {
            return ( false );
        }
        if ( array_key_exists( $this->post->post_name, $this->arr_post_type ) ) {
            $creations = $this->get_creations_by_type( $this->arr_post_type[ $this->post->post_name ] );
            // gerer les creations
        } else {
            $creations = $this->get_all_creations();
        }
        if ( count( $creations ) === 0 ) {
            _cl( 'Pas de creations encore', 'No creations yet' );
            return ( false );
        }
        return ( $creations );
    }
    
    public function get_creation()
	{

        if ( ! isset( $this->post->ID ) ) {
            return ( false );
        }
        $creation = $this->get_creation_by_id( $this->post->ID );
        return ( $creation );
    }
	
    public function get_images( $post_id = 0 )
	{

        if ( $post_id === 0 ) {
            return ( false );
        }
        return $this->wpdb->get_results( $this->wpdb->prepare(
            "
            SELECT  ID, post_parent AS post_id,
                    guid AS link, post_mime_type, post_title
            FROM    cl_posts
            WHERE   post_parent = %d
            AND     post_type = 'attachment'
            ",
            $post_id
        ) );
    }
    
    public function get_cat_by_lang( $lang = 'fr' )
    {
        $arr = array();
        
        $offset = ( $lang === 'fr' ) ? 0 : 1;
        
        foreach ($this->arr_post_type as $name => $value) {
            if ( $offset === 0 ) {
                $arr[$name] = $value;
                $offset = 1;
            } else {
                $offset = 0;
            }
        }
        return $arr;
    }
    
    
    private function undo_size( $image )
    {
        $pos1 = strpos( $image, 'width="');
        if ( $pos1 !== false ) {
            $part1 = substr( $image, 0, $pos1 );
            $rest = substr( $image, $pos1 + 7 );
            $pos2 = strpos( $rest, '"' );
            $part2 = substr( $rest, $pos2 + 1 );
            $image = $part1 . $part2;
        }
        $pos3 = strpos( $image, 'height="');
        if ( $pos3 !== false ) {
            $part1 = substr( $image, 0, $pos3 );
            $rest = substr( $image, $pos3 + 8 );
            $pos2 = strpos( $rest, '"' );
            $part2 = substr( $rest, $pos2 + 1 );
            $image = $part1 . $part2;
        }
        return $image;
    }
    
    public function get_images_size( $post_id, $size = 'large', $single = false )
    {
        $images = $this->get_images( $post_id );
        $count = count($images);
        if ( $count < 1 ) {
            return false;
        } elseif ( $single ) {
            return $this->undo_size(wp_get_attachment_image( $images{ 0 }->ID, $size ));
        }
        for ( $i = 0; $i < $count; $i++ ) {
            $images{ $i }->link_size = $this->undo_size( wp_get_attachment_image( $images{ $i }->ID, $size ) );
        }
        return $images;
    }
}