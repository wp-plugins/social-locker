<?php

class FactoryFR105FakeValueProvider implements IFactoryFR105ValueProvider 
{
    public function init( $scope, $postId = false ) {
        return;
    }
    
    public function saveChanges() {
        return;
    }

    public function getValue($name, $default = null) {
        return null;
    }

    public function setValue($name, $value) {
        return;
    }
}