<?php
/**
 * The class contains a base class for all lists of assets.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Assets List
 * 
 * @since 1.0.0
 */
class Factory325_AssetsList 
{
    protected $all = array();
    public $headerPlace = array();
    public $footerPlace = array();
    public $required = array();
    
    protected $defaultPlace;

    public function __construct( $plugin = null, $defaultIsFooter = true ) {
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
     * 
     * @param string $source if the 'bootstrap' specified, checks only whether the bootstrap assets were required.
     * @return boolean
     */
    public function isEmpty( $source = 'wordpress' ) {
        if ( 'bootstrap' === $source ) return empty( $this->required[$source] );
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
    public function request( $items, $source = 'wordpress' ) {
        
        if ( is_array( $items ) ) {
            foreach($items as $item) {
                $this->required[$source][] = $item;
            }  
        } else {
            $this->required[$source][] = $items;
        }
     
        return $this;
    }
}
