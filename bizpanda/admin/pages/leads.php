<?php
/**
 * The file contains a short help info.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OPanda_LeadsPage extends OPanda_AdminPage  {
 
    public function __construct( $plugin ) {   
        $this->menuPostType = OPANDA_POST_TYPE;
        $this->id = "leads";
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/leads.php';
        
        $count = OPanda_Leads::getCount();
        if ( empty( $count ) ) $count = '0';
        
        $this->menuTitle = sprintf( __('Leads (%d)', 'bizpanda'), $count );
        
        parent::__construct( $plugin );
    }
  
    public function assets($scripts, $styles) {
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/leads.010008.css'); 
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/leads.010008.js'); 
        
        $this->scripts->request('jquery');
        
        $this->scripts->request( array( 
            'control.checkbox',
            'control.dropdown'
            ), 'bootstrap' );

        $this->styles->request( array( 
            'bootstrap.core', 
            'bootstrap.form-group',
            'bootstrap.separator',
            'control.dropdown',
            'control.checkbox',
            ), 'bootstrap' );
    }
    
    public function indexAction() {

        if(!class_exists('WP_List_Table')){
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }
        
        require_once( OPANDA_BIZPANDA_DIR . '/admin/includes/classes/class.leads.table.php' );

        $table = new OPanda_LeadsListTable( array('screen' => 'bizpanda-leads') );
        $table->prepare_items();

        ?>
        <div class="wrap factory-fontawesome-320" id="opanda-leads-page">

            <h2>
                <?php _e('Leads', 'bizpanda') ?>
                <a href="<?php $this->actionUrl('export') ?>" class="add-new-h2"><?php _e( 'export', 'bizpanda' ); ?></a>
            </h2>
            
            <?php if ( BizPanda::isSinglePlugin() ) { ?>

                <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
                    <p style="margin-top: 0px;"> <?php _e('This page shows contacts of visitors who opted-in or signed-in on your website through Email or Sign-In Lockers.', 'bizpanda'); ?></p>
                <?php } else { ?>
                    <p style="margin-top: 0px;"><?php printf( __('This page shows contacts of visitors who signed-in on your website through the <a href="%s">Sign-In Locker</a>.', 'bizpanda'), opanda_get_help_url('what-is-signin-locker') ); ?></p>
                <?php } ?>

            <?php } else { ?>
                <p style="margin-top: 0px;"> <?php _e('This page shows contacts of visitors who opted-in or signed-in on your website through Email or Sign-In Lockers.', 'bizpanda'); ?></p>
            <?php } ?>
        
            <?php
                $table->search_box(__('Search Leads', 'mymail'), 's');
                $table->views();
            ?>

            <form method="post" action="">
            <?php echo $table->display(); ?>
            </form>
        </div>
        <?php
        
        OPanda_Leads::updateCount();
    }
    
    public function leadDetailsAction() {
            $leadId = isset( $_REQUEST['leadID'] ) ? intval( $_REQUEST['leadID'] ) : 0;
            
            $lead = OPanda_Leads::get( $leadId );
            $customFields = OPanda_Leads::getCustomFields( $leadId );
            
            $email = $lead->lead_email;
            $name = $lead->lead_name;
            $family = $lead->lead_family;

            if ( !empty( $name) || !empty( $family) ) {
                $displayName = $name . ' ' . $family;
            } else {
                $displayName = !empty( $lead->lead_display_name )? $lead->lead_display_name : $lead->lead_email;
            }  
                
            $emailConfirmed = empty( $lead->lead_email_confirmed ) ? 0 : 1;
            $subscriptionConfirmed = empty( $lead->lead_subscription_confirmed ) ? 0 : 1;
            
            if ( isset( $_POST['submit'] ) ) {
                
                $data = array();
                
                $email = $_POST['email'];    
                
                if ( !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@.+\./', $email) ) {
                    
                    $error = __('Please enter a valid email.', 'bizpanda');
                    
                } else {

                    $name = $_POST['name'];
                    $family = $_POST['family'];

                    if ( !empty( $name) || !empty( $family) ) {
                        $displayName = $name . ' ' . $family;
                    } else {
                        $displayName = !empty( $lead->lead_display_name )? $lead->lead_display_name : $lead->lead_email;
                    } 

                    $data['email'] = $email;
                    $data['displayName'] = $displayName;
                    $data['name'] = $name;
                    $data['family'] = $family;

                    $emailConfirmed = empty( $_POST['email_confirmed'] ) ? 0 : 1;
                    $subscriptionConfirmed = empty( $_POST['subscription_confirmed'] ) ? 0 : 1;
                    
                    $customValues = isset( $_POST['opanda_values'] ) ? $_POST['opanda_values'] : array();
                    $customNames = isset( $_POST['opanda_names'] ) ? $_POST['opanda_names'] : array();            

                    $index = 0;
                    foreach( $customNames as $customName ) {
                        $data['{' . $customName . '}'] = $customValues[$index];
                        $customFields[$customName] = $customValues[$index];
                        $index++;
                    }

                    OPanda_Leads::save($lead, $data, array(), $emailConfirmed, $subscriptionConfirmed);

                    $url = admin_url('/edit.php?post_type=opanda-item&page=leads-bizpanda&action=leadDetails&leadID=' . $lead->ID . '&opanda_success=1');
                    wp_redirect($url);
                    exit;
                }
            }

            $avatar = OPanda_Leads::getAvatar( $leadId, $lead->lead_email, 150 );
            $postUrl = admin_url('/edit.php?post_type=opanda-item&page=leads-bizpanda&action=leadDetails&leadID=' . $lead->ID);
            $cancelUrl = admin_url('/edit.php?post_type=opanda-item&page=leads-bizpanda');
        ?>

        <div class="wrap factory-fontawesome-320" id="opanda-lead-details-page">
            
        <h2>Edit <strong><?php echo htmlspecialchars( $displayName ) ?></strong></h2>
        
        <?php if ( isset( $_GET['opanda_success'] ) ) { ?>
        <div class="factory-bootstrap-329">
            <div class="alert alert-success"><?php _e('<strong>Well done!</strong> The lead data updated successfully.', 'bizpanda') ?></div>
        </div>
        <?php } ?>
        
        <?php if ( !empty( $error ) ) { ?>
        <div class="factory-bootstrap-329">
            <div class="alert alert-danger"><?php echo $error; ?></div>
        </div>
        <?php } ?>
        
        <form method="POST" action="<?php echo $postUrl ?>">
            <input type="hidden" name="leadID" value="<?php echo $leadId ?>" />
            <input type="hidden" name="submit" value="1" />
            
        <table class="form-table">
            <tr>
                <td scope="row" class="avatar-wrap">
                    <div class="opanda-avatar"><?php echo $avatar ?></div>
                </td>
                
                <td class="user-info">
                    
                    <h3 class="detail">
                        <ul class="click-to-edit">
                            <li><?php echo htmlspecialchars( $email ) ?></li>
                            <li><input id="opanda_email" class="" type="text" name="email" value="<?php echo htmlspecialchars( $email ) ?>" placeholder="<?php _e('Email', 'bizpanda') ?>"></li>
                        </ul>
                    </h3>
                    
                    <div class="detail">
                        <label for="opanda_name"><?php _e('Name:', 'bizpanda') ?></label>
                        <ul class="click-to-edit">
                            <li><?php echo htmlspecialchars( $name ) ?> <?php echo htmlspecialchars( $family ) ?></li>
                            <li>
                                <input id="opanda_name" type="text" name="name" value="<?php echo htmlspecialchars( $name ) ?>" placeholder="<?php _e('First Name', 'bizpanda') ?>">
                                <input id="opanda_family" class="" type="text" name="family" value="<?php echo htmlspecialchars( $family ) ?>" placeholder="<?php _e('Last Name', 'bizpanda') ?>">
                            </li>
                        </ul>
                    </div>
                    
                    <div class="detail">
                        <label for="opanda_email_confirmed"><?php _e('Email Confirmed:', 'bizpanda') ?></label>
                        <ul class="click-to-edit">
                            <li><?php if ( $emailConfirmed ) { _e('yes', 'bizpanda'); } else { _e('no', 'bizpanda'); } ?></li>
                            <li>
                                <input type="checkbox" id="opanda_email_confirmed" name="email_confirmed" value="1" <?php if ( $emailConfirmed ) { echo 'checked="checked"'; } ?> >            
                            </li>
                        </ul>
                    </div>
                    
                    <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
                    
                    <div class="detail">
                        <label for="opanda_subscription_confirmed"><?php _e('Subscription Confirmed:', 'bizpanda') ?></label>
                        <ul class="click-to-edit">
                            <li><?php if ( $subscriptionConfirmed ) { _e('yes', 'bizpanda'); } else { _e('no', 'bizpanda'); } ?></li>
                            <li>
                                <input type="checkbox" id="opanda_email_confirmed" name="subscription_confirmed" value="1" <?php if ( $subscriptionConfirmed ) { echo 'checked="checked"'; } ?> >                
                            </li>
                        </ul>
                    </div>
                    
                    <?php } ?>
                    
                    <?php if ( !empty( $customFields ) ) { ?>
                    
                    <div class="custom-field-wrap">
                        
                    <?php 
                        $index = 0;
                        foreach ( $customFields as $fieldName => $fieldValue ) {
                        $index++;
                    ?>
                    
                        <div class="detail">
                            <label for="opanda_<?php echo $index ?>"><?php echo $fieldName ?>:</label>

                            <ul class="click-to-edit">
                                <li><?php echo $fieldValue ?></li>
                                <li><input type="text" id="opanda_<?php echo $index ?>" name="opanda_values[]" value="<?php echo $fieldValue ?>" class="regular-text input"></li>
                                <input type="hidden" name="opanda_names[]" value="<?php echo $fieldName ?>" />
                            </ul>
                        </div>
                    
                    <?php } ?>
                        
                    </div>
                    
                    <?php } ?>

                    <div class="controls-wrap">
                        <input type="submit" class="button button-primary" value="<?php _e('Save Changes', 'bizpanda') ?>" />
                        <a href="<?php echo $cancelUrl ?>" class="button button-default"><?php _e('Return', 'bizpanda') ?></a> 
                    </div>
                </td>

            </tr>
        </table>
            
        </form>
        
        </div>
        
        <?php
    }
    
    public function exportAction() {
        global $bizpanda;
            
            ?>
            <div class="wrap" id="opanda-export-page">

                <h2>
                    <?php _e('Exporting Leads', 'bizpanda') ?>
                </h2>

                <p style="margin-top: 0px;"> <?php printf( __('This feature is available only in the Premium version. <a href="%s" target="_blank"><strong>Go Premium to unlock the exporting feature</strong></a>.', 'bizpanda'), opanda_get_premium_url() ); ?></p>
            </div>
            <?php
            
        

    }
}

FactoryPages321::register($bizpanda, 'OPanda_LeadsPage');
