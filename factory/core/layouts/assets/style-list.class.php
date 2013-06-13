<?php

class FactoryFR107StyleList extends FactoryFR107AssetsList 
{
    public function __construct($factory ) {
        parent::__construct($factory, false);
    }
    public function connect() {
        
        $aseetUrl = $this->plugin->pluginUrl . '/assets/';
        
        // register all global required styles
        if ( !empty( $this->required['_global_'] ) ) {
            foreach ($this->required['_global_'] as $script) {
                wp_enqueue_script( $script );
            }     
        }
        
        // register all other styles
        foreach($this->all as $style) {
            
            $dep = !empty( $this->required[$style] ) ? $this->required[$style] : array();  
            wp_enqueue_style( $style, str_replace('~/', $aseetUrl, $style), $dep);
        }   
    }
}
