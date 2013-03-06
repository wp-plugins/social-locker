<?php

class FactoryFormFR100Group extends FactoryFormFR100Item {
    
    /**
     * Is a current form items a group?
     * @var boolean 
     */
    public $isgroup = true;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = 'group';
    
    /**
     * Returns whether a group has a legend.
     * @var type 
     */
    public $hasLegend = false;
    
    function __construct($itemData, $parent = null) {
        parent::__construct($itemData, $parent);
        
        if (!empty($itemData['title'])) $this->typeType = true;
    }
}