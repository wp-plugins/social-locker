<?php

abstract class FactoryFR107Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryFR107Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
