<?php

abstract class FactoryFR106Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR106Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
