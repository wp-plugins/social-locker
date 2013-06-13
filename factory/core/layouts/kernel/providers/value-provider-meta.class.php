<?php
/**
 * Factory Meta Value Provider
 * 
 * This provide works with meta value like a lazy key value storage and 
 * provides methods to commit changes on demand. It increases perfomance on form saving.
 */
class FactoryFR107MetaValueProvider implements IFactoryFR107ValueProvider 
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
     * Initizalize an instance of the provider. 
     * This method should be invoked before the provider usage.
     * 
     * @param type $scope       Scope is prefix that is added to all meta keys.
     * @param type $postId      Post id we will use meta data or empty for current post.
     */
    public function init( $scope, $postId = false ) {
        global $post;
            
        $this->scope = preg_replace('/_meta_box$/', '', $this->formatCamelCase( $scope ) );
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
            $rows[] = $wpdb->prepare( '(%d,%s,%s)' , $this->postId, $metaKey, $metaValue );
        }
        $sql = $sql . implode( ',', $rows );
        $wpdb->query($sql);
    }

    public function getValue($name, $default = null) {
        
        $value = isset( $this->meta[$this->scope . '_' . $name] ) 
                ? $this->meta[$this->scope . '_' . $name][0] 
                : $default;
        
        if ($value === 'true') $value = true;
        if ($value === 'false') $value = false;
        
        return $value;
        // return (!$value) ? $default : $value;
    }

    public function setValue($name, $value) {

        $name = $this->scope . '_' . $name;
        $this->values[$name] = empty( $value ) ? $value : stripslashes ( $value );
        $this->keys[] = $name;
        
        return;
    }
    
    private function formatCamelCase( $string ) {
        $output = "";
        foreach( str_split( $string ) as $char ) {
                strtoupper( $char ) == $char and $output and $output .= "_";
                $output .= $char;
        }
        $output = strtolower($output);
        return $output;
    }
}