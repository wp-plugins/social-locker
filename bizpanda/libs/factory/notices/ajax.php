<?php
/**
 * Ajax actions bound with the notices.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-notices  
 * @since 1.0.0
 */

function factory_notices_323_hide() {
    $id = empty( $_POST['id'] ) ? null : $_POST['id'];
    $never = ( empty( $_POST['never'] ) || $_POST['never'] == 'false' ) ? false : true;

    if ( !$id ) exit;

    $notices = get_option("factory_notices_closed", array());
    $notices[$id] = array(
        'never' => $never,
        'time' => time()
    );

    delete_option('factory_notices_closed');
    add_option('factory_notices_closed', $notices);

    echo 'ok';
    exit;
}  
add_action('wp_ajax_factory_notices_323_hide', 'factory_notices_323_hide');