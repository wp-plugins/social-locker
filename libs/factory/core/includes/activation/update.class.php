<?php
/**
 * The file contains a base class for update items of plugins.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Plugin Activator
 * 
 * @since 1.0.0
 */
abstract class Factory308_Update {
    
    /**
     * Current plugin
     * @var Factory308_Plugin
     */
    var $plugin;
    
    public function __construct( Factory308_Plugin $plugin ){
        $this->plugin = $plugin;
    }
    
    abstract function install();
}

?>
