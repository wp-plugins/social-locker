<?php
/**
 * The file contains a class to manage style assets.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Style List
 * 
 * @since 1.0.0
 */
class Factory308_StyleList extends Factory308_AssetsList 
{
    public function connect() {

        // register all global required styles
        if ( !empty( $this->required['_global_'] ) ) {
            foreach ($this->required['_global_'] as $style) {
                if ( 'wordpress' === $style[1] ) wp_enqueue_style( $style[0] );
                elseif ( 'bootstrap' === $style[1] ) factory_bootstrap_308_enqueue_style( $style[0] );
            }     
        }
        
        // register all other styles
        foreach($this->all as $style) {
            
            $dep = !empty( $this->required[$style] ) ? $this->required[$style] : array();  
            
            foreach( $dep as $depStyle ) {
                if ( 'wordpress' === $depStyle[1] ) wp_enqueue_style( $depStyle[0] );
                elseif ( 'bootstrap' === $depStyle[1] ) factory_bootstrap_308_enqueue_style( $depStyle[0] );
            }
            
            wp_enqueue_style( $style, $style, array());
        }   
    }
}
