<?php

interface IFactoryFR105ValueProvider {
    
    public function init( $scope, $postId = false );
    public function saveChanges();
    
    public function getValue( $name, $default = null );
    public function setValue( $name, $value );
}