<?php

class FactoryFormFR105Tab extends FactoryFormFR105Item {
    
    /**
     * Is a current form items a tab?
     * @var boolean 
     */
    public $isTab = true;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = 'tab';
    
    /**
     * Tab type
     * @var string 
     */
    public $tabType = 'horizontal';
    
    function __construct($itemData, $parent = null) {
        parent::__construct($itemData, $parent);

        if ( !empty( $itemData['tabType'] ) ) $this->tabType = $itemData['tabType'];
    }
}