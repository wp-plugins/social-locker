<?php
    
    // ------------------------------------------------------------------------------------------
    // Upgrade To Premium
    // ------------------------------------------------------------------------------------------
    
    /**
     * Shows the trial and premium notices.
     * 
     * @see factory_notices
     * @since 1.0.0
     */
    function onp_sl_upgrade_to_premium_notices( $notices ) {
        global $sociallocker;
        $forceToShowNotices = defined('ONP_DEBUG_SL_OFFER_PREMIUM') && ONP_DEBUG_SL_OFFER_PREMIUM;

        if ( ( !$sociallocker->license || $sociallocker->license->build !== 'free' || $sociallocker->build !== "free" ) && !$forceToShowNotices ) return $notices;

        $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);

        if ( $alreadyActivated ) {
            $message = __('5 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more.', 'optinpanda');
            $header = __('Drive more traffic and build quality followers with Social Locker Premium!', 'optinpanda');
            $url = onp_licensing_325_get_purchase_url( $sociallocker );

        } else {
            $message = __('5 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more. Drive more traffic and build quality followers with Social Locker Premium!', 'optinpanda');
            $header = __('Try the premium version for 7 days for free!', 'optinpanda');
            $url = onp_licensing_325_manager_link($sociallocker->pluginName, 'activateTrial', false);
        }

        $closed = get_option('factory_notices_closed', array());

        $lastCloase  = isset( $closed['onp-sl-offer-to-purchase'] ) 
            ? $closed['onp-sl-offer-to-purchase']['time'] 
            : 0;

        // shows every 7 days
        if ( ( time() - $lastCloase > 60*60*7 ) || $forceToShowNotices ) {

                if ( !$alreadyActivated ) {

                    $notices[] = array(
                        'id'        => 'onp-sl-offer-to-purchase',

                        'class'     => 'call-to-action ',
                        'icon'      => 'fa fa-arrow-circle-o-up',
                        'header'    => '<span class="onp-hightlight">' . $header . '</span>',
                        'message'   => $message,   
                        'plugin'    => $sociallocker->pluginName,
                        'where'     => array('plugins','dashboard', 'edit'),

                        // buttons and links
                        'buttons'   => array(
                            array(
                                'title'     => '<i class="fa fa-arrow-circle-o-up"></i> ' . __('Activate Premium', 'optinpanda'),
                                'class'     => 'button button-primary',
                                'action'    => $url
                            ),
                            array(
                                'title'     => __('No, thanks, not now', 'optinpanda'),
                                'class'     => 'button',
                                'action'    => 'x'
                            )
                        )
                    ); 

                } else {

                    $notices[] = array(
                        'id'        => 'onp-sl-offer-to-purchase',

                        'class'     => 'call-to-action ',
                        'icon'      => 'fa fa-arrow-circle-o-up',
                        'header'    => '<span class="onp-hightlight">' . $header . '</span>',
                        'message'   => $message,   
                        'plugin'    => $sociallocker->pluginName,
                        'where'     => array('plugins','dashboard', 'edit'),

                        // buttons and links
                        'buttons'   => array(
                            array(
                                'title'     => '<i class="fa fa-arrow-circle-o-up"></i> ' . __('Learn More & Upgrade', 'optinpanda'),
                                'class'     => 'button button-primary',
                                'action'    => $url
                            ),
                            array(
                                'title'     => __('No, thanks, not now', 'optinpanda'),
                                'class'     => 'button',
                                'action'    => 'x'
                            )
                        )
                    ); 
                }

            

        }

        return $notices;
    }

    add_filter('factory_notices_' . $sociallocker->pluginName, 'onp_sl_upgrade_to_premium_notices', 10, 2);

    // ------------------------------------------------------------------------------------------
    // Achievement Popups
    // ------------------------------------------------------------------------------------------
    
    /**
     * Shows the popups offering to rate the plugin.
     * 
     * @see factory_notices
     * @since 1.0.0
     */
    function onp_sl_achievement_popups( $notices ){
        global $sociallocker;

        $popup = new OnpSL_RateUs_Popup( $sociallocker );
        if ( !$popup->isVisible() ) return $notices;

        $notices[] = $popup->getData();    
        return $notices;
    }
    add_filter('factory_notices_' . $sociallocker->pluginName, 'onp_sl_achievement_popups', 10, 2);

    /**
     * A popup which controls of showing the offer to rate the plugin.
     * 
     * @see factory_notices
     * @since 1.0.0
     */
    class OnpSL_RateUs_Popup {

        public $min = 25;
        public $step = 25;

        public function __construct( $plugin ) {
            $this->plugin = $plugin;
            add_action('admin_enqueue_scripts', array( $this, 'assets') );
        }

        /**
         * Returns an ID of a popup.
         */
        public function getId() {

            $action = $this->getAchievementAction();
            if ( empty( $action ) ) return false;

            return 'onp-sl-' . $action;
        }

        /**
         * Returns a current achievement action.
         */
        public function getAchievementAction() {

            if ( defined('ONP_SL_ACHIEVEMENT_ACTION') && ONP_SL_ACHIEVEMENT_ACTION ) {
                return ONP_SL_ACHIEVEMENT_ACTION;
            }

            $level = $this->getLevel();
            if ( empty( $level ) ) return false;

            $value = $level['value'];
            if ( $value < $this->min ) return false;

            $actions = get_option('onp_sl_achievement_popups', array());

            $action = false;

            if ( !isset( $actions['review'] ) ) $action = 'review';
            elseif ( !isset( $actions['subscription'] ) ) {
                if ( $value >= $actions['review']['value'] + $this->step ) $action = 'subscribe';
            }
            elseif ( !isset( $actions['premium'] ) ) {
                if ( $value >= $actions['subscription']['value'] + $this->step ) $action = 'premium';
            }

            if ( $action && isset( $_COOKIE['onp_sl_' . $action . '_closed'] ) ) return false;
            return $action;
        }

        /**
         * A cache var for the method getLevel.
         */
        protected $_level = false;

        /**
         * Gets current level reached.
         */
        public function getLevel() {

            if ( defined('ONP_SL_ACHIEVEMENT_VALUE') && false !== ONP_SL_ACHIEVEMENT_VALUE ) {
                return array('metric' => 'unlock-via-facebook-like', 'value' => ONP_SL_ACHIEVEMENT_VALUE);
            }

            if ( $this->_level !== false ) return $this->_level; 

            $counts = $this->getUnlocksCountByButton();
            if ( 'inf' == $counts ) return false;

            $result = array('metric' => null, 'value' => 0);

            foreach ( $counts as $name => $count ) {
                if ( $count < $this->min ) continue;
                if ( $count > $result['value'] ) $result = array('metric' => $name, 'value' => $count);
            }

            if ( $result['metric'] == null) { $this->_level = null; }
            else { $this->_level = $result; }

            return $this->_level;
        }

        /**
         * Returns true if the popup has to be shown now.
         */
        public function isVisible() {

            $action = $this->getAchievementAction();
            if ( 'review' == $action ) return true;
            else return false;
        }

        /**
         * A cache var for the method getUnlocksCountByButton.
         */
        protected $_unlocksCount = false;

        /**
         * Returns the count of events required to access the count of received likes, tweets, emails etc.
         */
        protected function getUnlocksCountByButton() {
            if ( $this->_unlocksCount !== false ) return $this->_unlocksCount;

            $cache = get_site_transient( 'onp_sl_unlocks_count' );
            if ( $cache ) {
                $this->_unlocksCount = $cache;
                return $this->_unlocksCount;
            }

            global $wpdb;

            $metrics = array(
                'unlock-via-facebook-like',
                'unlock-via-facebook-share',
                'unlock-via-twitter-tweet',
                'unlock-via-twitter-follow',
                'unlock-via-linkedin-share',
                'unlock-via-google-plus',
                'unlock-via-google-share',
                'email-received'
            );

            $value = intval( $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "opanda_stats_v2 WHERE metric_name='unlock'") );
            if ( $value > 10000 ) {
                $this->_unlocksCount = 'inf';
                set_site_transient( 'onp_sl_unlocks_count', $this->_unlocksCount, 60*60*12 );
                return $this->_unlocksCount;
            }

            $inClause = array();
            foreach( $metrics as $metric ) $inClause[] = "'$metric'";
            $inClause = implode(',', $inClause);

            $sql = "SELECT SUM(metric_value) as total_count, metric_name " .
                    "FROM " . $wpdb->prefix . "opanda_stats_v2 " .
                    "WHERE metric_name IN ($inClause) GROUP BY metric_name";

            $counts = $wpdb->get_results( $sql, ARRAY_A );

            $result = array();
            foreach( $counts as $row ) {
                $result[$row['metric_name']] = $row['total_count'];
            }

            foreach( $metrics as $metric ) {
                if ( !isset( $result[$metric] ) ) $result[$metric] = 0;
            }

            $this->_unlocksCount = $result;
            set_site_transient( 'onp_sl_unlocks_count', $this->_unlocksCount, 60*60*12 );

            return $this->_unlocksCount;
        }

        /**
         * Adds assets for the popup.
         */
        public function assets( $hook ) {

            // sytles for the plugin notices
            if ( $hook == 'index.php' || $hook == 'plugins.php' || $hook == 'edit.php' ) {
                wp_enqueue_style( 'sociallocker-notices', SOCIALLOCKER_URL . '/plugin/admin/assets/css/notices.010000.css' ); 
                wp_enqueue_script( 'sociallocker-notices', SOCIALLOCKER_URL . '/plugin/admin/assets/js/notices.010000.js' );    
            }
        }

        /**
         * Returns HTML message for the popup. 
         */
        public function getMessage() {
            $level = $this->getLevel();
            $value = floor ( $level['value'] / 5 ) * 5;

            $action = $this->getAchievementAction();

            switch( $level['metric'] ) {
                case 'unlock-via-facebook-like':
                    $units = __( 'likes', 'sociallocker' );
                    $where = 'on Facebook';
                    break;
                case 'unlock-via-facebook-share':
                    $units = __( 'shares', 'sociallocker' );
                    $where = 'on Facebook';
                    break;
                case 'unlock-via-twitter-tweets':
                    $units = __( 'tweets', 'sociallocker' );
                    $where = 'on Twitter';
                    break;
                case 'unlock-via-twitter-followers':
                    $units = __( 'followers', 'sociallocker' );
                    $where = 'on Twitter';
                    break;
                case 'unlock-via-linkedin-share':
                    $units = __( 'shares', 'sociallocker' );
                    $where = 'on Linkedin';
                    break;
                case 'unlock-via-google-plus':
                    $units = __( 'pluses', 'sociallocker' );
                    $where = 'on Google';
                    break;
                case 'email-received':
                    $units = __( 'emails', 'sociallocker' );
                    $where = 'collected';
                    break;
            }

            if ( 'email-received' == $level['metric'] ) {
                $description = sprintf( __('Congrats! You collected more %s emails via <strong>Social Locker</strong>.', 'sociallocker'), $value, $units );
            } else {
                $description = sprintf( __('Congrats! You gained more %s %s via <strong>Social Locker</strong>.', 'sociallocker'), $value, $units );
            }

            $premiumUrl = onp_sl_get_premium_url('achievements');
            ob_start();
            ?>
            <div>
                <div class="onp-sl-achievement onp-sl-<?php echo $level['metric'] ?>">
                    <span class="onp-sl-count"><?php _e('+', 'sociallocker') ?><?php echo $value ?></span>
                    <span class="onp-sl-count-explanation">
                        <span class="onp-sl-units"><?php echo $units ?></span><br/>
                        <span class="onp-sl-where"><?php echo $where ?></span>  
                    </span>
                    <span class="onp-sl-exclamation"><?php _e('!', 'sociallocker') ?></span>  
                </div>

                <?php if ( 'review' == $action ) { ?>

                <div class="onp-sl-text">
                    <p><?php echo $description ?></p>
                    <p><?php _e('Please do us a BIG favor, give the plugin a 5-star rating on wordpress.org.', 'sociallocker') ?></p>  
                </div>

                <div class="onp-sl-buttons">
                    <a href='https://wordpress.org/support/view/plugin-reviews/social-locker?filter=5' target="_blank" class='onp-sl-button onp-sl-button-primary' data-achievement-value="<?php echo $level['value'] ?>">
                        <i class='fa fa-star'></i><?php _e('Sure, you deserved it!', 'sociallocker') ?>
                    </a><br />
                    <a href='#' class='onp-sl-button-link' data-achievement-value="<?php echo $level['value'] ?>">
                        <?php _e('I already did', 'sociallocker') ?>
                    </a>   
                    <a href='#' class='onp-sl-button-link' data-achievement-value="<?php echo $level['value'] ?>">
                        <?php _e('No, not good enough', 'sociallocker') ?>
                    </a>         
                </div>

                <?php } ?>

                <div class='onp-sl-status-bar'>
                    <?php printf( __('Want more %s? Try the <a href="%s" target="_blank">premium version</a>.'), $units, $premiumUrl ) ?>
                </div>
            </div>
            <?php        
            $message = ob_get_contents(); ob_end_clean();
            return $message;
        }

        /**
         * Returns data for the popup.
         */
        public function getData() {

            $data = array(
                'id'        => $this->getId(),
                'class'     => 'onp-sl-rateus-popup factory-fontawesome-320',
                'position'  => 'popup',
                'layout'    => 'custom',
                'close'     => 'quick-hide',
                'message'   => $this->getMessage(),
                'where'     => array('plugins','dashboard', 'edit')
            ); 

            return $data;
        }
    }

    /**
     * Handles an ajax request to hide a specified achievement popup.
     */
    function onp_sl_hide_achievement() {

        $type = isset( $_REQUEST['achievementType'] ) ? $_REQUEST['achievementType'] : null;
        $value = isset( $_REQUEST['achievementValue'] ) ? intval( $_REQUEST['achievementValue'] ) : null;  

        if ( empty( $type) || empty( $value ) ) {
            echo json_encode(array('error' => __('Invalid request type.', 'sociallocker')));
            exit;
        }

        $achievementPopups = get_option('onp_sl_achievement_popups', array());
        if ( isset( $achievementPopups[$type] )) return false;

        $achievementPopups[$type] = array();
        $achievementPopups[$type]['value'] = $value;
        $achievementPopups[$type]['time'] = time();

        delete_option('onp_sl_achievement_popups');
        add_option('onp_sl_achievement_popups', $achievementPopups);

        exit;
    }
    add_action('wp_ajax_onp_sl_hide_achievement', 'onp_sl_hide_achievement' );



// ------------------------------------------------------------------------------------------
// StyleRoller
// ------------------------------------------------------------------------------------------