<?php
/**
 * Social Locker Type
 * Declaration for custom post type of Social Locler.
 * @link http://codex.wordpress.org/Post_Types
 */
class OnpSL_SocialLockerType extends FactoryTypes300_Type {
    
    /**
     * Custom post name.
     * @var string 
     */
    public $name = 'social-locker';
    
    /**
     * Singular title for labels of the type in the admin panel.
     * @var string 
     */
    public $singularTitle = 'Social Locker';
    
    /**
     * Plural title for labels of the type in the admin panel.
     * @var string 
     */
    public $pluralTitle = 'Social Lockers';
    
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
    
    public function useit() { return true;
// Dear user, who's reading this text now, the condition below is responsible for checking a license key.
// Sure you can change that and all features unlocked for free. Do it if you want.
// But please keep in mind, we could encrypt the code better. There are two reasons why we did not make that.
// The 1st reason is that the better encryption will slow the plugin.
// We make plugins for the people and want our plugins to work ideally for you.
// The 2nd reason is that we entrusted you, the user who're using our plugin, and we hope you will enjoy it.
// Thank you! Yours faithfully, OnePress.
// < condition start
global $sociallocker;
if ( in_array( $sociallocker->license->type, array( 'paid','trial' ) ) ) {
 return true; 
}
// condition end >

        return false;
    }
    
    /**
     * Type configurator.
     */
    public function configure() {
        global $sociallocker;
        
        /**
         * Menu
         */
        
        $this->menu->title = 'Social Lockers';
        $this->menu->icon = ONP_SL_PLUGIN_URL . '/assets/admin/img/menu-icon.png';
        
        /**
         * Metaboxes
         */
            $this->metaboxes[] = "OnpSL_BasicOptionsMetaBox";
            $this->metaboxes[] = "OnpSL_ManualLockingMetaBox";   
            $this->metaboxes[] = "OnpSL_BulkLockingMetaBox"; 
            $this->metaboxes[] = "OnpSL_PreviewMetaBox"; 

            if ( !$sociallocker->license->hasKey() ) {
                $this->metaboxes[] = "OnpSL_MoreFeaturesMetaBox";
            } 
        



        /**
         * View table
         */
        
        $this->viewTable = new SocialLockerViewTable();
        
        /**
         * Scripts & styles
         */
        
        $this->scripts->request('jquery', 'jquery-effects-highlight');
        
        $this->scripts->add( ONP_SL_PLUGIN_URL . '/assets/admin/js/json2.js');
        $this->scripts->add( ONP_SL_PLUGIN_URL . '/assets/admin/js/preview.030000.js');
        $this->scripts->add( ONP_SL_PLUGIN_URL . '/assets/admin/js/sociallocker.edit.030005.js')->request('jquery-ui-sortable');       
        $this->styles->add( ONP_SL_PLUGIN_URL . '/assets/admin/css/sociallocker.edit.030005.css');
            $this->scripts->add( ONP_SL_PLUGIN_URL . '/assets/admin/js/jquery.qtip.min.js');       
            $this->styles->add( ONP_SL_PLUGIN_URL . '/assets/admin/css/jquery.qtip.min.css');
        

    }
}

FactoryTypes300::register('OnpSL_SocialLockerType', $sociallocker);