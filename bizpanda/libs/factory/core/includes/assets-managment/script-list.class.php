<?php
/**
 * The file contains a class to manage script assets.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Script List
 * 
 * @since 1.0.0
 */
class Factory325_ScriptList extends Factory325_AssetsList 
{
    public $localizeData = array();
    public $useAjax = false;
    
    public function connect( $source = 'wordpress' ) {

        // register all global required scripts
        if ( !empty( $this->required[$source] ) ) {
            foreach ($this->required[$source] as $script) {
                if ( 'wordpress' === $source ) wp_enqueue_script( $script );
                elseif ( 'bootstrap' === $source ) $this->plugin->bootstrap->enqueueScript( $script );
            }     
        }
        
        if ( $source == 'bootstrap' ) return;
        
        $isFirstScript = true;
        $isFooter = false;
        
        // register all other scripts
        foreach (array($this->headerPlace, $this->footerPlace) as $scriptPlace) {
            
            foreach($scriptPlace as $script) {
                
                wp_register_script( $script, $script, array(), false, $isFooter);  

                if ( $isFirstScript && $this->useAjax ) {
                    wp_localize_script( $script, 'factory', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
                }

                if ( !empty( $this->localizeData[$script] ) ) {
                    
                    wp_localize_script( 
                            $script, 
                            $this->localizeData[$script][0], 
                            $this->localizeData[$script][1]
                    );   
                }

                wp_enqueue_script( $script );
                $siFirstScript = false;
            }   
        
            $isFooter = true;
        }
    }
    
    public function useAjax() {
        $this->useAjax = true;
    }
    
    public function localize($varname, $data) {
        $bindTo = count( $this->all ) == 0 ? null : end( $this->all );
        if (!$bindTo) return;
        
        $this->localizeData[$bindTo] = array($varname, $data);
        return $this;
    }
}
