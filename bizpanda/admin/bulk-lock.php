<?php

/**
 * Prints bulk lock status.
 * 
 * @ToDo: Yes, this code repeats the code above. 
 *  
 * @param type $lockerId
 */
function opanda_print_bulk_locking_state( $lockerId ) {

    $options = get_post_meta($lockerId, 'opanda_bulk_locking', true);

    // gets values for the form

    $setupStateClass = empty( $options ) ? 'onp-sl-empty-state' : 'onp-sl-has-options-state';

    $wayStateClass = '';
    if ( !empty($options) && isset( $options['way'] ) ) {
        if ( $options['way'] == 'skip-lock' ) $wayStateClass = 'onp-sl-skip-lock-state';
        elseif ( $options['way'] == 'more-tag' ) $wayStateClass = 'onp-sl-more-tag-state';
        elseif ( $options['way'] == 'css-selector' ) $wayStateClass = 'onp-sl-css-selector-state';
    }

    $skipAndLockStateClass = '';
    if ( !empty($options) && $options['way'] == 'skip-lock' ) {
        if ( $options['skip_number'] == 0 ) $skipAndLockStateClass = 'onp-sl-skip-lock-0-state';
        elseif ( $options['skip_number'] == 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-1-state';
        elseif ( $options['skip_number'] > 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-2-state';     
    } 

    $ruleStateClass = '';

    $defaultWay = 'skip-lock';
    if ( !empty($options) ) $defaultWay = $options['way'];

    $skipNumber = 1;
    if ( !empty($options) && $options['way'] == 'skip-lock' ) {
        $skipNumber = intval( $options['skip_number'] );
    }     

    $cssSelector = '';
    if ( !empty($options) && $options['way'] == 'css-selector' ) {
        $cssSelector = urldecode( $options['css_selector'] );
    }

    $excludePosts = '';
    if ( !empty($options) && !empty( $options['exclude_posts'] ) ) {
        $excludePosts = implode(', ', $options['exclude_posts']);
        $ruleStateClass .= ' onp-sl-exclude-post-ids-rule-state';
    } 

    $excludeCategories = '';
    if ( !empty($options) && !empty( $options['exclude_categories'] ) ) {
        $excludeCategories = implode(', ', $options['exclude_categories']);
        $ruleStateClass .= ' onp-sl-exclude-categories-ids-rule-state';
    } 

    $postTypes = '';
    if ( !empty($options) && !empty( $options['post_types'] ) ) {
        $postTypes = implode(', ', $options['post_types'] );
        $ruleStateClass .= ' onp-sl-post-types-rule-state';
    }  

    ?>

    <div class="factory-bootstrap-329 factory-fontawesome-320">
        
        <div class="onp-sl-setup-section <?php echo $setupStateClass ?>">

            <div class="onp-sl-empty-content">
                <span class="onp-sl-nolock">—</span>
            </div>

            <div class="onp-sl-has-options-content <?php echo $wayStateClass ?> <?php echo $ruleStateClass ?>">

                <div class="onp-sl-way-description onp-sl-skip-lock-content <?php echo $skipAndLockStateClass ?>">
                    <span class="onp-sl-skip-lock-0-content">
                        <?php echo _e('Every post will be locked entirely.', 'bizpanda') ?>
                    </span>
                    <span class="onp-sl-skip-lock-1-content">
                        <?php echo _e('Every post will be locked entirely except the first paragraph.', 'bizpanda') ?>
                    </span>
                    <span class="onp-sl-skip-lock-2-content">
                        <?php echo sprintf( __('Every post will be locked entirely except %s paragraphs placed at the beginning.', 'bizpanda'), $skipNumber ) ?>
                    </span>
                </div>

                <div class="onp-sl-way-description onp-sl-more-tag-content">
                    <?php echo _e('Content placed after the More Tag will be locked in every post.', 'bizpanda') ?>
                </div>

                <div class="onp-sl-way-description onp-sl-css-selector-content">
                    <p><?php echo _e('Every content matching the CSS selector will be locked on every page:', 'bizpanda') ?></p>
                    <strong class="onp-sl-css-selector-view"><?php echo $cssSelector ?></strong>                      
                </div>

                <div class='onp-sl-rules'>
                    <span class='onp-sl-post-types-rule'>
                        <?php printf( __('Applies to types: %s', 'bizpanda'), $postTypes ) ?>
                    </span>
                    <span class='onp-sl-exclude-post-ids-rule'>
                        <?php printf( __('Excludes posts: %s', 'bizpanda'), $excludePosts ) ?>
                    </span>
                    <span class='onp-sl-exclude-categories-ids-rule'>
                        <?php printf( __('Excludes categories: %s', 'bizpanda'), $excludeCategories ) ?>
                    </span>       
                </div>
            </div> 
        </div>

    </div>
    <?php
}



/**
 * Removes the bulk locker options from the cache.
 * 
 * @since 3.0.0
 * @param integer $lockerId
 * @return boolean
 */
function opanda_clear_batch_lock_cache( $lockerId ) {
    
    $bulkLockers = get_option('onp_sl_bulk_lockers', array());
    if ( !is_array($bulkLockers) ) $bulkLockers = array();
    if ( isset( $bulkLockers[$lockerId] ) ) unset( $bulkLockers[$lockerId] );
    
    delete_option('onp_sl_bulk_lockers');
    add_option('onp_sl_bulk_lockers', $bulkLockers);
}

/**
 * Updates the bulk locker options in the cache.
 * 
 * @since 3.0.0
 * @param integer $lockerId
 * @return boolean
 */
function opanda_update_batch_lock_cache( $lockerId ) {
    $data = get_post_meta($lockerId, 'opanda_bulk_locking', true);
    
    $bulkLockers = get_option('onp_sl_bulk_lockers', array());
    if ( !is_array($bulkLockers) ) $bulkLockers = array();
    
    if ( empty( $data ) && isset( $bulkLockers[$lockerId] ) ) {
        unset( $bulkLockers[$lockerId] );
        
        delete_option('onp_sl_bulk_lockers');
        add_option('onp_sl_bulk_lockers', $bulkLockers);
        return;
    }
    
    if ( empty( $data ) ) return;

    $bulkLockers[$lockerId] = $data; 

    delete_option('onp_sl_bulk_lockers');
    add_option('onp_sl_bulk_lockers', $bulkLockers);
}

/**
 * Deletes bulk locking options on a locker deletion.
 * 
 * @since 3.0.0
 * @return boolean
 */
function opanda_clear_bulk_locker_options_on_deletion( $postId ) {
    if ( !current_user_can( 'delete_posts' ) ) return true;

    $post = get_post( $postId );
    if ( empty( $post) ) return true;
    if ( $post->post_type !== OPANDA_POST_TYPE ) return true;
     
    opanda_clear_batch_lock_cache($postId);
    return true;
}
add_action('delete_post', 'opanda_clear_bulk_locker_options_on_deletion');

/**
 * Update global bulk locker options on changing a locker status.
 * 
 * Deletes bulk locking options on a locker deletion on moving to trash.
 * And reset options on retoring from the trash.
 * 
 * @since 3.0.0
 * @return void
 */
function opanda_update_bulk_locker_options_on_changing_status( $new_status, $old_status, $post ) {

    if ( empty( $post) ) return true;
    if ( $post->post_type !== OPANDA_POST_TYPE ) return true;
    
    if ( $new_status == 'trash' ) {
        opanda_clear_batch_lock_cache($post->ID);
    } elseif ( $new_status !== 'trash' ) {
        opanda_update_batch_lock_cache($post->ID);
    }
}
add_action('transition_post_status', 'opanda_update_bulk_locker_options_on_changing_status', 10, 3 );