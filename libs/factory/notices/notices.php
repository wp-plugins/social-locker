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

add_action('admin_notices', 'factory_notices_300_admin_notices');
function factory_notices_300_admin_notices() {
    $manager = new FactoryNotices300();
    $manager->showNotices();
}

/**
 * A class to manage notices.
 * 
 * @since 1.0.0
 */
class FactoryNotices300 {
    
    public function showNotices() {
        
        $notices = apply_filters('factory_notices_300', array());
        if ( count($notices) == 0 ) return;

        if ( 
            !current_user_can('activate_plugins') || 
            !current_user_can('edit_plugins') || 
            !current_user_can('install_plugins')) return;

        wp_enqueue_style('factory-notices-300-css', FACTORY_NOTICES_300_URL . '/assets/css/notices.css');      
        wp_enqueue_script('factory-notices-300-js', FACTORY_NOTICES_300_URL . '/assets/js/notices.js');
        
        foreach ($notices as $notice) {
            $this->showNotice($notice);
        }
    }
    
    /**
     * Shows a notice.
     * 
     * The data has the followin format:
     *  "id" => an id of the notice
     *  "type"  => a type of the notice, this will be appended to a notice class name (alert, offer)
     *  "subtype"  => a sub type of the notice, this will be appended to a notice class name too (hot, danger)
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
        
        // checking if we should show a notice on a current page
        $where = empty( $data['where'] ) ? array('plugins','dashboard') : $data['where'];
        $screen = get_current_screen();
        if ( !in_array($screen->base, $where) ) return;

        // setups a content of the notice to display
        $header = empty( $data['header'] ) ? null : $data['header'];
        $message = empty( $data['message'] ) ? null : $data['message'];

        $hasHeader = !empty( $header );
        $hasMessage = !empty( $message );
        $hasClose = isset( $data['close'] ) ? $data['close'] : false;
        
        $classes = array();
        $classes[] = 'updated';
        $classes[] = 'factory-notices-300-notice';
        if ( !empty( $data['class'] ) ) $classes[] = $data['class'];
        $classes[] = ' factory-' . $type;
        $classes[] = ' factory-' . $subtype;  
        $classes[] = ' factory-' . $type . '-' . $subtype;
        ?>
            <div class="<?php echo implode(' ', $classes) ?>" id="<?php echo $data['id'] ?>">
            <div class="factory-inner-wrap"> 
                <?php if ( $hasClose ) { ?>
                <a href="#" class="factory-close" title="Dismiss this message."></a>
                <?php } ?>
                <div class="factory-message-container">
                    <?php if ( $hasHeader ) { ?>
                    <strong class="factory-header"><?php echo $header ?></strong>
                    <?php } ?>
                    <span class="factory-message"><?php echo $message ?></span>
                </div>

                <div class="factory-buttons">
                    <?php foreach( $data['buttons'] as $buttonData ) { ?>
                    <?php $this->renderNoticeButton( $buttonData, $data['id'] ) ?>
                    <?php } ?>
                </div>
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
        $class = isset( $data['type'] ) ? 'factory-button-' . $data['type'] : 'factory-button-default';
        
        $onclick = '';
        if ( $action == 'x' ) { 
            $onclick = "factory_notices_300_hide_notice('$id', false); return false;";
            $action = '#';
        }

        if ( $action == 'xx' ) { 
            $action = '#';
            $onclick = "factory_notices_300_hide_notice('$id', true); return false;"; 
        }

        ?>
        <a href="<?php echo $action ?>" onclick="<?php echo $onclick ?>" class="factory-button <?php echo $class ?>">
            <?php echo $title ?>
        </a>
        <?php 
    }
}