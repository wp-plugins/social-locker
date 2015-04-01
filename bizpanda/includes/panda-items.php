<?php

class OPanda_Items {
    
    private static $_available = null;
    
    public static function isPremium( $name ) {
        $item = self::getItem( $name );
        return isset( $item['type'] ) && $item['type'] === 'premium';
    }
    
    public static function isFree( $name ) {
        $item = self::getItem( $name );
        return isset( $item['type'] ) && $item['type'] === 'free';
    }
    
    public static function isCurrentPremium() {
        $name = self::getCurrentItemName();
        return self::isPremium( $name );
    }
    
    public static function isCurrentFree() {
        $name = self::getCurrentItemName();
        return self::isFree( $name );
    }
    
    public static function getItem( $name ) {
        $available = self::getAvailable();
        return isset( $available[$name] ) ? $available[$name] : null; 
    } 
 
    public static function getCurrentItem() {
        $name = self::getCurrentItemName();
        if ( empty( $name ) ) return null;
        return self::getItem( $name );
    }
    
    public static function getItemById( $id ) {
        $name = self::getItemNameById();
        if ( empty( $name ) ) return null;
        return self::getItem( $name );
    }    
    
    public static function getItemNameById( $id ) {
        return get_post_meta( $id, 'opanda_item', true );
    }    
    
    public static function getCurrentItemName() {
        
        // - from the query
        $item = isset( $_GET['opanda_item'] ) ? $_GET['opanda_item'] : null;

        // - from the form hidden field
        if ( empty( $item ) ) {
            $item = isset( $_POST['opanda_item'] ) ? $_POST['opanda_item'] : null;
        }

        // - from the port meta data
        if ( !empty( $_GET['post'] ) ) {
            $postId = intval( $_GET['post'] );
            $value = get_post_meta( $postId, 'opanda_item', true );
            if ( !empty( $value ) ) $item = $value;
        }
        
        return $item;
    }

    public static function getAvailable() {
        if ( self::$_available !== null ) return self::$_available;

        $items = array();
        self::$_available = apply_filters( 'opanda_items', $items );
        return self::$_available;  
    }
    
    public static function getAvailableNames( $returnFalseForEmpty = false ) {
        $available = self::getAvailable();
        $result = array_keys( $available );
        
        if ( empty( $result ) ) return $returnFalseForEmpty ? false : 'empty';
        return $result;
    }
    
    public static function isAvailable( $name ) {
        $available = self::getAvailable();
        return isset( $available[$name] ); 
    }
    
    public static function isCurrentAvailable() {
        $name = self::getCurrentItemName();
        if ( empty( $name ) ) return false;
        return self::isAvailable( $name );
    }
    
    public static function getPremiumUrl( $name ) {
        $item = self::getItem( $name );
        if ( empty( $item ) ) return false;
        
        if ( isset( $item['plugin']->options['premium'] ) ) return $item['plugin']->options['premium'];
        return false;
    }
    
    public static function getCurrentPremiumUrl() {
        $name = self::getCurrentItemName();
        if ( empty( $name ) ) return false;
        return self::getPremiumUrl( $name );
    }
}