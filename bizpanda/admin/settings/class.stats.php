<?php
/**
 * A class for the page providing the basic settings.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The page Basic Settings.
 * 
 * @since 1.0.0
 */
class OPanda_StatsSettings extends OPanda_Settings  {
 
    public $id = 'stats';
    
    /**
     * Sets notices.
     * 
     * @since 1.0.0
     * @return void
     */
    public function init() {
        
        if ( isset( $_GET['onp_table_cleared'] )) {
            $this->success = __('The data has been successfully cleared.', 'bizpanda');
        }
    }
    
    /**
     * Shows the header html of the settings screen.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {
        ?>
        <p><?php _e('Configure here how the plugin should collect the statistical data.', 'optionpanda') ?></p>
        <?php
    }
    
    /**
     * Returns options for the Basic Settings screen. 
     * 
     * @since 1.0.0
     * @return void
     */
    public function getOptions() {
        global $optinpanda;

        $options = array();
        
        $options[] = array(
            'type' => 'separator'
        );

        $options[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'google_analytics',
            'title'     => __( 'Google Analytics', 'bizpanda' ),
            'hint'      => __( 'If set On, the plugin will generate <a href="https://support.google.com/analytics/answer/1033068?hl=en" target="_blank">events</a> for the Google Analytics when the content is unlocked.<br /><strong>Note:</strong> before enabling this feature, please <a href="https://support.google.com/analytics/answer/1008015?hl=en" target="_blank">make sure</a> that your website contains the Google Analytics tracker code.', 'bizpanda' )
        );
        
        $options[] = array(
            'type'      => 'html',
            'html'      => array($this, 'statsHtml')
        );
        
        $options[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',  
            'name'      => 'tracking',
            'title'     => __( 'Collecting Stats', 'bizpanda' ),
            'hint'      => __( 'Turns on collecting the statistical data for reports.', 'bizpanda' )
        );

        $options[] = array(
            'type' => 'separator'
        );
        
        return $options;
    }

    /**
     * Render the html block on how much the statistics data takes places.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function statsHtml() {
        global $wpdb;
        
        $dataSizeInBytes = $wpdb->get_var(
            "SELECT round(data_length + index_length) as 'size_in_bytes' FROM information_schema.TABLES WHERE " . 
            "table_schema = '" . DB_NAME . "' AND table_name = '{$wpdb->prefix}opanda_stats_v2'");
        
        $count = $wpdb->get_var("SELECT COUNT(*) AS n FROM {$wpdb->prefix}opanda_stats_v2");
        $humanDataSize = factory_325_get_human_filesize( $dataSizeInBytes );
        ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="control-group controls col-sm-10">
                    <p class="onp-sl-inline">
                        <?php if ( $count == 0 ) { ?>
                        <?php printf( __( 'The statistical data is <strong>empty</strong>.', 'bizpanda' ), $humanDataSize ); ?>
                        <?php } else { ?>
                        <?php printf( __( 'The statistical data takes <strong>%s</strong> on your server', 'bizpanda' ), $humanDataSize ); ?>
                        <a class="button" style="margin-left: 5px;" href="<?php $this->actionUrl('clearStatsData') ?>"><?php _e('clear data', 'bizpanda') ?></a>
                        <?php } ?>
                    </p>
                </div>
            </div>
        <?php
    }
    
    /**
     * Clears the statisticals data.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function clearStatsDataAction() {
        
        if ( !isset( $_REQUEST['onp_confirmed'] ) ) {
            return $this->confirm(array(
                'title' => __('Are you sure that you want to clear the current statistical data?', 'bizpanda'),
                'description' => __('All the statistical data will be removed.', 'bizpanda'),
                'actions' => array(
                    'onp_confirm' => array(
                        'class' => 'btn btn-danger',
                        'title' => __("Yes, I'm sure", 'bizpanda'),
                        'url' => $this->getActionUrl('clearStatsData', array(
                            'onp_confirmed' => true
                        ))
                    ),
                    'onp_cancel' => array(
                        'class' => 'btn btn-default',
                        'title' => __("No, return back", 'bizpanda'),
                        'url' => $this->getActionUrl('index')
                    ),
                )
            ));
        }
        
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}opanda_stats_v2");
        
        $lockers = get_posts(array(
            'post_type' => OPANDA_POST_TYPE
        ));
        
        foreach( $lockers as $locker ) {
            delete_post_meta($locker->ID, 'opanda_imperessions');
            delete_post_meta($locker->ID, 'opanda_unlocks');   
        }
        
        return $this->redirectToAction('index', array('onp_table_cleared' => true));
    }
    
    /**
     * Shows the html block with a confirmation dialog.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function confirm( $data ) {
        ?>
        <div class="onp-page-wrap factory-bootstrap-329" id="onp-confirm-dialog">
            <div id="onp-confirm-dialog-wrap">
                <h1><?php echo $data['title'] ?></h1>
                <p><?php echo $data['description'] ?></p>
                <div class='onp-actions'>
                    <?php foreach( $data['actions'] as $action ) { ?>
                        <a href='<?php echo $action['url'] ?>' class='<?php echo $action['class'] ?>'>
                           <?php echo $action['title'] ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

