<?php

if ( !function_exists( 'bizpanda_compability_note') ) {
    
    global $bizpanda_issue_plugin;
    
    function bizpanda_compability_note() {
        $count = 0;
        
        if ( method_exists('BizPanda', 'getInstalledPlugins') ) {
            
            $plugins = BizPanda::getInstalledPlugins();
            $count = count( $plugins );
            
            $titles = array();
            foreach( $plugins as $plugin ) $titles[] = $plugin['plugin']->options['title'];
            $titles = implode(',', $titles);
            
        } else {
            
            $count = 1;
            
            if ( BizPanda::hasPlugin('optinpanda') ) {
                $titles = 'Opt-In Panda';
            } else {
                $titles = 'Social Locker';
            }
        }
      
        global $bizpanda_issue_plugin;
        
        echo '<div id="message" class="error" style="padding: 10px;">';
        if ( $count > 1 ) printf( __('Unable to activate <strong>%s</strong>. Please make sure that the following plugins are updated to the latest versions: <strong>%s</strong>. Deactivate %s and try to update the specified plugins.'), $bizpanda_issue_plugin, $titles, $bizpanda_issue_plugin );
        else printf( __('Unable to activate <strong>%s</strong>. Please make sure that the following plugin is updated to the latest version: <strong>%s</strong>. Deactivate %s and try to update the specified plugin.'), $bizpanda_issue_plugin, $titles, $bizpanda_issue_plugin );
        echo '</div>';
    }
    
    function bizpanda_validate( $requiredVersion, $pluginTitle ) {
        $invalid = !defined('BIZPANDA_VERSION') || BIZPANDA_VERSION < $requiredVersion;

        if ( $invalid && is_admin() ) { 
            
            global $bizpanda_issue_plugin;
            $bizpanda_issue_plugin = $pluginTitle;
            
            add_action('admin_notices', 'bizpanda_compability_note');
        }
        return !$invalid;
    }
}

// we don't have to register another version of bizpanda,
// if some version was already registered, so skip the code below
if ( defined('OPANDA_ACTIVE') ) return;

global $bizpanda_versions;

if ( !$bizpanda_versions )
    $bizpanda_versions = array( 'free' => array(), 'premium' => array() );
    $bizpanda_versions['free']['112'] = dirname(__FILE__) . '/boot.php';



if ( !function_exists( 'bizpanda_connect') ) {

    function bizpanda_connect( ) {
        
        if ( !defined('OPANDA_ACTIVE') ) {
            
            global $bizpanda_versions;

            $assembly = !empty( $bizpanda_versions['premium'] ) ? 'premium' : 'free';

            $keys = array_keys( $bizpanda_versions[$assembly] );
            sort( $keys );

            $version = end( $keys );
            require $bizpanda_versions[$assembly][$version];
        }
        
        do_action('bizpanda_init');
    }
    
    add_action('plugins_loaded', 'bizpanda_connect');
}