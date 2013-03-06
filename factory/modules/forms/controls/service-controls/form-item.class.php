<?php

class FactoryFormFR100Item {
    
    /**
     * Is a current form items a tab?
     * @var boolean 
     */
    public $isTab = false; 
    
    /**
     * Is a current form item a tab item?
     * @var boolean 
     */
    public $isTabItem = false;    
    
    /**
     * Is a current form item a group?
     * @var boolean 
     */
    public $isGroup = false;

    /**
     * Is a current form item an inpuut control?
     * @var boolean 
     */
    public $isControl = false;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = null;
    
    /**
     * A parent item of a current item.
     * @var type 
     */
    public $parent = null;
    
    /**
     * Data of a current item.
     * @var mixed[] 
     */
    public $data = array();
    
    /**
     * Name of a current item.
     * @var string
     */
    public $name = null;
    
    /**
     * Title of a current item.
     * @var type 
     */
    public $title = null;
    
    /**
     * hint of a current item.
     * @var type 
     */
    public $hint = null;
    
    /**
     * Child items of a current item.
     * @var type 
     */
    public $items = array();
    
    function __construct($itemData, $parent = null) {
        
        $this->data = $itemData;
        if (!empty($itemData['name'])) $this->name = $itemData['name'];
        if (!empty($itemData['title'])) $this->title = $itemData['title'];
        if (!empty($itemData['hint'])) $this->hint = $itemData['hint'];    

        // if a parent is defined, add a current item to the parent
        if (!empty($parent)) { 
            $this->parent = $parent;
            $parent->add($this);
        }     
    }
    
    /**
     * Adds a new child item.
     * @param mixed $childItem
     */
    public function add($childItem) {
        $childItem->parent = $this;
        $this->items[] = $childItem;
    }
}