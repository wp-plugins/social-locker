<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryFR106UpdateFR106Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryFR106UpdateFR106Manager( $plugin );
    }
}

add_action('factory_fr106_load_updates', 'factory_update_fr106s_module_load');
function factory_update_fr106s_module_load( $plugin ) {
    new FactoryFR106UpdateFR106Module( $plugin ); 
}