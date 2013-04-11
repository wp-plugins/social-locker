<?php

class FactoryFR105List 
{
    protected $items = array();
    
    /**
     * Checks whether the collection is empty.
     * @return boolean
     */
    public function isEmpty() {
        return empty($this->items);
    }
    
    /**
     * Adds new items to the collection.
     * @param mixed
     */
    public function add() {

        foreach(func_get_args() as $item) {
            $this->items[] = $item;
        }        
    }
    
    /**
     * Returns all metaboxes as an array.
     * @return array
     */
    public function getAll() {
        return $this->items;
    }
}
