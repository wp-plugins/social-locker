<?php

class FactoryFormFR110IntegerFormControl extends FactoryFormFR110TextboxFormControl 
{
    public $type = 'integer';
        
    public function getValue($name) {
        $value = intval( parent::getValue($name) );
        return $value != 0 ? $value : null;
    }
}