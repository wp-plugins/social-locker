<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryFR107UpdateFR107Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryFR107UpdateFR107Manager( $plugin );
    }
}

add_action('factory_fr107_load_updates', 'factory_update_fr107s_module_load');
function factory_update_fr107s_module_load( $plugin ) {
    new FactoryFR107UpdateFR107Module( $plugin ); 
}