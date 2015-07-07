<?php

/**
 * The base class for all handlers of requests to the proxy.
 */
class OPanda_Handler {
    
    public $srorage;
    
    public function __construct( $options ) {
        $this->options = $options;
    }

    /**
     * Saves the value to the storage.
     */
    public function saveValue( $key, $name, $value ) { 
        
        if ( defined( 'OPANDA_WORDPRESS' ) ) {
            set_transient( 'opanda_' . md5($key . '_' . $name), $value, 60 * 60 * 24 );
        } else {
            if ( empty( $_SESSION[$key] ) ) $_SESSION[$key] = array();
            $_SESSION[$key][$name] = $value;
        }
    }
    
    /**
     * Get the value from the storage.
     */
    public function getValue( $key, $name, $default = null ) {
        
        if ( defined( 'OPANDA_WORDPRESS' ) ) {
            $value = get_transient( 'opanda_' . md5($key . '_' . $name) );
            if ( empty( $value ) ) return $default;
            return $value;
        } else {
            if ( empty( $_SESSION[$key] ) || empty( $_SESSION[$key][$name] ) ) return $default;
            return $_SESSION[$key][$name];
        }
    }
    
    protected function normilizeValues( $values = array() ) {
        if ( empty( $values) ) return $values;
        if ( !is_array( $values ) ) $values = array( $values );
        
        foreach ( $values as $index => $value ) {
            
            $values[$index] = is_array( $value )
                        ? $this->normilizeValues( $value ) 
                        : $this->normilizeValue( $value );
        }
        
        return $values;
    }
    
    protected function normilizeValue( $value = null ) {
        if ( 'false' === $value ) $value = false;
        elseif ( 'true' === $value ) $value = true;
        elseif ( 'null' === $value ) $value = null;
        return $value;
    }
    
    /**
     * Process names of the identity data.
     */
    public function prepareDataToSave( $service, $itemId, $identityData ) {
        
        // move the values from the custom fields like FNAME, LNAME
        
        if ( !empty( $service ) ) {
        
            $formType = get_post_meta( $itemId, 'opanda_form_type', true );
            $strFieldsJson = get_post_meta( $itemId, 'opanda_fields', true );

            if ( 'custom-form' == $formType && !empty( $strFieldsJson ) ) {

                $fieldsData = json_decode( $strFieldsJson, true );      
                $ids = $service->getNameFieldIds();

                $newIdentityData = $identityData;

                foreach( $identityData as $itemId => $itemValue ) {

                    foreach($fieldsData as $fieldData) {
                        if ( $fieldData['fieldOptions']['id'] !== $itemId ) continue;

                        $mapId = $fieldData['mapOptions']['id'];

                        if ( in_array( $fieldData['mapOptions']['mapTo'], array( 'separator', 'html', 'label' ) ) ) {
                            unset($newIdentityData[$itemId]);
                            continue;
                        }

                        foreach( $ids as $nameFieldId => $nameFieldType ) {
                            if ( $mapId !== $nameFieldId ) continue;
                            $newIdentityData[$nameFieldType] = $itemValue;
                            unset($newIdentityData[$itemId]);
                        }
                    }
                }  

                $identityData = $newIdentityData;
            }
        }
   
        // splits the full name into 2 parts
        
        if ( isset( $identityData['fullname'] ) ) {
            
            $fullname = trim( $identityData['fullname'] );
            unset( $identityData['fullname'] );

            $parts = explode(' ', $fullname);
            $nameParts = array();

            foreach( $parts as $part ) {
                if ( trim($part) == '' ) continue;
                $nameParts[] = $part;
            } 

            if ( count($nameParts) == 1 ) {
                $identityData['name'] = $nameParts[0];
            } else if ( count($nameParts) > 1) {
                $identityData['name'] = $nameParts[0];
                $identityData['displayName'] = implode(' ', $nameParts);
                unset( $nameParts[0] );
                $identityData['family'] = implode(' ', $nameParts);
            }   
        }

        return $identityData;
    }
    
    /**
     * Replaces keys of identity data of the view 'cf3' with the ids of custom fields in the mailing services.
     */
    public function mapToServiceIds( $service, $itemId, $identityData ) {

        $formType = get_post_meta( $itemId, 'opanda_form_type', true );
        $strFieldsJson = get_post_meta( $itemId, 'opanda_fields', true );
        
        if ( 'custom-form' !== $formType || empty( $strFieldsJson ) ) {
            
            $data = array();
            if ( isset( $identityData['email'] ) ) $data['email'] = $identityData['email'];
            if ( isset( $identityData['name'] ) ) $data['name'] = $identityData['name'];
            if ( isset( $identityData['family'] ) ) $data['family'] = $identityData['family'];       
            return $data;
        }
        
        $fieldsData = json_decode( $strFieldsJson, true );
        
        $data = array();
        foreach( $identityData as $itemId => $itemValue ) {
            
            if ( in_array( $itemId, array('email', 'fullname', 'name', 'family', 'displayName') ) ) {
                $data[$itemId] = $itemValue;
                continue;
            }
            
            foreach($fieldsData as $fieldData) {
                
                if ( $fieldData['fieldOptions']['id'] === $itemId ) {
                    $mapId = $fieldData['mapOptions']['id'];
                    $data[$mapId] = $service->prepareFieldValueToSave( $fieldData['mapOptions'], $itemValue );
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Replaces keys of identity data of the view 'cf3' with the labels the user enteres in the locker settings.
     */
    public function mapToCustomLabels( $service, $itemId, $identityData ) {
        
        $formType = get_post_meta( $itemId, 'opanda_form_type', true );
        $strFieldsJson = get_post_meta( $itemId, 'opanda_fields', true );
        
        if ( 'custom-form' !== $formType || empty( $strFieldsJson ) ) return $identityData;
        
        $fieldsData = json_decode( $strFieldsJson, true );

        $data = array();
        foreach( $identityData as $itemId => $itemValue ) {
            
            if ( in_array( $itemId, array('email', 'fullname', 'name', 'family', 'displayName') ) ) {
                $data[$itemId] = $itemValue;
                continue;
            }
            
            foreach($fieldsData as $fieldData) {

                if ( $fieldData['fieldOptions']['id'] !== $itemId ) continue;
                $label = $fieldData['serviceOptions']['label'];
                
                if ( empty( $label ) ) continue 2;
                $data['{' . $label . '}'] = $itemValue;
                continue 2;
            }

            $data[$itemId] = $itemValue;
        }
        
        return $data;
    }
}

/**
 * An exception which shows the error for public.
 */
class Opanda_HandlerException extends Exception {
    
    public function __construct ($message) {
        parent::__construct($message, 0, null);
    }
}

/**
 * An exception which shows hides the error but saves it in the logs.
 */
class Opanda_HandlerInternalException extends Opanda_HandlerException {
    
    protected $detailed;
    
    public function __construct ($message) {
        parent::__construct($message, 0, null);
        $this->detailed = $message;
        $this->message = 'Unexpected error occurred. Please check the logs for more details.';
    }
    
    public function getDetailed() {
        return $this->detailed;
    }
}