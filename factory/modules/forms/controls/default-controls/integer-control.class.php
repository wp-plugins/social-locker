<?php

class FactoryFormFR101IntegerFormControl extends FactoryFormFR101TextboxFormControl 
{
    public $type = 'integer';
        
    public function getValue($name) {
        $value = intval( parent::getValue($name) );
        return $value != 0 ? $value : null;
    }
}