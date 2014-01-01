<?php

add_action('wp_ajax_sociallocker_loader', 'sociallocker_load_ajax_content');
add_action('wp_ajax_nopriv_sociallocker_loader', 'sociallocker_load_ajax_content');
    
function sociallocker_load_ajax_content() {

    $hash = $_POST['hash'];
    $lockerId = @intval( $_POST['lockerId'] );

    if (empty($hash) || empty($lockerId)) return "";

    echo get_post_meta($lockerId, 'sociallocker_locker_content_hash_' . $hash, true);
    die();
}