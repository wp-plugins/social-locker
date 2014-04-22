<?php

class FactoryTypes307_Menu {
    
    public $icon;
    
    /**
     * A custom post type that is configurated by this instance.
     * @var FactoryTypes307_Type 
     */
    public $type = null;
    
    public function __construct($type) {
        $this->type = $type;
    }
}