<?php

class FactoryFR108MetaboxCollection
{
    private $boxes = array();
    public $plugin;
    
    public function __construct(FactoryFR108Plugin $plugin ) {
        $this->plugin = $plugin;
    }
    
    /**
     * Checks whether the collection is empty.
     * @return boolean
     */
    public function isEmpty() {
        return empty($this->boxes);
    }
    
    /**
     * Adds a new metabox to the collection.
     * @param FactoryMetabox $metabox
     */
    public function add(FactoryFR108Metabox $metabox) {
        
        if (gettype($metabox) == 'string') {
            $metabox = new factoryExistingMetabox($this->factory, $metabox);          
        }
        
        $metabox->plugin = $this->plugin;
        $this->boxes[] = $metabox;
    }
    
    /**
     * Returns all metaboxes as an array.
     * @return array
     */
    public function getAll() {
        return $this->boxes;
    }
}
