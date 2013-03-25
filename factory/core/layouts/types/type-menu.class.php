<?php

class FactoryFR103TypeMenu {
    
    public $icon;
    
    /**
     * A custom post type that is configurated by this instance.
     * @var FactoryType 
     */
    public $type = null;
    
    public function __construct(FactoryFR103Type $type) {
        $this->type = $type;
    }
    
    public function insertAfter($position)
    {
        
    }
    
    public function insertBefore($position)
    {
        
    }
    
    public function insertAt($position)
    {
        
    }
    
    public function append()
    {
        
    }
    
    public function prepend()
    {
        
    }
}