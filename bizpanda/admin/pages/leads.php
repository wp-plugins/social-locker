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
        
        $this->menuTitle = sprintf( __('Leads (%d)', 'optinpanda'), $count );
        
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
                <?php _e('Leads', 'optinpanda') ?>
                <a href="<?php $this->actionUrl('export') ?>" class="add-new-h2"><?php _e( 'export', 'opanda' ); ?></a>
            </h2>
            
            <?php if ( BizPanda::isSinglePlugin() ) { ?>

                <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
                    <p style="margin-top: 0px;"> <?php _e('This page shows contacts of visitors who opted-in or signed-in on your website through Email or Sign-In Lockers.', 'optinpanda'); ?></p>
                <?php } else { ?>
                    <p style="margin-top: 0px;"><?php printf( __('This page shows contacts of visitors who signed-in on your website through the <a href="%s">Sign-In Locker</a>.', 'optinpanda'), opanda_get_help_url('what-is-signin-locker') ); ?></p>
                <?php } ?>

            <?php } else { ?>
                <p style="margin-top: 0px;"> <?php _e('This page shows contacts of visitors who opted-in or signed-in on your website through Email or Sign-In Lockers.', 'optinpanda'); ?></p>
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
    
    public function exportAction() {
        global $bizpanda;
            
            ?>
            <div class="wrap" id="opanda-export-page">

                <h2>
                    <?php _e('Exporting Leads', 'optinpanda') ?>
                </h2>

                <p style="margin-top: 0px;"> <?php printf( __('This feature is available only in the Premium version. <a href="%s" target="_blank"><strong>Go Premium to unlock the exporting feature</strong></a>.', 'opanda'), opanda_get_premium_url() ); ?></p>
            </div>
            <?php
            
        

    }
}

FactoryPages321::register($bizpanda, 'OPanda_LeadsPage');
