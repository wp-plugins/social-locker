<?php

class FactoryFR100Page {
    
    /**
     * Current Factory Plugin.
     * @var FactoryFR100Plugin
     */
    public $plugin;
    
    /**
     * Page id used to call.
     * @var string 
     */
    public $id;
    
    public function __construct( FactoryFR100Plugin $plugin = null ) {
        $this->plugin = $plugin;
        $this->scripts = new FactoryFR100ScriptList( $plugin );
        $this->styles = new FactoryFR100StyleList( $plugin ); 
    }

    public function assets(FactoryFR100ScriptList $scripts, FactoryFR100StyleList $styles) {}
        
    /**
     * Shows page.
     */
    public function show() {
        
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'index';
        $actionFunction = $action . 'Action';

        $cancel = $this->OnActionExecuting($action);
        if ( $cancel === false ) return;
        
        call_user_func_array(array($this,$actionFunction), array());
        $this->OnActionExected($action);
    }
    
    protected function OnActionExecuting( $action ) {}
    
    protected function OnActionExected( $action ) {}
    
    protected function script( $path ) {
        wp_enqueue_script( $path, $path, array('jquery'), false, true );
    }
}