<?php

/**
 * Returns an URL for purchasing a premium version of the plugin.
 * 
 * @since 1.1.4
 * 
 * @param string $name plugin or item name.
 * @return string|false the URL to purchase
 */
function opanda_get_premium_url( $name = null, $campaign = 'na' ) {
    if ( empty( $name ) ) $name = OPanda_Items::getCurrentItemName ();
    
    $url = null;
    $url = apply_filters('opanda_premium_url', $url, $name, $campaign );
    if ( !empty( $url ) ) return $url;
    
    $url = OPanda_Items::getPremiumUrl( $name );
    if ( !empty( $url ) ) return $url;
    
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
    
    $url = OPanda_Plugins::getPremiumUrl( $name );
    if ( !empty( $url ) ) return $url;
    
    return OPanda_Items::getPremiumUrl( $name );
}

/**
 * Returns HTML offering to go premium. 
 * 
 * @since 1.1.4
 */
function opanda_get_premium_note( $wrap = true, $campaign = 'na' ) {
    
    $url = opanda_get_premium_url( null, $campaign );
    $content = '';
    
    if ( $wrap ) {
        $content .= '<div class="factory-fontawesome-320 opanda-overlay-note opanda-premium-note">';
    }

    $content .= sprintf( __( '<i class="fa fa-star-o"></i> <strong>Go Premium</strong> <i class="fa fa-star-o"></i><br />To Unlock These Features<br /><a href="%s" class="opnada-button" target="_blank">Learn More</a>', 'bizpanda' ), $url );
    
    if ( $wrap ) {
        $content .= '</div>';
    }

    return $content;
}

/**
 * Prints simple visibility options
 * 
 * @since 1.1.7
 */
function opanda_print_simple_visibility_options( $postId ) {
    
    $hideForMember = get_post_meta($postId, 'opanda_hide_for_member', true);
    
    $relock = get_post_meta($postId, 'opanda_relock', true);
    $relockInterval = get_post_meta($postId, 'opanda_relock_interval', true);
    $relockIntervalUnits = get_post_meta($postId, 'opanda_relock_interval_units', true);
    
    $mobile = get_post_meta($postId, 'opanda_mobile', true);
    $always = get_post_meta($postId, 'opanda_always', true);
    
    $empty = !$hideForMember && !$relock && $mobile && !$always;
    ?>

    <ul>
        <?php if ( $hideForMember ) { ?>
        <li><?php _e('Hide for members: <strong>yes</strong>', 'bizpanda') ?></li>
        <?php } ?>
        <?php if ( $relock ) { ?>
        <li><?php printf( __('ReLock: <strong>%s</strong>', 'bizpanda'), ( $relock ? ( $relockInterval . ' ' . $relockIntervalUnits ) : 'no' ) ) ?></li>
        <?php } ?>
        <?php if ( !$mobile ) { ?>
        <li><?php _e('Hide for mobile: <strong>yes</strong>', 'bizpanda') ?></li>
        <?php } ?>
        <?php if ( $always ) { ?>
        <li><?php _e('Appears always: <strong>yes</strong>', 'bizpanda') ?></li>
        <?php } ?>        
        <?php if ( $empty ) { ?>
        <li>—</li>
        <?php } ?>        
    </ul>

    <?php
}

/**
 * Prints visibility conditions
 * 
 * @since 1.1.7
 */
function opanda_print_visibility_conditions( $postId ) {
    $visibilityFilters = get_post_meta( $postId, 'opanda_visibility_filters', true );
    ?>

    <div class="bp-visibility-conditions">
        
    <?php
    if ( empty( $visibilityFilters ) ) { ?>
        -
    <?php } else {
        $filters = json_decode( $visibilityFilters, true );
        
        echo '<ul class="bp-filters">';
        
        foreach($filters as $filter) {
            echo '<li class="bp-filter">';

            $scopes = $filter['conditions'];
            if ( empty($scopes) ) {
                ?>
                —
                <?php
            } else {
                
                $type = $filter['type'];

                if ( 'showif' === $type ) {
                    echo '<div class="bp-filter-type">' . __('Show Locker IF', 'bizpanda') . '</div>';
                } else {
                    echo '<div class="bp-filter-type">' .__('Hide Locker IF', 'bizpanda'). '</div>';
                }

                echo '<ul class="bp-scopes">';

                foreach($scopes as $scope) {
                    echo '<li class="bp-scope">';
                    echo '<div class="bp-and"></div>';

                    echo '<ul class="bp-conditions">';

                    $conditions = $scope['conditions'];
                    foreach($conditions as $condition) {

                        $param = opanda_get_visibility_param_name( $condition['param'] );
                        $operator = $condition['operator'];
                        $value = $condition['value'];
                        $type = $condition['type'];
                        
                        echo '<li class="bp-condition">';
                        echo '<div class="bp-or"></div>';

                        echo opanda_print_visibility_expression($param, $operator, $type, $value );

                        echo '</li>';
                    }

                    echo '</ul>';

                    echo '</li>';
                }
                echo '</ul>';

                echo '</li>';
            }

        }
        
        echo '</ul>';
    }
    ?>
        
    </div>

    <?php
}

function opanda_get_visibility_param_name( $param ) {

    switch ( $param ) {
        case 'user-role': 
            return __('[User Role]', 'bizpanda');
        case 'user-registered': 
            return __('[User Registered]', 'bizpanda');
        case 'user-mobile': 
            return __('[User Mobile]', 'bizpanda');
        case 'session-pageviews': 
            return __('[Total Pageviews]', 'bizpanda');
        case 'session-locker-pageviews': 
            return __('[Locker Pageviews]', 'bizpanda');
        case 'session-landing-page': 
            return __('[Landing Page]', 'bizpanda');
        case 'session-referrer': 
            return __('[Referrer]', 'bizpanda');
        case 'location-page': 
            return __('[Current Page]', 'bizpanda');
        case 'location-referrer': 
            return __('[Current Referrer]', 'bizpanda');
        case 'post-published': 
            return __('[Publication Date]', 'bizpanda');
    }
    
    return $param;
}

function opanda_print_visibility_expression( $param, $operation, $type, $value ) {
    echo '<span class="bp-param">' . $param . '</span> ';
    
    $operatorName = opanda_get_visibility_expression_operator( $operation, $type );
    echo $operatorName . ' ';
    
    if ( is_array( $value ) ) {
        
        if ( 'date' === $type ) {
            
            if ( isset( $value['start'] ) ) {
                
                if ( 'relative' === $value['start']['type'] ) {
                    echo sprintf( __('older than <strong>%s %s</strong> but younger than <strong>%s %s</strong>', 'bizpanda'), $value['start']['unitsCount'], $value['start']['units'], $value['end']['unitsCount'], $value['end']['units'] );
                } else {
                    echo sprintf( __('<strong>%s</strong> and <strong>%s</strong>', 'bizpanda'), date( 'd.m.Y', $value['start'] / 1000 ), date( 'd.m.Y', $value['end'] / 1000 ) );
                }
                
            } else {
                
                if ( 'relative' === $value['type'] ) {
                    echo sprintf( __('<strong>%s %s</strong>', 'bizpanda'), $value['unitsCount'], $value['units'] );
                } else {
                    echo sprintf( __('<strong>%s</strong>', 'bizpanda'), date( 'd.m.Y', $value / 1000 ) );
                }     
                
            }
          
        } else {
            echo sprintf( __('<strong>%s</strong> and <strong>%s</strong>', 'bizpanda'), $value['start'], $value['end'] );
        }

    } else {
        if ( 'date' === $type ) { 
           echo sprintf( __('<strong>%s</strong>', 'bizpanda'), date( 'd.m.Y', $value / 1000 ) );
        } else {
            echo '<strong>' . $value . '</strong>' ;  
        }
    }
}

function opanda_get_visibility_expression_operator( $operation, $type ) {
    
    switch( $operation ) {
        case 'equals': 
            return __('equals', 'bizpanda');
        case 'notequal': 
            return __('does not equal', 'bizpanda'); 
        case 'greater': 
            return __('greater than', 'bizpanda');
        case 'less': 
            return __('less than', 'bizpanda');
        case 'older': 
            return __('older than', 'bizpanda');
        case 'younger': 
            return __('younger than', 'bizpanda');
        case 'contains': 
            return __('contains', 'bizpanda');
        case 'notcontain': 
            return __('does not contain', 'bizpanda');
        case 'between': 
            
            if ( $type === 'date') {
                return '';
            } else {
                return __('between', 'bizpanda');
            }
  
        default:
            return $operation;
    }
}