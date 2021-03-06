<?php

/**
 * 
 */
class CL_Category extends CL_Abstract {

    private $arr_menu_title = array();
    private $arr_menu_url = array();
    private $categories;

    /**
     * 
     * @global type $cl_lang
     * @return type
     */
    public function get_menu_title() {

        global $cl_lang;

        if ( $cl_lang->fr ) {
            $menu = array(
                'Les Créations',
                'La boutique',
                'Fourreaux',
                'Escarcelles & Bourses',
                'Ceintures',
                'Divers'
            );
        } else {
            $menu = array(
                'The Creations',
                'The shop',
                'Scabbards',
                'Purses & Pouchs',
                'Girdles',
                'Miscelaneous'
            );
        }
        return ( $menu );
    }

    /**
     * 
     * @global type $cl_lang
     * @return type
     */
    public function get_menu_urls() {

        global $cl_lang;

        if ( $cl_lang->fr ) {
            $urls = array(
                '/boutique/',
                '/boutique/fourreaux/',
                '/boutique/escarcelles-et-bourses/',
                '/boutique/ceintures/',
                '/boutique/divers/'
            );
        } else {
            $urls = array(
                '/shop/',
                '/shop/scabbards/',
                '/shop/purses_pouchs/',
                '/shop/girdles/',
                '/shop/miscellaneous/'
            );
        }
        return ( $urls );
    }

    /**
     * 
     * @param type $cat
     * @param type $is_css
     * @return type
     */
    public function display_title_cat( $cat, $is_css = false ) {

        $this->arr_name_type = $this->get_name_cat( $is_css );

        if ( isset( $this->arr_name_type[ $cat ] ) ) {
            $name = $this->arr_name_type[ $cat ];
            if ( $is_css ) {
                $name = strtolower( $name );
            }
            echo $name;
            return ( true );
        } else {
            _cl( 'Autre', 'Other' );
            return ( false );
        }
    }

    /**
     * 
     * @global type $wpdb
     * @global type $cl_lang
     * @param type $is_css
     * @return type
     */
    public function get_name_cat( $is_css = false, $choose_fr = false ) {

        global $cl_lang;

        $tax = 'type';
        $arr_terms = array();
        if ( empty( $this->categories ) ) {
            $this->categories = $this->wpdb->get_results( $this->wpdb->prepare(
                "
                SELECT  ter.name as name, tax.term_taxonomy_id as term_id
                FROM    cl_term_taxonomy tax
                JOIN    cl_terms ter ON tax.term_id = ter.term_id
                WHERE   tax.taxonomy = %s
                ",
                $tax
            ) );
        }
        $terms = $this->categories;
        foreach ( $terms as $term ) {
            $arr_terms[ $term->term_id ] = $term->name;
            if ( isset( $cl_lang->en ) && $cl_lang->en && ! $is_css ) {
                switch ( $term->term_id ) {
                    case 12:
                        $arr_terms[ $term->term_id ] = 'Miscelaneous';
                        break;
                    case 8:
                        $arr_terms[ $term->term_id ] = 'Scabbard';
                        break;
                    case 9:
                        $arr_terms[ $term->term_id ] = 'Girdle';
                        break;
                    case 10:
                        $arr_terms[ $term->term_id ] = 'Pouch';
                        break;
                    case 11:
                        $arr_terms[ $term->term_id ] = 'Purse';
                        break;
                }
            }
        }
        return ( $arr_terms );
    }
    
    public function get_image_cat( $tax_id )
    {
        global $cl_custom_type;
        
        $result = $cl_custom_type->get_creations_by_type( $tax_id, 1);
        return ( empty($result) ) ? false : $cl_custom_type->get_images_size( $result{ 0 }->post_id, 'medium', true );
    }
}