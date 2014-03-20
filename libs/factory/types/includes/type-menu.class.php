<?php

class FactoryTypes305_Menu {
    
    public $icon;
    
    /**
     * A custom post type that is configurated by this instance.
     * @var FactoryTypes305_Type 
     */
    public $type = null;
    
    public function __construct($type) {
        $this->type = $type;
    }
}