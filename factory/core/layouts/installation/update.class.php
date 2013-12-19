<?php

abstract class FactoryFR110Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR110Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
