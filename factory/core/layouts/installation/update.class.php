<?php

abstract class FactoryFR102Update {
    
    /**
     * Current plugin
     * @var FactoryFR102Plugin
     */
    var $plugin;
    
    public function __construct( FactoryFR102Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
