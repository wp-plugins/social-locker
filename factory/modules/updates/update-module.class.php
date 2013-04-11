<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryFR105UpdateFR105Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryFR105UpdateFR105Manager( $plugin );
    }
}

add_action('factory_fr105_load_updates', 'factory_update_fr105s_module_load');
function factory_update_fr105s_module_load( $plugin ) {
    new FactoryFR105UpdateFR105Module( $plugin ); 
}