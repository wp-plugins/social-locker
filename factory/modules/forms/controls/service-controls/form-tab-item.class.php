<?php

class FactoryFormFR108TabItem extends FactoryFormFR108Item {
    
    /**
     * Is a current form items a tab item?
     * @var boolean 
     */
    public $isTabItem = true;
    
    /**
     * A form type of a current item.
     * @var string
     */
    public $itemType = 'tab-item';
    
    /**
     * An icon of a current tab.
     * @var string
     */  
    public $tabIcon;
    
    function __construct($itemData, $parent = null) {
        parent::__construct($itemData, $parent);
        
        if ( empty( $this->name ) && !empty( $this->title ) ) {
            $name = str_replace(' ', '-', $this->title );
            $name = strtolower($name);
            $this->name = $name;
        }
        
         if ( !empty( $itemData['icon'] ) ) $this->tabIcon = $itemData['icon'];
    }
}