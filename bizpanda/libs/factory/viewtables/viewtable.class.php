<?php

abstract class FactoryViewtables320_Viewtable {

    /**
     * A type used to display the table.
     * @var FactoryTypes322_Type 
     */
    public $type;
    
    /**
     * Table's columns
     * @var FactoryViewtables320_Columns 
     */
    public $columns;
    
    /**
     * Scripts that must be included on edit page.
     * @var FactoryScriptList 
     */
    public $scripts;
    
    /**
     * Styles that must be included on edit page.
     * @var FactoryStyleList 
     */  
    public $styles;
    
    /**
     * Creates a new instance of a viewtabl.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
    }
    
    public function connect( $type ) {
        
        $this->type = $type;
        $this->columns = new FactoryViewtables320_Columns();
        
        $this->scripts = $this->plugin->newScriptList();
        $this->styles = $this->plugin->newStyleList();
        
        $this->configure();
        
        add_filter('manage_edit-' . $type->name . '_columns', array($this, 'actionColumns'));
        add_action('manage_' . $type->name . '_posts_custom_column', array($this, 'actionColumnValues'), 2);
        
        // includes styles and scripts
        if ( !$this->scripts->isEmpty() || !$this->styles->isEmpty() ) {
            add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
        }
        
        // remove quiik edit for non-public types
        if ( $type->template !== 'public' ) {
            add_filter('post_row_actions', array( $this, 'actionPostRowActions' ),10,2);
        }
        
        // remove buld edit action
        if ( $type->template !== 'public' ) {
            add_filter('bulk_actions-edit-' . $this->type->name, array( $this, 'actionBulk' ) );
        } 
    }
    
    public function configure() {}
    
    public function actionColumns( $columns ) {
        
        if ($this->columns->isClearn) {
            $columns = array();
            $columns["cb"] = "<input type=\"checkbox\" />";
        }
        
        foreach($this->columns->getAll() as $column) {
            $columns[$column['id']] = $column['title'];
        }
        
        return $columns;
    }
    
    public function actionColumnValues( $column ) {
        global $post;
        
        $postfix = strtoupper(substr($column, 0, 1)) . substr($column, 1, strlen($column));
        $functionName = 'column' . $postfix;
        $fullMode = ( isset( $_GET['mode'] ) && $_GET['mode'] == 'excerpt' );
        
        if ( !method_exists($this, $functionName) ) return false;
        call_user_func(array($this, $functionName), $post, $fullMode);   
    }
    
    /**
     * Actions that includes registered fot this type scritps and styles.
     * @global type $post
     * @param type $hook
     */
    public function actionAdminScripts( $hook ) {
        global $post;

        if ( !$post ) return;
	if ( $hook !== 'edit.php' ) return;
        if ( $post->post_type != $this->type->name ) return;
        if ( $this->scripts->isEmpty() && $this->styles->isEmpty() ) return;
                
        $this->scripts->connect();
        $this->styles->connect(); 
    }
    
    public function actionPostRowActions( $actions ) {
        global $post;
        
        if( $post->post_type !== $this->type->name ) return $actions;
        unset($actions['inline hide-if-no-js']);
        return $actions;
    }
    
    public function actionBulk( $actions ) {
        global $post;
        
        if ( !$post ) return $actions;
        if( $post->post_type !== $this->type->name ) return $actions;
        unset( $actions[ 'edit' ] );
        return $actions;
    }    
}