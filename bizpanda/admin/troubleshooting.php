<?php

function opanda_troubleshooting() {
    if ( !isset( $_GET['opanda_trouble'] ) ) return;
    $trouble = $_GET['opanda_trouble'];
    
    switch ( $trouble ) {
        case 'bulk-lock':
        opanda_trouble_reset_bulk_lock();
        break;
    }
}
add_action('admin_init', 'opanda_troubleshooting');

function opanda_trouble_reset_bulk_lock() {
    delete_option('onp_sl_bulk_lockers');
}