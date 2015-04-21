<?php

class FactoryPages321_Page {
    
    /**
     * Current Factory Plugin.
     * @var Factory325_Plugin
     */
    public $plugin;
    
    /**
     * Page id used to call.
     * @var string 
     */
    public $id;
    
    public function __construct( $plugin = null ) {
        $this->plugin = $plugin;
        
        if ( $plugin ) {
            $this->scripts = $this->plugin->newScriptList();
            $this->styles = $this->plugin->newStyleList();    
        } else {
         
            $coreVersion = isset( $this->deps['factory_core'] ) ? $this->deps['factory_core'] : null;
            if ( empty( $coreVersion ) ) throw new Exception ("The version of the 'factory_core' is not specified in the var \$deps for the page '{$this->id}'.");
        
            $scriptsClass = 'Factory' . $coreVersion . '_ScriptList';
            $stylesClass = 'Factory' . $coreVersion . '_StyleList';
            
            $this->scripts = new $scriptsClass();
            $this->styles = new $stylesClass();
        }
    }

    public function assets($scripts, $styles) {}
        
    /**
     * Shows page.
     */
    public function show() {
        
        if ( $this->result ) {
            echo $this->result;
        } else {
            $action = isset( $_GET['action'] ) ? $_GET['action'] : 'index';
            $this->executeByName( $action );  
        }
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
    
    /**
     * Renders a template.
     * @param string $path
     * @param mixed $model
     */
    protected function template($path, $model, $bodyContent = null) {
        $layout = null;
        $file = $this->plugin->templateRoot . '/' . $path . '.tpl.php';
        
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        
        if ( !empty($content) ) {
            $content = str_replace('{pagebody}', $bodyContent, $content);
        }
        
        if ( !empty($layout) ) {
            $this->template($layout, $model, $content);
        } else {
            echo $content;
        }
    }
}