<?php

class FactoryFR103FakeValueProvider implements IFactoryFR103ValueProvider 
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