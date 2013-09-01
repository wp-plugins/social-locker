<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryPR108UpdatePR108Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryPR108UpdatePR108Manager( $plugin );
    }
}

add_action('factory_pr108_load_updates', 'factory_update_pr108s_module_load');
function factory_update_pr108s_module_load( $plugin ) {
    new FactoryPR108UpdatePR108Module( $plugin ); 
}