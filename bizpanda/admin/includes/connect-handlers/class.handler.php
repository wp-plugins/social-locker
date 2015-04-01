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