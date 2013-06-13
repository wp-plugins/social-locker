<?php

abstract class FactoryFR106Activation {
    
    /**
     * Curent plugin.
     * @var FactoryPlugin
     */
    public $plugin;
    
    public function __construct(FactoryFR106Plugin $plugin) {
        $this->plugin = $plugin;
    }
    
    public function activate() {}
    public function deactivate() {}
    public function update() {}

    // --------------------------------------------------------------------------------
    // Licensing
    // --------------------------------------------------------------------------------
    
    /**
     * Installs default license.
     * @param type $data
     */
    protected function license($data) {
        $data['Activated'] = 0; 
        $data['Expired'] = 0; 
        
        $defaultLicense = get_option('fy_default_license_' . $this->plugin->pluginName, null);
        if ( empty($defaultLicense) ) {
            update_option('fy_default_license_' . $this->plugin->pluginName, $data);    
        }
    }
    
    // --------------------------------------------------------------------------------
    // Posts and pages
    // --------------------------------------------------------------------------------
    
    /**
     * Adds post on activation.
     * @return array Post info.
     */
    protected function addPost() {
        
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
        
        $this->createPost($postInfo, $metaInfo, $optionName);
        
        return array(
            'post' => $postInfo,
            'meta' => $metaInfo
        );
    }
    
    /**
     * Adds a page on activation.
     */
    protected function addPage() {
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
    private function createPost( $postInfo, $metaInfo, $optionName ) {
        global $wpdb;

        $slug = $postInfo['post_name'];
        $postType = $postInfo['post_type'];
        
        $postId = $wpdb->get_var(
                "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' AND 
                    post_type = '" . $postType . "' LIMIT 1");
        
        $optionValue = get_option( $optionName );

        if ( !$postId )
        {
            $create = true;

            if ( !empty( $optionValue ) ) {
                $post_id = $wpdb->get_var(
                        "SELECT ID FROM " . $wpdb->posts . " WHERE ID = '$optionValue' AND 
                            post_type = '" . $postType . "' LIMIT 1" );
                if ( $post_id ) $create = false;
            };

            if ( $create ) :
                    if ( !isset( $postInfo['post_status'] ) ) $postInfo['post_status'] = 'publish';	
                    $optionValue = wp_insert_post( $postInfo );
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