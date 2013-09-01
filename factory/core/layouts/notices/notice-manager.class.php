<?php

class FactoryPR108NoticeManager {
    
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        
        add_action('admin_notices', array($this,'show_notices'));
    }
    
    public function show_notices() {
        
        $notices = apply_filters('factory_pr108_admin_notices-' . $this->plugin->pluginName, array(), $this->plugin);
        if ( count($notices) == 0 ) return;

        if ( 
            !current_user_can('activate_plugins') || 
            !current_user_can('edit_plugins') || 
            !current_user_can('install_plugins')) return;

        foreach ($notices as $notice) {
            $this->show_notice($notice);
        }
    }
    
    public function show_notice( $data ) {
        
        $type = empty( $data['type'] ) ? 'offer' : $data['type'];
        
        $where = empty( $data['where'] ) ? array('plugins','dashboard') : $data['where'];
        $screen = get_current_screen();
        if ( !in_array($screen->base, $where) ) return;

        $header = empty( $data['header'] ) ? null : $data['header'];
        $message = empty( $data['message'] ) ? null : $data['message'];

        $hasHeader = !empty( $header );
        $hasMessage = !empty( $message );
        $class = empty( $data['class'] ) ? '' : $data['class'];
        
        if ( factory_pr108_starts_with($type, 'alert') ) {
            $class = $class . ' onp-alert ';
        }
        
        $class .= ' onp-' . $type;

        ?>
        <div class="updated onp-notice <?php echo $class ?>" id="<?php echo $data['id'] ?>">
            <div class="onp-notice-inner-wrap"> 
                <?php if ( !factory_pr108_starts_with($type, 'alert') ) { ?>
                <a href="#" class="onp-notice-close" title="Dismiss this message."></a>
                <?php } ?>
                <div class="onp-message-container">
                    <?php if ( $hasHeader ) { ?>
                    <strong class="onp-notice-header"><?php echo $header ?></strong>
                    <?php } ?>
                    <span class="onp-notice-message"><?php echo $message ?></span>
                </div>

                <div class="onp-notice-buttons">
                    <?php foreach( $data['buttons'] as $buttonData ) { ?>
                    <?php $this->render_notice_button( $buttonData, $data['id'] ) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_notice_button( $data, $id ) {
        $title = $data['title'];
        $action = $data['action'];
        $class = empty( $data['class'] ) ? 'onp-notice-button-default' : 'onp-notice-button-' . $data['class'];

        $onclick = '';
        if ( $action == 'x' ) { 
            $onclick = "fy_hide_notice('$id', false); return false;";
            $action = '#';
        }

        if ( $action == 'xx' ) { 
            $action = '#';
            $onclick = "fy_hide_notice('$id', true); return false;"; 
        }

        ?>
        <a href="<?php echo $action ?>" onclick="<?php echo $onclick ?>" class="onp-notice-button <?php echo $class ?>">
            <?php echo $title ?>
        </a>
        <?php 
    }
}