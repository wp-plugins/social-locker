<?php

class OPanda_LeadsListTable extends WP_List_Table
{
    public function __construct( $options = array() ) {
        
        $options['singular'] = __( 'Lead', 'bizpanda' );
        $options['plural'] = __( 'Leads', 'bizpanda' );
        $options['ajax'] = false;
        
        parent::__construct( $options );
        $this->bulk_delete();
    }
    
    public function get_views() {
        
        $counts = OPanda_Leads::getCountByStatus();
        $link = 'edit.php?post_type=' . OPANDA_POST_TYPE . '&page=leads-bizpanda';

        $currentStatus = isset( $_GET['opanda_status'] ) ? $_GET['opanda_status'] : 'all';
        if ( !in_array( $currentStatus, array('all', 'confirmed', 'not-confirmed') ) ) $currentStatus = 'all';
        
        $items = array(
            'view-all' => array(
                'title' => __('All', 'bizpanda'),
                'link' => $link,
                'count' => array_sum($counts),
                'current' => $currentStatus == 'all'
            ),
            'view-confirmed' => array(
                'title' => __('Confirmed', 'bizpanda'),
                'link' => add_query_arg( 'opanda_status', 'confirmed', $link ),
                'count' => $counts['confirmed'],
                'current' => $currentStatus == 'confirmed'
            ),
            'view-not-confirmed' => array(
                'title' => __('Not Confirmed', 'bizpanda'),
                'link' => add_query_arg( 'opanda_status', 'not-confirmed', $link ),
                'count' => $counts['not-confirmed'],
                'current' => $currentStatus == 'not-confirmed'
            )
        );
        
        $views = array();
        foreach( $items as $name => $data ) {
            $views[$name] = "<a href='" . $data['link'] . "' class='" . ( $data['current'] ? 'current' : '' ) . "'>" . $data['title'] . " <span class='count'>(" . number_format_i18n( $data['count'] ) . ")</span></a>";
        }
        
        return $views;
    }
    
    public function no_items() {
        echo __( 'No leads found. ', 'bizpanda');
        
        $view = isset( $_GET['opanda_status'] ) ? $_GET['opanda_status'] : 'all';
        if ( 'all' !== $view ) return;
        
        if ( BizPanda::isSinglePlugin() ) {
            
            if ( BizPanda::hasPlugin('optinpanda') ) {
                printf('To start generating leads, create <a href="%s"><strong>Email Locker</strong></a> or <a href="%s"><strong>Sign-In Locker</strong></a> and lock with them some content on your website.', opanda_get_help_url('what-is-email-locker'), opanda_get_help_url('what-is-signin-locker'));
            } else {
                printf('To start generating leads, create <a href="%s"><strong>Sign-In Locker</strong></a> and lock with it some content on your website.', opanda_get_help_url('what-is-signin-locker'));
            }
            
        } else {
            printf('To start generating leads, create <a href="%s"><strong>Email Locker</strong></a> or <a href="%s"><strong>Sign-In Locker</strong></a> and lock with them some content on your website.', opanda_get_help_url('what-is-email-locker'), opanda_get_help_url('what-is-signin-locker'));
        }
    }

    public function search_box($text, $input_id) {
        if( !count($this->items) && !isset($_GET['s']) ) return;

        ?>
            <form id="searchform" action method="GET">
            <?php if(isset($_GET['post_type'])) : ?><input type="hidden" name="post_type" value="<?php echo $_GET['post_type'] ?>"><?php endif; ?>
            <?php if(isset($_GET['page'])) : ?><input type="hidden" name="page" value="<?php echo $_GET['page'] ?>"><?php endif; ?>
            <?php if(isset($_GET['opanda_status'])) : ?><input type="hidden" name="opanda_status" value="<?php echo $_GET['opanda_status'] ?>"><?php endif; ?>

            <p class="search-box">
                <label class="screen-reader-text" for="sa-search-input"><?php echo $text; ?></label>
                <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php if(isset($_GET['s'])) echo $_GET['s']?>">
                <input type="submit" name="" id="search-submit" class="button" value="<?php echo $text; ?>">
            </p>
            </form>
        <?php
    }
    
    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        
        return $columns = array(
            
           'cb' => '<input type="checkbox" />',
           'avatar' => '',
           'name' => __('Name', 'bizpanda'),
           'channel' => __('Channel', 'bizpanda'),
           'added' => __('Added', 'bizpanda'),
           'status' => __('Status', 'bizpanda'),
        );
    }
    
    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        
       return array(
           'name'  => 'name',
           'channel'  => 'channel',
           'added'  => 'added',
           'status' => 'status'
       );
    }
    
    public function get_bulk_actions() {
        $actions = array(
            'delete'	=> __('Delete', 'mymail')
        );

        return $actions;
    }
    
    /**
     * Checks and runs the bulk action 'delete'.
     */
    public function bulk_delete() {
        
        $action = $this->current_action();
        if ( 'delete' !== $action ) return;
        if ( empty(  $_POST['opanda_leads'] ) ) return;
        
        $ids = array();
        foreach( $_POST['opanda_leads'] as $leadId ) {
            $ids[] = intval( $leadId );
        }
        
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}opanda_leads WHERE ID IN (" . implode(',', $ids) . ")");
        $wpdb->query("DELETE FROM {$wpdb->prefix}opanda_leads_fields WHERE lead_id IN (" . implode(',', $ids) . ")");    
    }
        
    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        
        $query = "SELECT * FROM {$wpdb->prefix}opanda_leads";
        
        // where 
        
        $where = array();
        if ( isset( $_GET['opanda_status'] ) && in_array( $_GET['opanda_status'], array('confirmed', 'not-confirmed') ) ) {
           $where[] = 'lead_email_confirmed = ' . ( ( $_GET['opanda_status'] == 'confirmed' ) ? '1' : '0' );
        }
        
        if ( isset( $_GET['s'] ) ) {
            
            $search = trim(addcslashes(esc_sql($_GET['s']), '%_'));
            $search = explode(' ', $search);

            $searchSql = " (";
            $terms = array();
            
            foreach($search as $term){

                if(substr($term, 0,1) == '-'){
                    $term = substr($term,1);
                    $operator = 'AND';
                    $like = 'NOT LIKE';
                    $end = '(1=1)';
                }else{
                    $operator = 'OR';
                    $like = 'LIKE';
                    $end = '(1=0)';
                }

                $termsql = " ( ";
                $termsql .= " (lead_display_name $like '%".$term."%') $operator ";
                $termsql .= " (lead_name $like '%".$term."%') $operator ";
                $termsql .= " (lead_family $like '%".$term."%') $operator ";
                $termsql .= " (lead_email $like '%".$term."%') $operator ";
                $termsql .= " $end )";

                $terms[] = $termsql;
            }

            $searchSql .= implode(' AND ', $terms) .')';
            $where[] = $searchSql;
        }
        
        if ( !empty( $where ) ) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }   

        // order

        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'added';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'desc';
        
        if ( !in_array( $orderby, array( 'name', 'channel', 'added', 'status' ) ) ) $orderby = 'added';
        if ( !in_array( $order, array( 'asc', 'desc' ) ) ) $orderby = 'asc';
        
        if ( 'name' === $orderby ) $dbOrderBy = array( 'lead_display_name', 'lead_email');
        elseif ( 'channel' === $orderby ) $dbOrderBy = array( 'lead_item_id' );
        elseif ( 'added' === $orderby ) $dbOrderBy = array( 'lead_date' );
        elseif ( 'status' === $orderby ) $dbOrderBy = array( 'lead_confirmed' );

        foreach( $dbOrderBy as $index => $orderField ) {
            $dbOrderBy[$index] .= ' ' . strtoupper( $order );
        }
        
        if( !empty($dbOrderBy) & !empty($order) )
            $query.=' ORDER BY ' . implode ( ', ', $dbOrderBy );

        $totalitems = $wpdb->query($query);
        $perpage = 20;

        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        $totalpages = ceil($totalitems/$perpage);
        
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }

        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        
        $this->items = $wpdb->get_results($query);
    }
    
    /**
     * Shows a checkbox.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_cb($record) {
        return sprintf(
            '<input type="checkbox" name="opanda_leads[]" value="%s" />', $record->ID
        );
    }
    
    /**
     * Shows an avatar of the lead.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_avatar($record) {
        
        $url = admin_url('/edit.php?post_type=opanda-item&page=leads-bizpanda&action=leadDetails&leadID=' . $record->ID);
        $avatar = '';
        
        if ( !empty( $url ) ) $avatar .= '<a href="' .$url . '" class="opanda-avatar">';
        else $avatar .= '<span class="opanda-avatar">';
        
        $avatar .= OPanda_Leads::getAvatar( $record->ID, $record->lead_email, 40 );
        
        if ( !empty( $url ) ) $avatar .= '</a>';
        else $avatar .= '</span>';
        
        echo $avatar;
    }
    
    /**
     * Shows a name of the lead.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_name($record) {
        
        $name = '';

        $url = admin_url('/edit.php?post_type=opanda-item&page=leads-bizpanda&action=leadDetails&leadID=' . $record->ID);
        
        if ( !empty( $url ) ) $name .= '<a href="' . $url . '" class="opanda-name">';
        else $name .= '<strong class="opanda-name">';
        
        if ( !empty( $record->lead_display_name ) ) {
            $name .= $record->lead_display_name;
        } else {
            $name .= $record->lead_email;
        }
        
        if ( !empty( $url ) ) $name .= '</a>';
        else $name .= '</strong>';
        
        /**
        Social Icons
         */ 
        $fields = OPanda_Leads::getLeadFields( $record->ID  );
        
        if ( isset( $fields['facebookUrl'] ) ) {
            $name .= sprintf( '<a href="%s" target="_blank" class="opanda-social-icon opanda-facebook-icon"><i class="fa fa-facebook"></i></a>', $fields['facebookUrl'] );
        } 
        
        if ( isset( $fields['twitterUrl'] ) ) {
            $name .= sprintf( '<a href="%s" target="_blank" class="opanda-social-icon opanda-twitter-icon"><i class="fa fa-twitter"></i></a>', $fields['twitterUrl'] );
        } 
        
        if ( isset( $fields['googleUrl'] ) ) {
            $name .= sprintf( '<a href="%s" target="_blank" class="opanda-social-icon opanda-google-icon"><i class="fa fa-google-plus"></i></a>', $fields['googleUrl'] );
        } 
        
        if ( isset( $fields['linkedinUrl'] ) ) {
            $name .= sprintf( '<a href="%s" target="_blank" class="opanda-social-icon opanda-linkedin-icon"><i class="fa fa-linkedin"></i></a>', $fields['linkedinUrl'] );
        } 
         /**/
        
        if ( !empty( $record->lead_display_name ) ) {
            $name .= '<br />' . $record->lead_email;
        }
        
        echo $name;
    }
    
    /**
     * Shows how the lead was generated.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_channel($record) {
        
        $itemId = $record->lead_item_id;
        $itemTitle = $record->lead_item_title;

        $item = get_post( $itemId );

        $itemTitle = empty( $item )
            ? '<i>' . __('(unknown)', 'bizpanda') . '</i>'
            : $item->post_title;
        
        $via = empty( $item )
             ? $itemTitle
             : '<a href="' . opanda_get_admin_url('stats', array('opanda_id' => $itemId)) . '"><strong>' . $itemTitle. '</strong></a>';
 
        $via = sprintf( __("Via: %s", 'bizpanda'), $via );
        
        $postUrl = $record->lead_referer;
        $postTitle = $record->lead_post_title;

        $post = get_post( $record->lead_post_id );

        if ( !empty( $post) ){
            $postUrl = get_permalink( $post->ID );
            $postTitle = $post->post_title;
        }
        
        if ( empty( $postTitle) ) $postTitle = '<i>' . __('(no title)', 'bizpanda') . '</i>';
        $referer = '<a href="' . $postUrl . '"><strong>' . $postTitle . '</strong></a>';
        $where = sprintf( __("On Page: %s", 'bizpanda'), $referer );
        
        $text = $via . '<br />' . $where;
        echo $text;
    }
    
    /**
     * Shows when the lead was added.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_added($record) {
        
        echo date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $record->lead_date + (get_option('gmt_offset')*3600));
    }    
   
    /**
     * Shows a status of the lead.
     * 
     * @since 1.0.7
     * @return void
     */
    public function column_status($record) {

        if ( BizPanda::hasPlugin('optinpanda') ) {
            
            if ( $record->lead_email_confirmed) { ?>
                <span class="opanda-status-help" title="<?php _e('This email is real. It was received from social networks or the user confirmed it by clicking on the link inside the confirmation email.', 'bizpanda') ?>">
                    <i class="fa fa-check-circle-o"></i><i><?php _e('email', 'optinapnda') ?></i>
                </span>
            <?php } else { ?>
                <span class="opanda-status-help" title="<?php _e('This email was not confirmed. It means that actually this email address may be owned by another user.', 'bizpanda') ?>">
                    <i class="fa fa-circle-o"></i><i><?php _e('email', 'optinapnda') ?></i>
                </span>
            <?php }
            
            echo '<br />'; 
            
            if ( $record->lead_subscription_confirmed) { ?>
                <span class="opanda-status-help" title="<?php _e('This user confirmed his subscription.', 'bizpanda') ?>">
                    <i class="fa fa-check-circle-o"></i><i><?php _e('subscription', 'optinapnda') ?></i>
                </span>
            <?php } else { ?>
                <span class="opanda-status-help" title="<?php _e('This user has not confirmed his subscription.', 'bizpanda') ?>">
                    <i class="fa fa-circle-o"></i><i><?php _e('subscription', 'optinapnda') ?></i>
                </span>
            <?php }
            
        } else {
            
            if ( $record->lead_email_confirmed) { ?>
                <span class="opanda-status-help" title="<?php _e('This email is real. It was received from social networks.', 'bizpanda') ?>">
                    <i class="fa fa-check-circle-o"></i><i><?php _e('email', 'optinapnda') ?></i>
                </span>
            <?php } else { ?>
                <span class="opanda-status-help" title="<?php _e('This email was not confirmed. It means that actually this email address may be owned by another user.', 'bizpanda') ?>">
                    <i class="fa fa-circle-o"></i><i><?php _e('email', 'optinapnda') ?></i>
                </span>
            <?php }
            
        }
    }
}
 