<?php
/**
 * A group of classes and methods to create and manage notices.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-notices 
 * @since 1.0.0
 */

// creating a license manager for each plugin created via the factory
add_action('factory_notices_323_plugin_created', 'factory_notices_323_plugin_created');
function factory_notices_323_plugin_created( $plugin ) {
    new FactoryNotices323( $plugin );
}

/**
 * A class to manage notices.
 * 
 * @since 1.0.0
 */
class FactoryNotices323 {

    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        add_action('current_screen', array( $this, 'currentScreenAction') );
    }
    
    public function currentScreenAction() {
        
        $this->notices = apply_filters('factory_notices_' . $this->plugin->pluginName, array());
        if ( count( $this->notices ) == 0 ) return;
        
        $screen = get_current_screen();

        $this->hasNotices = false;
        foreach ($this->notices as $notice) {

            $where = empty( $notice['where'] ) ? array('plugins','dashboard','edit') : $notice['where'];
            $screen = get_current_screen();

            if ( in_array($screen->base, $where) ) {
                $this->hasNotices = true;
                break;
            };
        }

        if ( $this->hasNotices ) {
            add_action('factory_bootstrap_enqueue_scripts_' . $this->plugin->pluginName, array( $this, 'enqueueBootstrapScripts' ));      
            add_action('admin_enqueue_scripts', array( $this, 'enqueueScripts' ));        
            add_action('admin_notices', array( $this, 'showNotices' ));
        } 
    }
    
    public function enqueueBootstrapScripts() {
        $this->plugin->bootstrap->enqueueStyle('bootstrap.core');
    }
    
    public function enqueueScripts() {
        wp_enqueue_style('factory-notices-323-css', FACTORY_NOTICES_323_URL . '/assets/css/notices.css');      
        wp_enqueue_script('factory-notices-323-js', FACTORY_NOTICES_323_URL . '/assets/js/notices.js');
    }
    
    public function showNotices() {

        if ( count( $this->notices ) == 0 ) return;

        if ( 
            !current_user_can('activate_plugins') || 
            !current_user_can('edit_plugins') || 
            !current_user_can('install_plugins')) return;
        
        ?>

        <?php if ( $this->hasNotices ) { ?>
        <div class="updated factory-bootstrap-329 factory-fontawesome-320 factory-notices-323-notices">
        <?php
        foreach ($this->notices as $notice) {
            $this->showNotice($notice);
        }
        ?>
        </div>
        <?php } ?>
        <?php
    }
    
    /**
     * Shows a notice.
     * 
     * The data has the followin format:
     *  "id" => an id of the notice
     *  "where" => a place where the notice should be visible (plugins, dashboard and so on)
     *  "header" => a header of the notice
     *  "message" => a message of the notice
     *  "class" => an extra class to add to the notice
     *  "close" => if true, then the close icon will be available to dissmish the notice
     * 
     * @since 1.0.0
     * @param type $data
     * @return void
     */
    public function showNotice( $data ) {
        
        $type = empty( $data['type'] ) ? 'offer' : $data['type'];
        $subtype = empty( $data['subtype'] ) ? 'none' : $data['subtype'];
        
        $position = empty( $data['position'] ) ? 'notice' : $data['position'];
        $layout = empty( $data['layout'] ) ? 'standard' : $data['layout'];
        
        // checking if we should show a notice on a current page
        $where = empty( $data['where'] ) ? array('plugins','dashboard', 'edit') : $data['where'];
        $screen = get_current_screen();

        if ( !in_array($screen->base, $where) ) return;
        
        // setups a content of the notice to display
        $header = empty( $data['header'] ) ? null : $data['header'];
        $message = empty( $data['message'] ) ? null : $data['message'];

        $hasHeader = !empty( $header );
        $hasMessage = !empty( $message );
        $hasClose = isset( $data['close'] ) ? $data['close'] : false;
        $hasIcon = isset( $data['icon'] );   
        
        if ( !isset( $data['buttons'] ) ) $data['buttons'] = array();
        
        $classes = array();
        if ( !empty( $data['class'] ) ) $classes[] = $data['class'];
        if ( !empty( $data['plugin'] ) ) $classes[] = 'notice-' . $data['plugin'];
        if ( $hasIcon ) $classes[] = 'factory-has-icon';  
        
        ?>
        <div class="factory-notice-item factory-<?php echo $position ?> <?php echo implode(' ', $classes) ?>" id="<?php echo $data['id'] ?>">
            <div class="factory-inner-wrap"> 
                <?php if ( $hasClose ) { ?>
                <a href="#" class="factory-close close factory-corner-close" data-close="<?php echo $data['close'] ?>" title="Dismiss this message."><i class="fa fa-times"></i></a>
                <?php } ?>
                <?php if ( $hasIcon ) { ?>
                    <i class="factory-icon <?php echo $data['icon'] ?>"></i>
                <?php } ?>                
                <div class="factory-message-container">                   
                    <?php if ( $hasHeader ) { ?>
                    <h4 class="factory-header alert-heading"><?php echo $header ?></h4>
                    <?php } ?>
                    <span class="factory-message"><?php echo $message ?></span>
                </div>
                    
                <?php if ( !empty( $data['buttons'] ) ) { ?>
                <div class="factory-buttons actions">
                    <?php foreach( $data['buttons'] as $buttonData ) { ?>
                    <?php $this->renderNoticeButton( $buttonData, $data['id'] ) ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Show a notice button.
     * 
     * @since 1.0.0
     * @return void
     */
    public function renderNoticeButton( $data, $id ) {
        $title = $data['title'];
        $action = $data['action'];
        
        $classes = array();
        if ( !empty( $data['class'] ) ) $classes[] = $data['class'];
        
        $onclick = '';
        if ( $action == 'x' ) { 
            $onclick = "factory_notices_323_hide_notice('$id', false); return false;";
            $action = '#';
        }

        if ( $action == 'xx' ) { 
            $action = '#';
            $onclick = "factory_notices_323_hide_notice('$id', true); return false;"; 
        }

        ?>
        <a href="<?php echo $action ?>" onclick="<?php echo $onclick ?>" class="factory-button <?php echo implode(' ', $classes) ?>">
            <?php echo $title ?>
        </a>
        <?php 
    }
    
    public static function resiter( $className, $plugin ) {
        
    }
}