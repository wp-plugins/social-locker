<?php
/**
 * Ajax requests to get a list of Panda Items to insert.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Returns a list of the lockers.
 */
function opanda_ajax_get_lockers() {
    
    $lockers = get_posts(array(
        'post_type' => OPANDA_POST_TYPE,
        'meta_key' => 'opanda_item',
        'meta_value' => OPanda_Items::getAvailableNames(),
        'numberposts' => -1
    ));
        
    $result = array();
    foreach($lockers as $locker)
    {
        $itemType = get_post_meta( $locker->ID, 'opanda_item', true );
        $item = OPanda_Items::getItem($itemType);
        
        $result[] = array(
            'id' => $locker->ID,
            'title' => empty( $locker->post_title ) ? '(no titled, ID=' . $locker->ID . ')' : $locker->post_title,
            'shortcode' => $item['shortcode'],
            'isDefault' => get_post_meta( $locker->ID, 'opanda_is_default', true )
        );
    }

    echo json_encode($result);
    die();
}

add_action('wp_ajax_get_opanda_lockers', 'opanda_ajax_get_lockers');

