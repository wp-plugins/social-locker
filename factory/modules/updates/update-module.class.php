<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryFR108UpdateFR108Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryFR108UpdateFR108Manager( $plugin );
    }
}

add_action('factory_fr108_load_updates', 'factory_update_fr108s_module_load');
function factory_update_fr108s_module_load( $plugin ) {
    new FactoryFR108UpdateFR108Module( $plugin ); 
}