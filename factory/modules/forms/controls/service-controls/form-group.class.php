<?php

class FactoryFormFR110Group extends FactoryFormFR110Item {
    
    /**
     * Is a current form items a group?
     * @var boolean 
     */
    public $isGroup = true;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = 'group';
    
    function __construct($itemData, $parent = null) {
        parent::__construct($itemData, $parent);      
    }
}