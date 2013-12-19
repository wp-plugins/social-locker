<?php

class FactoryFR110ScriptList extends FactoryFR110AssetsList 
{
    public function __construct($factory ) {
        parent::__construct($factory, true);
    }
    
    public $localizeData = array();
    public $useAjax = false;
    
    public function connect() {
        
        $aseetUrl = $this->plugin->pluginUrl . '/assets/';
        
        // register all global required scripts
        if ( !empty( $this->required['_global_'] ) ) {
            foreach ($this->required['_global_'] as $script) {
                wp_enqueue_script( $script );
            }     
        }
        
        $isFirstScript = true;
        $isFooter = false;
        
        // register all other scripts
        foreach (array($this->headerPlace, $this->footerPlace) as $scriptPlace) {
            
            foreach($scriptPlace as $script) {
                
                $dep = !empty( $this->required[$script] ) ? $this->required[$script] : array();
                
                wp_register_script( $script, str_replace('~/', $aseetUrl, $script), $dep, false, $isFooter);  

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
