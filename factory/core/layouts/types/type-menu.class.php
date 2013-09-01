<?php

class FactoryPR108TypeMenu {
    
    public $icon;
    
    /**
     * A custom post type that is configurated by this instance.
     * @var FactoryType 
     */
    public $type = null;
    
    public function __construct(FactoryPR108Type $type) {
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