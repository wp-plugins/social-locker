<?php

abstract class FactoryFR108Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR108Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
