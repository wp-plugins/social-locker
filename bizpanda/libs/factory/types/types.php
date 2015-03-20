<?php
/**
 * A group of classes and methods to create and manage custom types.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

add_action('factory_325_plugin_activation', 'FactoryTypes322::activationHook');
add_action('factory_325_plugin_deactivation', 'FactoryTypes322::deactivationHook');

/**
 * A base class to manage types. 
 * 
 * @since 1.0.0
 */
class FactoryTypes322 {
    
    /**
     * Registered custom types.
     * 
     * @since 1.0.0
     * @var FactoryTypes322_Type[] 
     */
    private static $types = array();
    
    /**
     * Registers a new custom type.
     * 
     * If the second argument is given, capabilities for this type 
     * will be setup on the plugin configuration.
     * 
     * @since 1.0.0
     * @param string A class name of a custom type.
     * @param Factory325_Plugin 
     * @return void
     */
    public static function register( $className, $plugin = null ) {
        $type = new $className( $plugin );

        $pluginName = !empty($plugin) ? $plugin->pluginName : '-';
        if ( !isset( self::$types[$pluginName] ) ) self::$types[$pluginName] = array();
        
        self::$types[$pluginName][] = $type;
    }
    
    /**
     * A plugin activation hook.
     * 
     * @since 1.0.0
     * @param Factory325_Plugin 
     * @return void
     */
    public static function activationHook( $plugin ) {
        $pluginName = $plugin->pluginName;

        // Sets capabilities for types.
        if ( isset( self::$types[$pluginName] ) ) {
            foreach(self::$types[$pluginName] as $type) {
                if ( empty( $type->capabilities )) continue;
                foreach( $type->capabilities as $roleName ) {
                    $role = get_role( $roleName );
                    if ( !$role ) continue;

                    $role->add_cap('edit_' . $type->name); 
                    $role->add_cap('read_' . $type->name);
                    $role->add_cap('delete_' . $type->name);
                    $role->add_cap('edit_' . $type->name . 's');
                    $role->add_cap('edit_others_' . $type->name . 's');
                    $role->add_cap('publish_' . $type->name . 's'); 
                    $role->add_cap('read_private_' . $type->name . 's');      
                }
            }
        }
    }
    
    /**
     * A plugin deactivation hook.
     * 
     * @since 1.0.0
     * @param Factory325_Plugin 
     * @return void
     */
    public static function deactivationHook( $plugin ) {
        
        $pluginName = $plugin->pluginName;
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        
        // Sets capabilities for types.
        if ( isset( self::$types[$pluginName] ) ) {
            foreach(self::$types[$pluginName] as $type) {
                if ( empty( $type->capabilities )) continue;

                foreach( $all_roles as $roleName => $roleInfo ) {

                    $role = get_role( $roleName );
                    if ( !$role ) continue;

                    $role->remove_cap( 'edit_' . $type->name ); 
                    $role->remove_cap( 'read_' . $type->name );
                    $role->remove_cap( 'delete_' . $type->name );
                    $role->remove_cap( 'edit_' . $type->name . 's' );
                    $role->remove_cap( 'edit_others_' . $type->name . 's' );
                    $role->remove_cap( 'publish_' . $type->name . 's' ); 
                    $role->remove_cap( 'read_private_' . $type->name . 's' );      
                } 
            }
        }
    }
}