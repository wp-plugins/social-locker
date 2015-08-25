<?php
/**
 * The page 'Settings'.
 * 
 * @since 1.0.0
 */
class OPanda_SettingsPage extends FactoryPages321_AdminPage  {

    /**
     * The parent menu of the page in the admin menu.
     * 
     * @see FactoryPages321_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $menuPostType = OPANDA_POST_TYPE;
    
    /**
     * The id of the page in the admin menu.
     * 
     * Mainly used to navigate between pages.
     * @see FactoryPages321_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $id = "settings";

    public function __construct(Factory325_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('Global Settings', 'bizpanda');
    }
    
    /**
     * Requests assets (js and css) for the page.
     * 
     * @see FactoryPages321_AdminPage
     * 
     * @since 1.0.0
     * @return void 
     */
    public function assets($scripts, $styles) {
        
        $this->scripts->request('jquery');
        
        $this->scripts->request( array( 
            'control.checkbox',
            'control.dropdown',
            'plugin.ddslick',
            ), 'bootstrap' );

        $this->styles->request( array( 
            'bootstrap.core', 
            'bootstrap.form-group',
            'bootstrap.separator',
            'control.dropdown',
            'control.checkbox',
            ), 'bootstrap' ); 
        
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/settings.010008.js');
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/settings.010008.css');   
        
        
    }
    
    /**
     * Renders the page 
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        global $bizpanda;
        
        $current = isset( $_GET['opanda_screen'] ) ? $_GET['opanda_screen'] : null;
        $screens = array();        
        
        $subscriptionOptions = array(
            'title' => __('Subscription Options', 'bizpanda'),  
            'class' => 'OPanda_SubscriptionSettings',
            'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.subscription.php'
        ); 
        
        $socialOptions = array();
        
        if ( BizPanda::hasFeature('social') || BizPanda::hasPlugin('sociallocker') ) {

            $socialOptions = array(
                'title' => __('Social Options', 'bizpanda'),
                'class' => 'OPanda_SocialSettings',
                'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.social.php'
            );
        }

        // for the plugin Opt-In Panda, the subscription options should be the first 
        
        if ( BizPanda::isSinglePlugin() && BizPanda::hasPlugin('optinpanda') ) {
            if ( empty( $current ) ) $current = 'subscription';
            
            $screens['subscription'] = $subscriptionOptions;
            if (!empty( $socialOptions ) ) $screens['social'] = $socialOptions;
        } else {
            if ( empty( $current ) ) $current = 'social';
            
            if (!empty( $socialOptions ) ) $screens['social'] = $socialOptions;
            if ( BizPanda::hasFeature('subscription') ) $screens['subscription'] = $subscriptionOptions; 
        }

        if ( BizPanda::hasFeature('lockers') ) {
        
            $screens['lock'] = array(
                'title' => __('Lock Options', 'bizpanda'),
                'class' => 'OPanda_AdvancedSettings',
                'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.lock.php'
            );
        }
        
        $screens['stats'] = array(
            'title' => __('Stats Options', 'bizpanda'),
            'class' => 'OPanda_StatsSettings',
            'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.stats.php'
        ); 
        
        $screens['text'] = array(
            'title' => __('Front-end Text', 'bizpanda'),
            'class' => 'OPanda_TextSettings',
            'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.text.php'
        );
        
        if ( BizPanda::hasFeature('terms') ) {
            
            $screens['terms'] = array(
                'title' => __('Terms & Policies', 'bizpanda'),
                'class' => 'OPanda_TermsSettings',
                'path' => OPANDA_BIZPANDA_DIR . '/admin/settings/class.terms.php'
            );
        }

        $screens = apply_filters( 'opanda_settings_screens', $screens );
        if ( !isset( $screens[$current] ) ) $current = 'social';
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/settings/class.settings.php';
        
        require_once $screens[$current]['path'];
        $screen = new $screens[$current]['class']( $this );

        $action = isset( $_GET['opanda_action'] ) ? $_GET['opanda_action'] : null;
        if ( !empty( $action ) ) {
            $methodName = $action . 'Action';
            $screen->$methodName();
            return;
        }
        
        // getting options
        
        $options = $screen->getOptions();
        $options = apply_filters("opanda_{$current}_settings", $options );
        
        // creating a form

        $form = new FactoryForms328_Form(array(
            'scope' => 'opanda',
            'name'  => 'setting'
        ), $bizpanda );
        
        $form->setProvider( new FactoryForms328_OptionsValueProvider(array(
            'scope' => 'opanda'
        )));
        
        $form->add($options);
        
        
        
        if ( isset( $_POST['save-action'] ) ) {

            do_action("opanda_{$current}_settings_saving");
            $form->save();
            do_action("opanda_{$current}_settings_saved");
            
            $redirectArgs = apply_filters("opanda_{$current}_settings_redirect_args", array(
                'opanda_saved' => 1,
                'opanda_screen' => $current
            ));
            
            return $this->redirectToAction('index', $redirectArgs);
        }
        
        $formAction = add_query_arg( array(
            'post_type' => OPANDA_POST_TYPE,
            'page' => 'settings-' . $bizpanda->pluginName,
            'opanda_screen' => $current
        ), admin_url('edit.php') );

        ?>
        <div class="wrap ">
            
            <h2 class="nav-tab-wrapper">
                <?php foreach ( $screens as $screenName => $screenData ) { ?><a href="<?php $this->actionUrl('index', array('opanda_screen' => $screenName)) ?>" class="nav-tab <?php if ( $screenName === $current ) { echo 'nav-tab-active'; } ?>">
                    <?php echo $screenData['title'] ?>
                </a><?php } ?>
            </h2>
            
            <?php $screen->header()  ?>
            
            <div class="factory-bootstrap-329 opanda-screen-<?php echo $current ?>">
            <form method="post" class="form-horizontal" action="<?php echo $formAction ?>">

                <?php if ( isset( $_GET['opanda_saved'] ) && empty( $screen->error) ) { ?>
                <div id="message" class="alert alert-success">
                    <p><?php _e('The settings have been updated successfully!', 'bizpanda') ?></p>
                </div>
                <?php } ?>
                
                <?php if ( !empty( $screen->success ) ) { ?>
                <div id="message" class="alert alert-success">
                    <p><?php echo $screen->success ?></p>
                </div>
                <?php } ?>
                
                <?php if ( !empty( $screen->error ) ) { ?>
                <div id="message" class="alert alert-danger">
                    <p><?php echo $screen->error ?></p>
                </div>
                <?php } ?>
                
                <?php do_action('onp_sl_settings_options_notices') ?>

                <div style="padding-top: 10px;">
                <?php $form->html(); ?>
                </div>
                
                <div class="form-group form-horizontal">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="control-group controls col-sm-10">
                        <input name="save-action" class="btn btn-primary" type="submit" value="<?php _e('Save Changes', 'bizpanda') ?>"/>
                    </div>
                </div>
            
            </form>
            </div>  
                
        </div>
        <?php
    }
}

FactoryPages321::register($bizpanda, 'OPanda_SettingsPage');

