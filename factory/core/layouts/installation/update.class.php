<?php

abstract class FactoryPR108Update {
    
    /**
     * Current plugin
     * @var FactoryPlugin
     */
    var $plugin;
    
    public function __construct( FactoryPR108Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
