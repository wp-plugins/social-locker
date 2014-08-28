<?php

class FactoryTypes321_Menu {
    
    public $icon;
    
    /**
     * A custom post type that is configurated by this instance.
     * @var FactoryTypes321_Type 
     */
    public $type = null;
    
    public function __construct($type) {
        $this->type = $type;
    }
}