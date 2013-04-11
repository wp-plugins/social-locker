<?php

abstract class FactoryFR105Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR105Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
