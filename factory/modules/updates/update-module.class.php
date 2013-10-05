<?php

/**
 * Class is used to manage the updates data.
 */
class FactoryFR110UpdateFR110Module {
    
    public function __construct( $plugin ) {
        $plugin->updates = new FactoryFR110UpdateFR110Manager( $plugin );
    }
}

add_action('factory_fr110_load_updates', 'factory_update_fr110s_module_load');
function factory_update_fr110s_module_load( $plugin ) {
    new FactoryFR110UpdateFR110Module( $plugin ); 
}