<?php

abstract class FactoryFR100ViewTable {
    
    /**
     * Current factory.
     * @var Factory 
     */
    public $plugin;
    
    /**
     * A type used to display the table.
     * @var type 
     */
    public $type;
    
    /**
     * Table's columns
     * @var FactoryFR100ViewTableColumns 
     */
    public $columns;
    
    /**
     * Scripts that must be included on edit page.
     * @var FactoryFR100ScriptList 
     */
    public $adminScripts;
    
    /**
     * Styles that must be included on edit page.
     * @var FactoryFR100StyleList 
     */  
    public $adminStyles;
    
    public function __construct( FactoryFR100Plugin $plugin = null ) {
        $this->plugin = $plugin;
    }
    
    public function register( FactoryFR100Type $type ) {
        
        $this->type = $type;
        $this->plugin = $type->plugin;
        $this->columns = new FactoryFR100ViewTableColumns();
        
        $this->adminScripts = new FactoryFR100ScriptList( $type->plugin );
        $this->adminStyles = new FactoryFR100StyleList( $type->plugin ); 
        
        $this->configure( $this, $this->adminScripts, $this->adminStyles );
        
        add_filter('manage_edit-' . $type->name . '_columns', array($this, 'actionColumns'));
        add_action('manage_' . $type->name . '_posts_custom_column', array($this, 'actionColumnValues'), 2);
        
        // includes styles and scripts
        if ( !$this->adminScripts->isEmpty() || !$this->adminStyles->isEmpty() ) {
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
    
    public abstract function configure( 
            FactoryFR100ViewTable $table, 
            FactoryFR100ScriptList $scripts, 
            FactoryFR100StyleList $styles );
    
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
        if ( $this->adminScripts->isEmpty() && $this->adminStyles->isEmpty() ) return;
                
        $this->adminScripts->connect();
        $this->adminStyles->connect(); 
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