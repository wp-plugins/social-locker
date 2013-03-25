<?php
/**
 * A number of global functions with the prefix 'factory_fr102_', 
 * that are used to manage Factory instances.
 */

/**
 * A Factory gateway to create an instance of the plugin. 
 * It should be invoked first in plugin file.
 */
function factory_fr102_create_plugin( $pluginPath, $data ) {
    $plugin = new FactoryFR102Plugin($pluginPath, $data );
    return $plugin;
}

/**
 * Returns nonce based on a current wordpress blog options.
 */
function factory_fr102_get_nonce() {
    $values = array('name', 'description', 'admin_email', 'url', 'language', 'version');
    $line = '';
    
    foreach($values as $value) $line .= get_bloginfo($value);
    return md5( $line );
}

/**
 * Prints nonce based on a current wordpress blog options.
 */
function factory_fr102_nonce() {
    echo factory_fr102_get_nonce();
}


function factory_fr102_json_error($error) {
    echo json_encode(array('error' => $error));
    exit;
}