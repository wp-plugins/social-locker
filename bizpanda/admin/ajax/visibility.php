<?php

/**
 * Returns a list of available roles.
 */
function bp_ajax_get_user_roles() {    
    global $wp_roles;
    $roles = $wp_roles->roles;
    
    $values = array();
    foreach( $roles as $roleId => $role ) {
        $values[] = array(
            'value' => $roleId,
            'title' => $role['name']
        );
    }
    
    $values[] = array(
        'value' => 'guest',
        'title' => __('Guest', 'bizpanda')
    );
    
    $result = array(
        'values' => $values
    );
    
    echo json_encode($result);
    exit;
}

add_action('wp_ajax_bp_ajax_get_user_roles', 'bp_ajax_get_user_roles');