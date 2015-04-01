<?php

/**
 * Opt-In Panda Type
 * Declaration for custom post type of Social Locler.
 * @link http://codex.wordpress.org/Post_Types
 */
class OPanda_PandaItemType extends FactoryTypes322_Type {
    
    /**
     * Custom post name.
     * @var string 
     */
    public $name = 'opanda-item';
    
    /**
     * Singular title for labels of the type in the admin panel.
     * @var string 
     */
    public $singularTitle = 'Opt-In Panda';
    
    /**
     * Plural title for labels of the type in the admin panel.
     * @var string 
     */
    public $pluralTitle = 'Opt-In Pandas';
    
    /**
     * Template that defines a set of type options.
     * Allowed values: public, private, internal.
     * @var string 
     */
    public $template = 'private';
    
    /**
     * Capabilities for roles that have access to manage the type.
     * @link http://codex.wordpress.org/Roles_and_Capabilities
     * @var array 
     */
    public $capabilities = array('administrator');
    
    public function useit() { return true; global $sociallocker;
if ( in_array( $sociallocker->license->type, array( 'paid','trial' ) ) ) {
 return true; 
}

        return false;
    }
    
    function __construct($plugin) {
        parent::__construct($plugin);
        
        $this->pluralTitle = __('Lockers', 'optinpanda');
        $this->singularTitle = __('Locker', 'optinpanda');
    }
    
    /**
     * Type configurator.
     */
    public function configure() {
        global $bizpanda;

        /**
         * Labels
         */
        
        $pluralName = $this->pluralTitle;
        $singularName = $this->singularTitle;

        $labels = array(
            'singular_name' => $this->singularTitle,
            'name' => $this->pluralTitle,          
            'all_items' => sprintf( __('All Lockers', 'optinpanda'), $pluralName ),
            'add_new' => sprintf( __('+ New Locker', 'optinpanda'), $singularName ),
            'add_new_item' => sprintf( __('Add new', 'optinpanda'), $singularName ),
            'edit' => sprintf( __('Edit', 'optinpanda') ),
            'edit_item' => sprintf( __('Edit Item', 'optinpanda'), $singularName ),
            'new_item' => sprintf( __('New Item', 'optinpanda'), $singularName ),
            'view' => sprintf( __('View', 'factory') ),
            'view_item' => sprintf( __('View Item', 'optinpanda'), $singularName ),
            'search_items' => sprintf( __('Search Items', 'optinpanda'), $pluralName ),
            'not_found' => sprintf( __('No Items found', 'optinpanda'), $pluralName ),
            'not_found_in_trash' => sprintf( __('No Items found in trash', 'optinpanda'), $pluralName ),
            'parent' => sprintf( __('Parent Item', 'optinpanda'), $pluralName )
        );

        $this->options['labels'] = apply_filters('opanda_items_lables', $labels);

        /**
         * Menu
         */
        
        $this->menu->title = BizPanda::getMenuTitle();
        $this->menu->icon = BizPanda::getMenuIcon();

        /**
         * View table
         */
        
        $this->viewTable = 'OPanda_ItemsViewTable';
        
        /**
         * Scripts & styles
        */  
        
        $this->scripts->request( array( 'jquery', 'jquery-effects-highlight' ) );
        
        $this->scripts->request( array( 
            'bootstrap.transition',
            'bootstrap.tab',
            'holder.more-link',
            'control.checkbox',
            'control.dropdown',
            'control.list',
            'bootstrap.modal',
            ), 'bootstrap' );
        
        $this->styles->request( array( 
            'bootstrap.core', 
            'bootstrap.form-group', 
            'bootstrap.form-metabox', 
            'bootstrap.tab', 
            'bootstrap.wp-editor', 
            'bootstrap.separator',
            'control.checkbox',
            'control.dropdown',
            'control.list',
            'holder.more-link'
            ), 'bootstrap' ); 
        
        $this->scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/filters.010000.js');    
        $this->scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/libs/json2.js');
        $this->scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/preview.010000.js');
        $this->scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/item-edit.010008.js')->request('jquery-ui-sortable');       
        $this->styles->add( OPANDA_BIZPANDA_URL . '/assets/admin/css/item-edit.010008.css');
            $this->styles->add( OPANDA_BIZPANDA_URL . '/assets/admin/css/item-edit.010000-en_US.css');  
        

            $this->scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/libs/jquery.qtip.min.js');       
            $this->styles->add( OPANDA_BIZPANDA_URL . '/assets/admin/css/libs/jquery.qtip.min.css');
        

        
        do_action( 'opanda_panda-item_edit_assets', $this->scripts, $this->styles );   
    }
}

global $bizpanda;
FactoryTypes322::register('OPanda_PandaItemType', $bizpanda);