<?php

abstract class FactoryFR103Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR103Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
