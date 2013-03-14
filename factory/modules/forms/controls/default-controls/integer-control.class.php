<?php

class FactoryFormFR100IntegerFormControl extends FactoryFormFR100TextboxFormControl 
{
    public $type = 'integer';
        
    public function getValue($name) {
        $value = intval( parent::getValue($name) );
        return $value != 0 ? $value : null;
    }
}