<?php
/**
 * The file contains a base class for plugin activators.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Plugin Activator
 * 
 * @since 1.0.0
 */
abstract class Factory325_Activator {
    
    /**
     * Curent plugin.
     * @var Factory325_Plugin
     */
    public $plugin;
    
    public function __construct(Factory325_Plugin $plugin) {
        $this->plugin = $plugin;
    }
    
    public function activate() {}
    public function deactivate() {}
    public function update() {}

    // --------------------------------------------------------------------------------
    // Posts and pages
    // --------------------------------------------------------------------------------
    
    /**
     * Adds post on activation.
     * @return array Post info.
     */
    public function addPost() {
        
        $argsCount = func_num_args();
        
        $postInfoBase = array();
        $metaInfoBase = array();
        
        if ($argsCount == 4) {
            
             $base = func_get_arg(0);
             
             $postInfoBase = $base['post'];
             $metaInfoBase = $base['meta'];
        }
        
        $optionName = ($argsCount == 4) ? func_get_arg(1) : func_get_arg(0);
        $postInfo = ($argsCount == 4) ? func_get_arg(2) : func_get_arg(1);
        $metaInfo = ($argsCount == 4) ? func_get_arg(3) : func_get_arg(2);
        
        if ($postInfo == null) $postInfo = array();
        if ($metaInfo == null) $metaInfo = array();  
        
        $postInfo = array_merge($postInfoBase, $postInfo);
        $metaInfo = array_merge($metaInfoBase, $metaInfo);
        
        $insert_id = $this->createPost($postInfo, $metaInfo, $optionName);
        
        return array(
            'post_id' => $insert_id,
            'post' => $postInfo,
            'meta' => $metaInfo
        );
    }
    
    /**
     * Adds a page on activation.
     */
    public function addPage() {
        $argsCount = func_num_args();

        $optionName = func_get_arg(0);
        $postInfo = func_get_arg(1);
        $metaInfo = func_get_arg(2);
        
        if ($postInfo == null) $postInfo = array();
        if ($metaInfo == null) $metaInfo = array(); 
        
        $postInfo['post_type'] = 'page';
        $this->createPost($postInfo, $metaInfo, $optionName);
    }
    
    /**
     * Creates post by using the specified info.
     * @global type $wpdb
     * @param type $postInfo
     * @param type $metaInfo
     * @param type $optionName
     * @return interger
     */
    public function createPost( $postInfo, $metaInfo, $optionName ) {
        global $wpdb;

        $slug = $postInfo['post_name'];
        $postType = $postInfo['post_type'];
        
        $postId = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' AND 
                    post_type = '" . $postType . "' LIMIT 1");
        
        $optionValue = get_option($optionName);

        if ( !$postId )
        {
            $create = true;

            if ( !empty( $optionValue ) ) {
                $post_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE ID = '$optionValue' AND 
                            post_type = '" . $postType . "' LIMIT 1");
                if ( $post_id ) $create = false;
            };

            if ( $create ) :
                    if ( !isset( $postInfo['post_status'] ) ) $postInfo['post_status'] = 'publish';
                    
                    // '@' here is to hide unexpected output while plugin activation
                    $optionValue = @wp_insert_post( $postInfo );
                    $postId = $optionValue;
                    update_option( $optionName, $optionValue );
            endif;
        }
        else
        {
            if ( empty ( $optionValue ) ) {
                update_option( $optionName, $postId );
            }
        }

        update_option( $optionName, $postId );
        
        // adds meta
        foreach($metaInfo as $key => $value) {
            if ($value === true) $value = 'true';
            if ($value === false) $value = 'false';

            add_post_meta($postId, $key, $value); 
        }
        
        return $postId;
    }
}