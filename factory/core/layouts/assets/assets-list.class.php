<?php

class FactoryFR102AssetsList 
{
    /**
     * Current factory.
     * @var FactoryFR102Plugin
     */
    public $plugin;
    
    protected $all = array();
    public $headerPlace = array();
    public $footerPlace = array();
    public $required = array();
    
    protected $defaultPlace;

    public function __construct( $plugin, $defaultIsFooter = true ) {
        $this->plugin = $plugin;
        
        if ( $defaultIsFooter ) $this->defaultPlace = &$this->footerPlace;
        if ( !$defaultIsFooter ) $this->defaultPlace = &$this->headerPlace;
    }
    
    /**
     * Adds new items to the collection (default place).
     * @param mixed
     */
    public function add() {

        foreach(func_get_args() as $item) {
            $this->all[] = $item;
            $this->defaultPlace[] = $item;
        }     
        
        return $this;
    }
    
    /**
     * Adds new items to the collection (header).
     * @param mixed
     */
    public function addToHeader() {

        foreach(func_get_args() as $item) {
            $this->all[] = $item;
            $this->headerPlace[] = $item;
        }  
        
        return $this;
    }   
    
    /**
     * Adds new items to the collection (footer).
     * @param mixed
     */
    public function addToFooter() {

        foreach(func_get_args() as $item) {
            $this->all[] = $item;
            $this->footerPlace[] = $item;
        }   
        
        return $this;
    }   
    
    /**
     * Checks whether the collection is empty.
     * @return boolean
     */
    public function isEmpty() {
        return empty($this->all) && empty($this->required);
    }
    
    public function IsHeaderEmpty() {
        return empty($this->headerPlace);
    }
    
    public function IsFooterEmpty() {
        return empty($this->footerPlace);
    }   
    
    /**
     * Adds new items to the requried collection.
     * @param mixed
     */
    public function request() {
        
        $bindTo = count( $this->all ) == 0 ? '_global_' : end( $this->all );
        
        foreach(func_get_args() as $item) {
            $this->required[$bindTo][] = $item;
        }       
        
        return $this;
    }    
}
