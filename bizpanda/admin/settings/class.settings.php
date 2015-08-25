<?php
/**
 * The base class for screens of settings.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @since 1.0.0
 */
abstract class OPanda_Settings {

    /**
     * Saves the current page object to make available the URLs methods.
     * And calls the init method to set notices.
     * 
     * @since 1.0.0
     * 
     * @param FactoryPages321_AdminPage $page
     * @return OPanda_Settings
     */
    public function __construct( $page ) {
        $this->page = $page;
        $this->plugin = $page->plugin;
        
        add_action("opanda_{$this->id}_settings_saving", array($this, 'onSaving'));
        add_action("opanda_{$this->id}_settings_saved", array($this, 'onSaved'));
        add_filter("opanda_{$this->id}_settings_redirect_args", array( $this, 'addErrorsToRedirectArgs') );
        
        $this->isSaving = isset( $_POST['save-action'] );
        
        if ( isset( $_REQUEST['opanda_error'] ) ) {
            $this->error = urldecode( $_REQUEST['opanda_error'] );
        }
        
        $this->init();
    }
    
    /**
     * The success notice to display.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $success = null;
    
    /**
     * The error notice to display.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $error = null;
    
    /**
     * Inits the settings.
     * Here you can set the notices to display.
     * 
     * @since 1.0.0
     * @return void
     */
    public function init() {}

    /**
     * Shows the header html of the settings.
     * Usually it's a concise description of the current screen of the settings.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {}
    
    /**
     * Shows the footer html of the settings. Currently it's not used.
     * 
     * @since 1.0.0
     * @return void
     */
    public function footer() {}
    
    /**
     * Returns the array of the options to display.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    abstract public function getOptions();
    
    /**
     * Builds an URL for the specified action with the set arguments.
     * 
     * @since 1.0.0
     * @param string $action An action of the current screen of settings.
     * @param string[] $args A set of extra arguments.
     * @return string The result URL.
     */
    public function getActionUrl( $action = 'index', $args = array() ) {
        
        $args['opanda_screen'] = $this->id;
        if ( 'index' !== $action ) $args['opanda_action'] = $action;
        return $this->page->getActionUrl('index', $args);
    }
    
    /**
     * Prints an URL for the specified action with the set arguments.
     * 
     * @since 1.0.0
     * @param string $action An action of the current screen of settings.
     * @param string[] $args A set of extra arguments.
     * @return string The result URL.
     */
    public function actionUrl( $action = 'index', $args = array() ) {
        echo $this->getActionUrl( $action );
    }
    
    /**
     * Redirects to the specified action with the set arguments.
     * 
     * @since 1.0.0
     * @param string $action An action of the current screen of settings.
     * @param string[] $args A set of extra arguments.
     * @return string The result URL.
     */
    public function redirectToAction( $action = 'index', $args = array() ) {
        
        wp_redirect( $this->getActionUrl( $action = 'index', $args) );     
        exit;
    }
    
    /**
     * Calls before saving the settings.
     * 
     * @since 1.0.0
     * @return void
     */
    public function onSaving() {}
    
    /**
     * Calls after the form is saved.
     * 
     * @since 1.0.0
     * @return void
     */
    public function onSaved() {}
    
    /**
     * Shows an error.
     */
    public function showError( $text ) {
        $this->error = $text;
    }
    
    public function addErrorsToRedirectArgs( $args ) {
        if ( empty( $this->error ) || !$this->isSaving ) return $args;
        $args['opanda_error'] = urlencode($this->error);
        return $args;
    }
}