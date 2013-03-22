<?php

/**
 * Renders link to the license manager.
 * 
 * @param type $pluginName
 * @param type $action
 */
function onepress_fr100_link_license_manager( $pluginName, $action = null ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    echo '?' . http_build_query( $args );
}
