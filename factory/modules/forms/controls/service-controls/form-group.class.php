<?php

class FactoryFormFR106Group extends FactoryFormFR106Item {
    
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