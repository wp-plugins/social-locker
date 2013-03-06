<?php
/**
 * A number of global functions with the prefix 'factory_fr100_', 
 * that are used to manage Factory instances.
 */

/**
 * A Factory gateway to create an instance of the plugin. 
 * It should be invoked first in plugin file.
 * 
 * @param string $pluginPath        An absolute path to the main plugin file.
 * @param string $pluginName        An unique plugin name.
 * @param string $factoryItems      A folder placed at $pluginPath that includes Factory items.
 * @param string $factoryTemplates  A folder placed at $pluginPath that includes Factory templates.
 */
function factory_fr100_create_plugin( 
        $pluginPath, 
        $pluginName, 
        $version,
        $build,
        $server,
        $factoryItems = 'items', 
        $factoryTemplates = 'templates' ) {
    
    $plugin = new FactoryFR100Plugin($pluginPath, $pluginName, $version, $build, $server, $factoryItems, $factoryTemplates);
    return $plugin;
}

/**
 * Returns nonce based on a current wordpress blog options.
 */
function factory_fr100_get_nonce() {
    $values = array('name', 'description', 'admin_email', 'url', 'language', 'version');
    $line = '';
    
    foreach($values as $value) $line .= get_bloginfo($value);
    return md5( $line );
}

/**
 * Prints nonce based on a current wordpress blog options.
 */
function factory_fr100_nonce() {
    echo factory_fr100_get_nonce();
}
