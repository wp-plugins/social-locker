<?php
/**
 * The file contains the class of Factory Meta Value Provider.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Factory Meta Value Provider
 * 
 * This provide works with meta values like a lazy key-value storage and 
 * provides methods to commit changes on demand. It increases perfomance on form saving.
 * 
 * @since 1.0.0
 */
class FactoryForms328_MetaValueProvider implements IFactoryForms328_ValueProvider 
{
    /**
     * Values to save $metaName => $metaValue
     * @var array
     */
    private $values = array();
    
    /**
     * Chanched meta keys (indexed array)
     * @var array
     */
    private $keys = array();
    
    private $meta = array();
    public $scope;
    
    /**
     * Creates a new instance of a meta value provider.
     * 
     * @global type $post
     * @param type $options
     */
    public function __construct( $options = array() ) {
        global $post;
                
        $this->scope = ( isset( $options['scope'] ) ) ? $options['scope'] : null;
        $this->scope = preg_replace('/\_meta\_box$/', '', $this->formatCamelCase( $this->scope ) );

        $this->postId = ( isset( $options['postId'] ) ) ? $options['postId'] : $post->ID;
        
        // the second parameter for compatibility with wordpress 3.0
        $temp = get_post_meta( $this->postId, '' );
        
        foreach($temp as $key => &$content) {
            if ( strpos( $key, $this->scope ) === 0 ) {
                $this->meta[$key] = $content;
            }
        }
    }
    
    /**
     * Initizalize an instance of the provider. 
     * This method should be invoked before the provider usage.
     * 
     * @param type $scope       Scope is prefix that is added to all meta keys.
     * @param type $postId      Post id we will use meta data or empty for current post.
     */
    public function init( $postId = false ) {
        global $post;
            
        $this->postId = $postId ? $postId : $post->ID;
        
        // the second parameter for compatibility with wordpress 3.0
        $temp = get_post_meta( $this->postId, '' );
        foreach($temp as $key => &$content) {
            if ( strpos( $key, $this->scope ) === 0 ) {
                $this->meta[$key] = $content;
            }
        }
    }

    /**
     * Saves changes into a database.
     * The method is optimized for bulk updates.
     */
    public function saveChanges() {

        
        $this->deleteValues();
        $this->insertValues();
        
        /**
        foreach ($this->values as $key => $value) {
            update_post_meta($this->postId, $key, $value);
        }
         */
    }
    
    /**
     * Removes all actual values from a database.
     */
    private function deleteValues() {
        if ( count( $this->keys ) == 0 ) return;
        
        global $wpdb; 
        
        $keys = array();
        for($i = 0; $i < count($this->keys); $i++) {
            $keys[] = '\'' . $this->keys[$i] . '\'';
        }

        $clause = implode( ',', $keys );
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id={$this->postId} AND meta_key IN ($clause)");
    }
    
    /**
     * Inserts new values by using bulk insert directly into a database.
     */
    private function insertValues() {
        global $wpdb; 
        
        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
        $rows = array();

        foreach($this->values as $metaKey=>$metaValue) {
            if ( is_array( $metaValue ) ) {
                foreach( $metaValue as $value ) {
                    $rows[] = $wpdb->prepare( '(%d,%s,%s)' , $this->postId, $metaKey, $value );  
                }
            } else {
                $rows[] = $wpdb->prepare( '(%d,%s,%s)' , $this->postId, $metaKey, $metaValue );
            }
        }
        $sql = $sql . implode( ',', $rows );
        $wpdb->query($sql);
    }

    public function getValue($name, $default = null, $multiple = false ) {
        
        if ( is_array( $name ) ) {
       
            $values = array();
            $index = 0;
            
            foreach($name as $item) {
                $itemDefault = ( $default && is_array($default) && isset($default[$index]) )
                                    ? $default[$index] : null;
                
                $values[] = $this->getValueBySingleName($item, $itemDefault, $multiple);
                $index++;
            }
            return $values;
        }
        
        $value = $this->getValueBySingleName($name, $default, $multiple);
        return $value;
    }
    
    protected function getValueBySingleName( $singleName, $default = null, $multiple = false ) {

        $value = isset( $this->meta[$this->scope . '_' . $singleName] ) 
                ? ( $multiple ) ? $this->meta[$this->scope . '_' . $singleName] : $this->meta[$this->scope . '_' . $singleName][0] 
                : $default;
        
        if ($value === 'true') $value = 1;
        if ($value === 'false') $value = 0;
        
        return $value;  
    }

    public function setValue($name, $value) {

        if ( is_array( $name ) ) {
            $index = 0;

            foreach($name as $item) {
                $itemValue = ( $value && is_array($value) && isset($value[$index]) )
                                    ? $value[$index] : null;

                $this->setValueBySingleName($item, $itemValue);
                $index++;
            }
            
            return;
        }
        
        $this->setValueBySingleName($name, $value);
        return;
    }
    
    protected function setValueBySingleName( $singleName, $singeValue ) {
        $name = $this->scope . '_' . $singleName;
        
        if ( is_array( $singeValue ) ) {
            
            foreach ($singeValue as $index => $value) {
                
                $singeValue[$index] = empty( $singeValue[$index] ) 
                    ? $singeValue[$index] 
                    : stripslashes ( $singeValue[$index] );
            }
            
            $value = $singeValue;
        } else {
            $value = empty( $singeValue ) ? $singeValue : stripslashes ( $singeValue ); 
        }

        $this->values[$name] = $value;
        $this->keys[] = $name; 
    }
    
    private function formatCamelCase( $string ) {
        $output = "";
        foreach( str_split( $string ) as $char ) {
            if ( strtoupper( $char ) == $char && !in_array($char, array('_', '-'))) {
                $output .= "_";
            }
            $output .= $char;
        }
        $output = strtolower($output);
        return $output;
    }
}