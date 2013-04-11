<?php

class FactoryFormFR105IntegerFormControl extends FactoryFormFR105TextboxFormControl 
{
    public $type = 'integer';
        
    public function getValue($name) {
        $value = intval( parent::getValue($name) );
        return $value != 0 ? $value : null;
    }
}