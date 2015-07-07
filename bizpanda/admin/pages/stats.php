<?php
/**
 * The file contains a page that shows statistics
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OPanda_StatisticsPage extends OPanda_AdminPage  {
 
    public function __construct( $plugin ) {
        $this->id = 'stats';
        $this->menuPostType = OPANDA_POST_TYPE;
        $this->menuTitle = __('Stats & Reports', 'bizpanda');
        
        parent::__construct( $plugin );
    }
        
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        
        $this->styles->request( array( 
            'bootstrap.core'
            ), 'bootstrap' ); 
        
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/libs/datepicker.js');  
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/libs/datepicker.css');      
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/stats.010000.js');
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/stats.010000.css');   
    }
    
    /**
     * Shows an index page where a user can set settings.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {

        // gettings all the items for the item selector
        
        $dropdownItems = get_posts(array(
            'post_type' => OPANDA_POST_TYPE,
            'meta_key' => 'opanda_item',
            'meta_value' => OPanda_Items::getAvailableNames(),
            'numberposts' => -1
        ));

        // current item
        
        $itemId = isset( $_GET['opanda_id'] ) ? $_GET['opanda_id'] : null;
        if ( empty( $itemId ) ) {
            $itemId = isset( $dropdownItems[0]->ID ) ? $dropdownItems[0]->ID : 0;
        }
        
        $itemName = OPanda_Items::getItemNameById($itemId);
        
        $showPopup = ( count( $dropdownItems ) > 1 && !isset( $_GET['opanda_id'] ) );
                
        $screens = apply_filters("opanda_item_type_stats_screens", array(), $itemName);
        $screens = apply_filters("opanda_{$itemName}_stats_screens", $screens);
        
        $item = get_post( $itemId );
        if ( empty( $item) ) die( __('The item with ID = ' . $itemId . ' is not found.' , 'bizpanda' ) );
        
        $itemTitle = empty( $item->post_title ) 
            ? sprintf( __('(no titled, id=%s)', 'bizpanda'), $item->ID ) 
            : $item->post_title;
        
        // current item screen
        
        $currentScreenName = isset($_REQUEST['opanda_screen']) ? $_REQUEST['opanda_screen'] : 'summary'; 
        $currentScreen = $screens[$currentScreenName];

        require_once(OPANDA_BIZPANDA_DIR . '/admin/includes/classes/class.stats-screen.php');
        
        require_once $currentScreen['path'];
        
        $screenClass = isset( $currentScreen['screenClsss'] ) ? $currentScreen['screenClsss'] : 'OPanda_StatsScreen';
        $screen = new $screenClass(array(
            'chartClass' => $currentScreen['chartClass'],
            'tableClass' => $currentScreen['tableClass']
        ));
        
        // current post

        $postId = isset($_REQUEST['opanda_post_id']) ? intval($_REQUEST['opanda_post_id']) : false;
        $post = ($postId) ? get_post($postId) : false;

        // set date range
        
        $dateStart = isset($_REQUEST['opanda_date_start']) ? $_REQUEST['opanda_date_start'] : false;  
        $dateEnd = isset($_REQUEST['opanda_date_end']) ? $_REQUEST['opanda_date_end'] : false; 

        $hrsOffset = get_option('gmt_offset');
        if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
        $hrsOffset .= ' hours';

        // by default shows a 30 days' range
        if (empty($dateEnd) || ($dateRangeEnd = strtotime($dateEnd)) === false) {
            $phpdate = getdate( strtotime($hrsOffset, time()) );
            $dateRangeEnd = mktime(0, 0, 0, $phpdate['mon'], $phpdate['mday'], $phpdate['year']);
        }

        if (empty($dateStart) || ($dateRangeStart = strtotime($dateStart)) === false) {
            $dateRangeStart = strtotime("-1 month", $dateRangeEnd);
        }

        // getting the chart data
        
        $chart = $screen->getChart(array(
            'itemId' => $itemId,
            'postId' => $postId,
            'rangeStart' => $dateRangeStart,
            'rangeEnd' => $dateRangeEnd,   
        ));

        // getting the table data

        $page = ( isset( $_GET['opanda_page'] ) ) ? intval( $_GET['opanda_page'] ) : 1;
        if ( $page <= 0 ) $page = 1;

        $table = $screen->getTable(array(
            'itemId' => $itemId,
            'postId' => $postId,
            'rangeStart' => $dateRangeStart,
            'rangeEnd' => $dateRangeEnd,  
            'per' => 50,
            'total' => true,
            'page' => $page
        ));
       
        // the base urls

        $urlBase = add_query_arg( array(
            'opanda_id' => $itemId,
            'opanda_post_id' => $postId,
            'opanda_screen' => $currentScreenName,
            'opanda_date_start' => date('m/d/Y', $dateRangeStart),
            'opanda_date_end' => date('m/d/Y', $dateRangeEnd),
        ), opanda_get_admin_url('stats') );

        $dateStart = date('m/d/Y', $dateRangeStart);
        $dateEnd = date('m/d/Y', $dateRangeEnd);
        
        // extra css classes

        $tableCssClass = '';
        if ( $table->getColumnsCount() > 8 ) $tableCssClass .= ' opanda-concise-table';
        else $tableCssClass .= ' opanda-free-table';
        
        ?>
        <div class="wrap">

            <h2><?php _e('Stats & Reports', 'bizpanda') ?></h2>

            <div id="opanda-control-panel">
                <div class="opanda-left" id="opanda-current-item">
                    <span><?php _e('You are viewing reports for ', 'bizpanda') ?> <a href="<?php echo admin_url("post.php?post=" . $itemId . "&action=edit") ?>"><strong><?php echo $itemTitle ?></strong></a></span>
                </div>
                
                <form method="get" id="opanda-item-selector" class="opanda-right">
                    <input type="hidden" name="post_type" value="<?php echo OPANDA_POST_TYPE ?>" />
                    <input type="hidden" name="page" value="stats-bizpanda" />
                    <input type="hidden" name="opanda_date_start" class="form-control" value="<?php echo $dateStart ?>" />
                    <input type="hidden" name="opanda_date_end" class="form-control" value="<?php echo $dateEnd ?>" />

                    <span><?php _e('Select item to view:', 'optionpanda') ?></span>
                    <select name="opanda_id">
                        <?php foreach( $dropdownItems as $dropdownItem ) { ?>
                        <option value="<?php echo $dropdownItem->ID ?>" <?php if ( $dropdownItem->ID == $itemId ) { echo 'selected="selected"'; } ?>>
                            <?php if ( empty($dropdownItem->post_title) ) { ?>
                                <?php printf( __('(no titled, id=%s)', 'bizpanda'), $dropdownItem->ID ) ?>
                            <?php } else { ?>
                                <?php echo $dropdownItem->post_title ?>
                            <?php } ?>
                        </option>
                        <?php } ?>
                    </select>
                    <input class="button" type="submit" value="<?php _e('Select', 'bizpanda') ?>" />
                </form>
                
            </div>

            <div class="factory-bootstrap-329 factory-fontawesome-320">

            <div class="onp-chart-hints">
                <div class="onp-chart-hint onp-chart-hint-errors">
                    <?php printf( __('This chart shows the count of times when the locker was not available to use due to the visitor installed the extensions like Avast or Adblock which may block social networks.<br />By default, the such visitors see the locker without social buttons but with the offer to disable the extensions. You can set another behaviour <a href="%s"><strong>here</strong></a>.', 'bizpanda'), admin_url('admin.php?page=common-settings-' . $this->plugin->pluginName . '&action=advanced') ) ?>
                </div>
            </div>
            
            <div id="opanda-chart-description">
                <?php echo $currentScreen['description'] ?>
            </div>
            
            <div id="onp-sl-chart-area">
                <form method="get"> 
                <div id="onp-sl-settings-bar">
                    
                    <div id="onp-sl-type-select">
                       <div class="btn-group" id="chart-type-group" data-toggle="buttons-radio">
                          <?php foreach ( $screens as $screenName => $screen ) { ?>
                           <a href="<?php echo add_query_arg( 'opanda_screen', $screenName, $urlBase ) ?>" class="btn btn-default <?php if ( $screenName == $currentScreenName ) { echo 'active'; } ?> type-<?php echo $screenName ?>" data-value="<?php echo $screenName ?>"><?php echo $screen['title'] ?></a>
                          <?php } ?>
                       </div>
                    </div>
                    <div id="onp-sl-date-select">
                        
                        <input type="hidden" name="post_type" value="<?php echo OPANDA_POST_TYPE ?>" />
                        <input type="hidden" name="page" value="stats-bizpanda" />      
                        <input type="hidden" name="opanda_post_id" value="<?php echo $postId ?>" />
                        <input type="hidden" name="opanda_screen" value="<?php echo $currentScreenName ?>" />
                        <input type="hidden" name="opanda_id" value="<?php echo $itemId ?>" />
                        
                        <span class="onp-sl-range-label"><?php _e('Date range', 'bizpanda') ?>:</span>
                        <input type="text" id="onp-sl-date-start" name="opanda_date_start" class="form-control" value="<?php echo $dateStart ?>" />
                        <input type="text" id="onp-sl-date-end" name="opanda_date_end" class="form-control" value="<?php echo $dateEnd ?>" />
                        
                        <a id="onp-sl-apply-dates" class="btn btn-default">
                            <?php _e('Apply', 'bizpanda') ?>
                        </a>
                    </div>
                </div>
                </form>

                <div class="chart-wrap">
                    <div id="chart" style="width: 100%; height: 195px;"></div>
                </div>
                
            </div>

            <div id="onp-sl-chart-selector">
                <?php if ( $chart->hasSelectors() ) { ?>
                <?php foreach( $chart->getSelectors() as $name => $field ) { ?>
                <div class="onp-sl-selector-item onp-sl-selector-<?php echo $name ?>" data-selector="<?php echo $name ?>">
                    <span class="chart-color" style="background-color: <?php echo $field['color'] ?>"></span>
                    <?php echo $field['title'] ?>
                </div>
                <?php } ?>
                <?php } ?>
            </div>

            <?php if ($postId) { ?>
                <div class="alert alert-warning">
                <?php echo sprintf(__('Data for the post: <strong>%s</strong> (<a href="%s">return back</a>)', 'bizpanda'),$post->post_title, add_query_arg( 'opanda_post_id', false, $urlBase ) ); ?>
                </div>
            <?php } else { ?>
                <p><?php _e('Top-50 posts and pages where you put the locker, ordered by their performance:', 'bizpanda') ?></p>
            <?php } ?>

            <div id="opanda-data-table-wrap">
            <table id="opanda-data-table" class="<?php echo $tableCssClass ?>">
                <thead>
                    <?php if ( $table->hasComplexColumns() ) { ?>
                    
                    <tr>
                        <?php foreach( $table->getHeaderColumns() as $name => $column ) { ?>
                            <th rowspan="<?php echo $column['rowspan'] ?>" colspan="<?php echo $column['colspan'] ?>" class="opanda-col-<?php echo $name ?> <?php echo isset( $column['cssClass'] ) ? $column['cssClass'] : '' ?> <?php if ( isset( $column['highlight']) ) { echo 'opanda-column-highlight'; } ?>">
                                <?php echo $column['title'] ?>
                                <?php if ( isset( $column['hint'] ) ) { ?>
                                <i class="opanda-hint" title="<?php echo $column['hint']; ?>"></i>
                                <?php } ?>
                            </th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach( $table->getHeaderColumns(2) as $name => $column ) { ?>
                            <th class="opanda-col-<?php echo $name ?> <?php echo isset( $column['cssClass'] ) ? $column['cssClass'] : '' ?> <?php if ( isset( $column['highlight']) ) { echo 'opanda-column-highlight'; } ?>">
                                <?php echo $column['title'] ?>
                                <?php if ( isset( $column['hint'] ) ) { ?>
                                <i class="opanda-hint" title="<?php echo $column['hint']; ?>"></i>
                                <?php } ?>
                            </th>
                        <?php } ?>
                    </tr>
                    
                    <?php } else { ?>
                    
                        <?php foreach( $table->getColumns() as $name => $column ) { ?>
                        <th class="opanda-column-<?php echo $name ?> <?php echo isset( $column['cssClass'] ) ? $column['cssClass'] : '' ?> <?php if ( isset( $column['highlight']) ) { echo 'opanda-column-highlight'; } ?>">
                            <?php echo $column['title'] ?>
                            <?php if ( isset( $column['hint'] ) ) { ?>
                            <i class="opanda-hint" title="<?php echo $column['hint']; ?>"></i>
                            <?php } ?>
                        </th>
                        <?php } ?>
                        
                    <?php } ?>
                </thead>
                <tbody>
                <?php for( $i = 0; $i < $table->getRowsCount(); $i++ ) { if ( $i >= 50 ) break; ?>
                <tr>
                    <?php foreach( $table->getDataColumns() as $name => $column ) { ?>
                        <td class="opanda-col-<?php echo $name ?> <?php echo isset( $column['cssClass'] ) ? $column['cssClass'] : '' ?> <?php if ( isset( $column['highlight']) ) { echo 'opanda-column-highlight'; } ?>">
                            <?php $table->printValue( $i, $name, $column ) ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php } ?>
                </tbody>

            </table>

            </div>

            </div>
        </div>

        <!-- Load the AJAX API -->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

          // Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(function(){
              window.bizpanda.statistics.drawChart({
                  'type': '<?php echo $chart->type ?>'
              });
          });
          
          window.opanda_default_selectors = [<?php echo join(',', $chart->getSelectorsNames()) ?>];

          window.chartData = [
            <?php $chart->printData() ?>
          ];
        </script>
        
        <?php if ( $showPopup ) { ?>
        
            <!-- Locker Select Popup -->

            <div id="opanda-locker-select-overlap" style="display: none;"></div>      
            <div id="opanda-locker-select-popup" style="display: none;">
                <strong><?php _e('Select Locker', 'bizpanda') ?></strong>
                <p><?php _e('Please select a locker to view reports.', 'bizpanda') ?></p>

                <select id="opanda-locker-select">
                    <?php foreach( $dropdownItems as $dropdownItem ) { ?>
                    <option value="<?php echo opanda_get_admin_url('stats', array('opanda_id' => $dropdownItem->ID)); ?>" <?php if ( $dropdownItem->ID == $itemId ) { echo 'selected="selected" data-default="true"'; } ?>>
                        <?php if ( empty($dropdownItem->post_title) ) { ?>
                            <?php printf( __('(no titled, id=%s)', 'bizpanda'), $dropdownItem->ID ) ?>
                        <?php } else { ?>
                            <?php echo $dropdownItem->post_title ?>
                        <?php } ?>
                    </option>
                    <?php } ?>
                </select>
                <input class="button" type="submit" value="<?php _e('Select', 'bizpanda') ?>" id="opanda-locker-select-submit" />
            </div>

        <?php } ?>
    <?php 
    }
}

FactoryPages321::register($bizpanda, 'OPanda_StatisticsPage');