<?php

class FactoryFR102AdminPageManager {

    public $plugin;
    public $pages = array();

    public function __construct(FactoryFR102Plugin $plugin) {
        $this->plugin = $plugin;
        $this->id = empty($this->id) ? str_replace('', '', strtolower( get_class($this) ) ) : $this->id;
        
        add_action('admin_menu', array($this, 'actionAdminMenu'));
    }
    
    /**
     * Registers metabox objects.
     */
    public function register( $adminPages ) {
        if ( !is_array($adminPages) ) $adminPages = array();

        foreach($adminPages as $adminPage) {
            $this->pages[$adminPage->id] = $adminPage;
        }
    }
    
    public function actionAdminMenu() {
        if ( empty($this->pages) ) return;
        
        foreach($this->pages as $page) {
            $page->register();
        }
    }
    
    public function getIds(){
        
        $result = array();
        foreach($this->pages as $page) {
            $result[] = $page->getResultId();
        }   
        return $result;
    }
}
?>
