<?php

class FactoryFR105Page {
    
    /**
     * Current Factory Plugin.
     * @var FactoryPlugin
     */
    public $plugin;
    
    /**
     * Page id used to call.
     * @var string 
     */
    public $id;
    
    public function __construct( FactoryFR105Plugin $plugin = null ) {
        $this->plugin = $plugin;
        $this->scripts = new FactoryFR105ScriptList( $plugin );
        $this->styles = new FactoryFR105StyleList( $plugin ); 
    }

    public function assets(FactoryFR105ScriptList $scripts, FactoryFR105StyleList $styles) {}
        
    /**
     * Shows page.
     */
    public function show() {
        
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'index';
        $this->executeByName( $action );
    }
    
    public function executeByName( $action ) {
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