<?php
/**
 * Social Locker Type
 * Declaration for custom post type of Social Locler.
 * @link http://codex.wordpress.org/Post_Types
 */
class SocialLockerType extends FactoryPR108Type {
    
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
    
    public function useit() {
// This condition is responsible for the license and features that it provides.
// Sure you can change it and get all features for free. Do it if you want.
// But please remember, we could encrypt this sources to protect it.
// But we entrusted you, the man who use our plugins, and we hope you will enjoy it.
// Thank you! Yours faithfully, OnePress.
// < condition start
global $socialLocker;
if ( in_array( $socialLocker->license->type, array( 'paid','trial' ) ) ) {
 return true; 
}
// condition end >

        return false;
    }
    
    /**
     * Type configurator.
     * 
     * @param FactoryType $type
     * @param FactoryTypeMenu $menu
     * @param FactoryMetaboxCollection $metaboxes
     */
    public function configure( 
            FactoryPR108Type $type, 
            FactoryPR108TypeMenu $menu, 
            FactoryPR108MetaboxCollection $metaboxes ) {

        /**
         * Menu
         */
        
        $menu->title = 'Social Lockers';
        $menu->icon = '~/assets/admin/img/menu-icon.png';
        
        /**
         * Metaboxes
         */
            $metaboxes->add( new SocialLockerBasicOptionsMetaBox() );
            $metaboxes->add( new SociallockerPreviewMetaBox() );
            $metaboxes->add( new SociallockerSupportMetaBox() );
            $metaboxes->add( new SocialLockerSocialOptionsMetaBox() );
            $metaboxes->add( new SocialLockerFunctionOptionsMetaBox() );   
        


        /**
         * View table
         */
        
        $type->viewTable = new SocialLockerViewTable();
        
        /**
         * Scripts & styles
         */
        
        $type->adminScripts->add('~/admin/js/locker-edit.020016.js')->request('jquery-ui-sortable');       
        $type->adminStyles->add('~/admin/css/locker-edit.020006.css');
    }
}

$socialLocker->registerType('SocialLockerType');