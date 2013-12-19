<?php

class FactoryFormFR110Collapsed extends FactoryFormFR110Item {
    
    /**
     * Is a current form items a collapsed item?
     * @var boolean 
     */
    public $isCollapsed = true;
    
    /**
     * Count of hidden options.
     * It's just a hint for a user.
     * @var type 
     */
    public $count = 0;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = 'collapsed';
    
    function __construct($itemData, $parent = null) {
        parent::__construct($itemData, $parent);
        
        if (!empty($itemData['count'])) $this->count = $itemData['count'];
    }
}