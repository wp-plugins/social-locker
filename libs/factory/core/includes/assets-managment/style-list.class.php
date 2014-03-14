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
class Factory306_StyleList extends Factory306_AssetsList 
{
    public function connect() {

        // register all global required styles
        if ( !empty( $this->required['_global_'] ) ) {
            foreach ($this->required['_global_'] as $script) {
                wp_enqueue_script( $script );
            }     
        }
        
        // register all other styles
        foreach($this->all as $style) {
            
            $dep = !empty( $this->required[$style] ) ? $this->required[$style] : array();  
            wp_enqueue_style( $style, $style, $dep);
        }   
    }
}
