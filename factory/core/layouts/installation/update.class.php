<?php

abstract class FactoryFR100Update {
    
    /**
     * Current plugin
     * @var FactoryFR100Plugin
     */
    var $plugin;
    
    public function __construct( FactoryFR100Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
