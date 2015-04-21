<?php 
abstract class OPanda_Subscription {
    
    public $name;
    public $title; 
    public $data;
    
    public function __construct( $data = array() ) {
        $this->data = $data;
        
        if ( isset( $data['name']) ) $this->name = $data['name'];
        if ( isset( $data['title']) ) $this->title = $data['title']; 
    }

    public function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);       
    }

    public abstract function getLists();
    public abstract function subscribe( $identityData, $listId, $doubleOptin, $contextData );
    public abstract function check( $identityData, $listId, $contextData );
}

/**
 * A subscription service exception.
 */
class OPanda_SubscriptionException extends Exception {
    
    public function __construct ($message) {
        parent::__construct($message, 0, null);
    }
}