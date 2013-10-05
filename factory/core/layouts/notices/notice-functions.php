<?php

if ( !function_exists('fy_hide_notice')) {
    
    function fy_hide_notice() {
        $id = empty( $_POST['id'] ) ? null : $_POST['id'];
        $never = ( empty( $_POST['never'] ) || $_POST['never'] == 'false' ) ? false : true;

        if ( !$id ) exit;
        
        $notices = get_option("fy_closed_notices", array());
        $notices[$id] = array(
            'never' => $never,
            'time' => time()
        );
        
        delete_option('fy_closed_notices');
        add_option('fy_closed_notices', $notices);
        
        echo 'ok';
        exit;
    }  
    
    add_action('wp_ajax_fy_hide_notice', 'fy_hide_notice');
}
