<?php
/**
 * The file contains an interface for all value provides.
 * 
 * A value provider is a provide to get and save values to some stores (database, metadata and so on).
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The interface for all value provides.
 * 
 * @since 1.0.0
 */
interface IFactoryForms305_ValueProvider {
    
    /**
     * Commit all changes.
     * 
     * @since 1.0.0
     * @return void
     */
    public function saveChanges();
    
    /**
     * Gets a value by its name.
     * 
     * @since 1.0.0
     * @param string $name A value name to get.
     * @param mixed $default A default to return if a given name doesn't exist.
     * @return mixed
     */
    public function getValue( $name, $default = null );
    
    /**
     * Sets a value by its name.
     * 
     * @since 1.0.0
     * @param seting $name A value name to set.
     * @param mixed $value A value to set.
     * @return void
     */
    public function setValue( $name, $value );
}