<?php
/**
 * The file contains a class to configure the metabox Social Options.
 * 
 * Created via the Factory Metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The class configure the metabox Social Options.
 * 
 * @since 1.0.0
 */
class OPanda_ConnectOptionsMetaBox extends FactoryMetaboxes321_FormMetabox
{
    /**
     * A visible title of the metabox.
     * 
     * Inherited from the class FactoryMetabox.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @var string
     */
    public $title;    
   
    /**
     * A prefix that will be used for names of input fields in the form.
     * 
     * Inherited from the class FactoryFormMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $scope = 'opanda';
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'core';
	
    public $cssClass = 'factory-bootstrap-329';
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
       $this->title = __('Connect Options', 'signinlocker');
    }
    
    /**
     * Configures a metabox.
     */
    public function configure( $scripts, $styles) {
         $styles->add( BIZPANDA_SIGNIN_LOCKER_URL . '/admin/assets/css/connect-options.010000.css');   
         $scripts->add( BIZPANDA_SIGNIN_LOCKER_URL . '/admin/assets/js/connect-options.010000.js');
         
         do_action( 'opanda_connect_options_assets', $scripts, $styles );
    }
    
    /**
     * Configures the connect buttons options.
     */ 
    public function form( $form ) {
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
        
        $options = array();
        
        $options[] = array(
            'type'      => 'html',
            'html'      => '<div class="opanda-fullwidth opanda-hint">
                            <strong>'.__('How to setup', 'signinlocker').'</strong>: ' . 
                            '<ol>' . 
                            '<li>' . __('Select social networks which will be available to sign in.', 'signinlocker') . 
                            '<li>' . __('For each social network, select actions which have to be performed to sign in.', 'signinlocker') .
                            '<li>' . __('Configure each selected action by clicking on its title.', 'signinlocker') .
                            '</ol>' .
                            '</div>'
        );

        $options[] =  array(
            'type'      => 'html',
            'html'      => array( $this, 'showConnectButtonsControl' )
        );
        
        // leads options
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-lead-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off factory-fontawesome-320',
            
            'items'     => array(
                array(
                    'type' => 'html',
                    'html' => $this->getOptionsHeaderHtml(
                        __('Action: Save Email', 'signinlocker'),
                        __('This action retrieves an email and some other personal data of the user and saves it in the database.', 'signinlocker')
                    )
                ),
                array(
                    'type' => 'html',
                    'html' => array($this,'getLeadExplanationOption')
                )
            )
        );
        
        // subscription options
        
        $subscription = array();
        
        $subscription[] = array(
            'type' => 'html',
            'html' => $this->getOptionsHeaderHtml(
                __('Action: Subscribe', 'signinlocker'),
                __('This action allows to subscribe the user to the specified mailing list when one clicks on the Sign In button.', 'signinlocker')
            )
        );
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/subscriptions.php';

        if ( BizPanda::hasPlugin('optinpanda') ) {

            $subscription[] = array(
                'type' => 'html',
                'html' => array($this, 'showSubscriptionService')
            );
            
            $serviceName = OPanda_SubscriptionServices::getCurrentName();

            if ( 'database' !== $serviceName && 'none' !== $serviceName ) {

                $serviceName = OPanda_SubscriptionServices::getCurrentServiceInfo();
                $manualList = isset( $serviceName['manualList'] ) ? $serviceName['manualList'] : false;

                if ( $manualList ) {

                    $subscription[] = array(
                        'type' => 'textbox',
                        'name' => 'subscribe_list',
                        'title' => __('List', 'signinlocker'),
                        'hint' => __( 'Specify the list ID to add subscribers.', 'signinlocker' )
                    );

                } else {

                    $subscription[] = array(
                        'type' => 'dropdown',
                        'name' => 'subscribe_list',
                        'data' => array(
                            'ajax' => true,
                            'url' => admin_url('admin-ajax.php'),
                            'data' => array(
                                'action' => 'opanda_get_subscrtiption_lists',
                                'opanda_service' => $serviceName
                            )
                        ),
                        'empty' => __( '- empty -', 'signinlocker' ),
                        'title' => __('List', 'signinlocker'),
                        'hint' => __( 'Select the list to add subscribers.', 'signinlocker' )
                    );
                }
            } 

            $subscription[] = array(
                'type' => 'dropdown',
                'name' => 'subscribe_mode',
                'hasGroups' => false,
                'hasHints' => true,
                'data' => OPanda_SubscriptionServices::getCurrentOptinModes( true ),
                'title' => __('Opt-In Mode', 'signinlocker')
            );

            $subscription[] = array(
                'type' => 'checkbox',
                'way' => 'buttons',
                'name' => 'subscribe_name',
                'title' => __('Require Name', 'signinlocker'),
                'hint' => 'If On, requires to specify the name to unlock (only for the Email Form).'
            );

            $subscription[] = array(
                'type' => 'separator'
            );

            $subscription[] = array(
                'type'      => 'textbox',
                'name'      => 'subscribe_before_form',
                'title'     => __('Before Form', 'signinlocker'),
                'hint'      => __('The text before the form.', 'signinlocker'),
                'default'   => __('Cannot sign in via social networks? Enter your email manually.', 'signinlocker')
            ); 

            $subscription[] = array(
                'type'      => 'textbox',
                'name'      => 'subscribe_button_text',
                'title'     => __('Buttton Text', 'signinlocker'),
                'hint'      => __('The text on the button.', 'signinlocker'),
                'default'   => __('sign in to unlock', 'signinlocker')
            );

            $subscription[] = array(
                'type'      => 'textbox',
                'name'      => 'subscribe_after_button',
                'title'     => __('After Buttton', 'signinlocker'),
                'hint'      => __('The text below the button. Guarantee something.', 'signinlocker'),
                'default'   => __('Your email address is 100% safe from spam!', 'signinlocker')
            );
            
        } else {
            
            $subscription[] = array(
                'type' => 'html',
                'html' => array($this, 'getSubscriptionExplanationOption')
            );
            
        }
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-subscribe-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off factory-fontawesome-320',
            
            'items'     => $subscription
        );
        
        // signup options
        
        if ( !function_exists('get_editable_roles') ) {
            require_once( ABSPATH . '/wp-admin/includes/user.php' );
        }

        $rolesItems = array();
        foreach (get_editable_roles() as $roleName => $roleInfo) {
            $rolesItems[] = array($roleName, $roleName);
        }
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-signup-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off factory-fontawesome-320',
            
            'items'     => array(
                array(
                    'type' => 'html',
                    'html' => $this->getOptionsHeaderHtml(
                        __('Action: Create Account', 'signinlocker'),
                        __('This action registers the user on your website (the password will be generated randomly).', 'signinlocker')
                    )
                ), 
                array(
                    'type' => 'dropdown',
                    'name' => 'signup_mode',
                    'hasGroups' => false,
                    'hasHints' => true,
                    'data' => array(
                        // array('hidden', __('Auto (Recommended)', 'signinlocker'), __('The user will be logged in automatically after clicking on the Sign-In Buttons.', 'signinlocker')),
                        array(
                            'title' => __('Manual', 'signinlocker'),
                            'value' => 'postponed',
                            'hint' =>  __('The account will be created but the user will not be logged in. The user will have to log in manually by using the login details sent via email. The locked content will get available instantly after clicking on the Sign-In buttons.', 'signinlocker')
                        )
                    ),
                    'title' => __('Login Mode', 'signinlocker'),
                    'default' => 'hidden'
                ),
                array(
                    'type' => 'html',
                    'html' => array($this,'getSignupRoleOption')
                ),
                array(
                    'type' => 'html',
                    'html' => array($this,'getSignupWelcomeOption')
                ),
            )
        );

        // twitter follow options
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-twitter-follow-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off',
            
            'items'     => array(
                array(
                    'type' => 'html',
                    'html' => $this->getOptionsHeaderHtml( 
                        __('Twitter Action: Follow', 'signinlocker'),
                        __('This action makes the user following you on Twitter after clicking the Sign In button.', 'signinlocker')
                    )
                ), 
                array(
                    'type'  => 'textbox',
                    'title' => __('User to follow', 'signinlocker'),
                    'hint'  => __('Set a user screen name to follow (for example, <a href="http://twitter.com/byonepress" target="_blank">byonepress</a>)', 'signinlocker'),
                    'name'  => 'twitter_follow_user'
                ), 
                array(
                    'type'  => 'checkbox',
                    'way'   => 'buttons',
                    'title' => __('Notifications', 'signinlocker'),
                    'hint'  => __('If On, the follower will get notifications about new tweets (usually via sms).', 'signinlocker'),
                    'name'  => 'twitter_follow_notifications',
                    'default' => false
                )
            )
        );
        
        // twitter tweet options
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-twitter-tweet-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off',
            
            'items'     => array(
                array(
                    'type' => 'html',
                    'html' => $this->getOptionsHeaderHtml( 
                        __('Twitter Action: Tweet', 'signinlocker'),
                        __('Sends the specified tweet below from behalf of the user after signing in.', 'signinlocker')
                    )
                ), 
                array(
                    'type'  => 'textarea',
                    'title' => __('Tweet', 'signinlocker'),
                    'hint'  => __('Type a message to tweet. It may include any URL.', 'signinlocker'),
                    'name'  => 'twitter_tweet_message'
                ),
            )
        );

        // youtube subscribe options
        
        $options[] =  array(
            'type'      => 'div',
            'id'        => 'opanda-google-youtube-subscribe-options',
            'cssClass'  => 'opanda-connect-buttons-options opanda-off',
            
            'items'     => array(
                array(
                    'type' => 'html',
                    'html' => $this->getOptionsHeaderHtml( 
                        __('Google Action: Subscribe To Youtube Channel', 'signinlocker'),
                        __('This action subscribers the user to the specified Youtube channel.', 'signinlocker')
                    )
                ), 
                array(
                    'type'  => 'textbox',
                    'title' => __('Youtube Channel ID', 'signinlocker'),
                    'hint'  => __('Set a channel ID to subscribe (for example, <a href="http://www.youtube.com/channel/UCANLZYMidaCbLQFWXBC95Jg" target="_blank">UCANLZYMidaCbLQFWXBC95Jg</a>).', 'signinlocker'),
                    'name'  => 'google_youtube_channel_id'
                )
            )
        );

        // hidden files to save active buttons and their actions
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'connect_buttons',
            'default'   => 'facebook,twitter,google,email'
        );
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'facebook_actions',
            'default'   => 'lead'
        ); 
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'twitter_actions',
            'default'   => 'lead'
        );
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'google_actions',
            'default'   => 'lead'
        ); 
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'linkedin_actions',
            'default'   => 'lead'
        );
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'email_actions',
            'default'   => 'lead'
        );   
        
        $options[] = array(
            'type'      => 'hidden',
            'name'      => 'catch_leads',
            'default'   => true
        );    
        
        $options = apply_filters('opanda_connect_options', $options);
        $form->add( $options ); 
    }
    
    public function getOptionsHeaderHtml( $title, $description = null ) {
        $title = '<strong>' . $title . '</strong>';
        $description = empty( $description ) ? '' : '<p>' . $description . '</p>';
        
        return '<div class="form-group opanda-header"><label class="col-sm-2 control-label"></label><div class="control-group col-sm-10"><div class="opanda-inner-wrap">' . $title . $description . '</div></div></div>';
    }
    
    public function getLeadExplanationOption() {
        $url = OPanda_Plugins::getPremiumUrl('optinpanda');
        ?>

        <div class="form-group" style="margin-bottom: 10px;">
            <label class="col-sm-2 control-label">
                 <?php _e('How to use', 'signinlocker') ?>
            </label>
            <div class="control-group col-sm-10">
                <p style="padding-top: 3px;">
                    <?php printf( __('<a href="%s" class="button" target="_blank">See emails</a> of users who already signed-up or <a href="%s" class="button" target="_blank">export emails</a> in the CSV format.', 'signinlocker'), admin_url('edit.php?post_type=opanda-item&page=leads-bizpanda'), admin_url('admin.php?page=leads-bizpanda&action=export') ) ?>
                </p>
                <?php ?>
                <?php if ( !BizPanda::hasPlugin('optinpanda') ) { ?>
                <p>
                    <?php printf( __('Install the plugin <a href="%s" target="_blank">Opt-In Panda</a> to subscribe automatically all the signed-up users to your mailing list. Supports most of services and plugins: Aweber, MailChimp, GetResponse, MyMail, MailPoet, K-news and more.'), $url ) ?>
                </p>
                <?php } ?>
                <?php 
 ?>
            </div>
        </div>

        <?php
   
    }
    
    public function getSubscriptionExplanationOption() {
        $url = OPanda_Plugins::getPremiumUrl('optinpanda', 'co-subscribe-action');
        ?>

        <div class="form-group" style="margin-bottom: 10px;">
            <label class="col-sm-2 control-label">
                 <?php _e('How to use', 'signinlocker') ?>
            </label>
            <div class="control-group col-sm-10">
                <p>
                    <?php printf( __('Install the plugin <a href="%s" target="_blank">Opt-In Panda</a> to subscribe automatically all the signed-up users to your mailing list. Supports most of services and plugins: Aweber, MailChimp, GetResponse, MyMail, MailPoet, K-news and more.'), $url ) ?>
                </p>
            </div>
        </div>

        <?php
    }
    
    public function getSignupRoleOption() {
        $defaultRole = get_option('default_role');
        ?>

        <div class="form-group" style="margin-bottom: 10px;">
            <label class="col-sm-2 control-label">
                <?php _e('New User Role', 'signinlocker') ?>
            </label>
            <div class="control-group col-sm-10">
                <p style="padding-top: 7px;">
                    <?php printf( __('All new users will be assigned to the role <strong>%s</strong> (<a href="%s" target="_blank">change</a>).', 'signinlocker' ), $defaultRole, admin_url('options-general.php') ) ?>
                </p>
            </div>
        </div>

        <?php

    }
    
    public function getSignupWelcomeOption() {
        $defaultRole = get_option('default_role');
        ?>

        <div class="form-group">
            <label class="col-sm-2 control-label">
                <?php _e('Welcome E-mail', 'signinlocker') ?>
            </label>
            <div class="control-group col-sm-10">
                <p style="padding-top: 7px;">
                    <?php printf( __('By default new users receive the standard wordpress welcome message. You can change it if you want.<br /><a href="https://wordpress.org/plugins/search.php?q=Welcome+Email" target="_blank">Click here</a> to select a free plugin to customize the welcome email.', 'signinlocker' ), $defaultRole, admin_url('options-general.php') ) ?>
                </p>
            </div>
        </div>

        <?php

    }
    
    // ------------------------------------------------------------------------------
    // Shows the control to manage the Connect Buttons
    // ------------------------------------------------------------------------------
    
    /**
     * Shows the control to manage the connect buttons.
     * 
     * @since 1.0.0
     * @return string
     */
    public function showConnectButtonsControl() {

        $buttons = array(

            'facebook' => array(
                'title' => __('Facebook', 'signinlocker'),
                'errors' => array($this, 'getFacebookErrors'),
                'actions' => array(
                    'lead' => array(
                        'on'    => true,
                        'title' => __('Save Email', 'signinlocker'),
                        'always' => true
                    ),
                    'subscribe' => array(
                        'title' => __('Subscribe', 'signinlocker')
                    ),
                    'signup' => array(
                        'on'    => true,
                        'title' => __('Create Account', 'signinlocker')
                    )
                )
            ),
            
            'twitter' => array(
                'title' => __('Twitter', 'signinlocker'),
                'errors' => array($this, 'getTwitterErrors'),
                'actions' => array(
                    'lead' => array(
                        'on'    => true,
                        'title' => __('Save Email', 'signinlocker'),
                        'always' => true
                    ),
                    'subscribe' => array(
                        'title' => __('Subscribe', 'signinlocker')
                    ),
                    'signup' => array(
                        'on'    => true,
                        'title' => __('Create Account', 'signinlocker')
                    ),
                    'follow' => array(
                        'title' => __('Follow', 'signinlocker'),
                        'type'  => 'social'
                    ),
                    'tweet' => array(
                        'title' => __('Tweet', 'signinlocker'),
                        'type'  => 'social'
                    )
                )
            ),
            
            'google' => array(
                'title' => __('Google', 'signinlocker'),
                'errors' => array($this, 'getGoogleErrors'),
                'actions' => array(
                    'lead' => array(
                        'on'    => true,
                        'title' => __('Save Email', 'signinlocker'),
                        'always' => true
                    ),
                    'subscribe' => array(
                        'title' => __('Subscribe', 'signinlocker')
                    ),
                    'signup' => array(
                        'on'    => true,
                        'title' => __('Create Account', 'signinlocker')
                    ),
                    'youtube-subscribe' => array(
                        'title' => __('Subscribe (YT)', 'signinlocker'),
                        'type'  => 'social'
                    )
                )
            ),
            
            'linkedin' => array(
                'title' => __('LinkedIn', 'signinlocker'),
                'errors' => array($this, 'getLinkedInErrors'),
                'actions' => array(
                    'lead' => array(
                        'on'    => true,
                        'title' => __('Save Email', 'signinlocker'),
                        'always' => true
                    ),
                    'subscribe' => array(
                        'title' => __('Subscribe', 'signinlocker')
                    ),
                    'signup' => array(
                        'on'    => true,
                        'title' => __('Create Account', 'signinlocker')
                    )
                )
            ),
            
            'email' => array(
                'title' => __('Email Form', 'signinlocker'),
                'errors' => array($this, 'getEmailFormErrors'),
                'actions' => array(
                    'lead' => array(
                        'on'    => true,
                        'title' => __('Save Email', 'signinlocker'),
                        'always' => true
                    ),
                    'subscribe' => array(
                        'title' => __('Subscribe', 'signinlocker')
                    ),
                    'signup' => array(
                        'on'    => true,
                        'title' => __('Create Account', 'signinlocker')
                    )
                )
            )
        );

        if ( BizPanda::hasPlugin('optinpanda') ) {

            foreach( $buttons as $buttonName => $buttonData ) {
                $buttons[$buttonName]['actions']['subscribe']['on'] = true;      
            }

        } else {

            $url = OPanda_Plugins::getPremiumUrl('optinpanda', 'co-subscribe-action');

            foreach( $buttons as $buttonName => $buttonData ) {
                $buttons[$buttonName]['actions']['subscribe']['error'] = sprintf( __('To enable this action, please install the plugin Opt-In Panda which provides subscription features. <a href="%s" target="_blank">Click here to learn more</a>.', 'signinlocker'), $url );  
            }  

        }
    
        if ( BizPanda::hasPlugin('sociallocker') ) {

            foreach( $buttons as $buttonName => $buttonData ) {
                foreach( $buttons[$buttonName]['actions'] as $actionName => $actionData ) {

                    if ( isset( $actionData['type'] ) && 'social' === $actionData['type'] ) {
                        $buttons[$buttonName]['actions'][$actionName]['on'] = true;
                    }   
                }   
            }
            
        } else {
            
            $url = OPanda_Plugins::getPremiumUrl('sociallocker', 'co-social-actions');

            foreach( $buttons as $buttonName => $buttonData ) {
                foreach( $buttons[$buttonName]['actions'] as $actionName => $actionData ) {
                    
                    if ( isset( $actionData['type'] ) && 'social' === $actionData['type'] ) {
                        $buttons[$buttonName]['actions'][$actionName]['error'] = sprintf( __('To enable this action, please install the Social Locker plugin which provides social features. <a href="%s" target="_blank">Click here to learn more</a>.', 'signinlocker'), $url );
                    }   
                }   
            }
            
        }   
        
        if ( BizPanda::isSinglePlugin() && BizPanda::hasPlugin('optinpanda') ) {
            
        } else {

            if ( !BizPanda::hasFeature('sociallocker-premium') ) {

                unset( $buttons['twitter']['actions']['follow']['on'] );
                $buttons['twitter']['actions']['follow']['error'] = opanda_get_premium_note( false,  'co-follow-action' );

                unset( $buttons['twitter']['actions']['tweet']['on'] );
                $buttons['twitter']['actions']['tweet']['error'] = opanda_get_premium_note( false,  'co-follow-action' ); 

                unset( $buttons['google']['actions']['youtube-subscribe']['on'] );
                $buttons['google']['actions']['youtube-subscribe']['error'] = opanda_get_premium_note( false, 'co-youtube-action' );  
            }
        }

        $buttons = apply_filters('opanda_connect_buttons_options', $buttons);
        $commonActions = array('subscribe', 'signup', 'lead');
        ?>
        <div class="opanda-connect-buttons factory-fontawesome-320">

            <?php foreach( $buttons as $name => $button ) { ?>
                <?php 
                    $errors = isset( $button['errors'] ) ? call_user_func( $button['errors'] ) : null;
                    $errorName = isset( $errors['name'] ) ? $errors['name'] : 'noname';

                    $errorIcon = isset( $errors['icon'] ) ? $errors['icon'] : 'fa-exclamation-triangle';
                    $errorText = isset( $errors['text'] ) ? $errors['text'] : $errors;         
                ?>
            
                <div class="opanda-button opanda-button-<?php echo $name ?> opanda-off <?php if ( $errors ) { echo 'opanda-has-error'; } ?>" data-name="<?php echo $name ?>">
                    <div class="opanda-inner-wrap">
                        <label class="opanda-button-title" for="opanda-button-<?php echo $name ?>-activated">
                            
                            <?php if ( $errors ) { ?>
                            <span class="opanda-error opanda-error-<?php echo $errorName ?>">
                                <i class="fa <?php echo $errorIcon ?>"></i>
                                <div class='opanda-error-text'><?php echo $errorText ?></div>
                            </span>
                            <?php } else { ?>
                            <span class='opanda-checkbox'>
                                <input type="checkbox" value="<?php echo $name ?>" id="opanda-button-<?php echo $name ?>-activated" />
                            </span>
                            <?php } ?>
  
                            <span><?php echo $button['title'] ?></span>

                        </label>
                        <ul class='opanda-actions'>
                            <?php foreach( $button['actions'] as $actionName => $actionData ) { ?>
                            <?php if ( isset( $button['actions'][$actionName]['on'] ) ) { ?>
                                <?php
                                    $actionTitle = $actionData['title'];
                                ?>
                                <?php $isCommon = in_array( $actionName, $commonActions ); ?>
                            
                                <li class='opanda-action opanda-action-<?php echo $actionName ?>'>
                                    <span>
                                        <input 
                                            type="checkbox" 
                                            value="1" 
                                            data-common="<?php echo ( $isCommon ? '1' : '0' ); ?>" 
                                            data-button="<?php echo $name ?>" 
                                            data-action="<?php echo $actionName ?>" 
                                            disabled="disabled" />
                                    </span>
                                    
                                    <a href="#" class="opanda-action-link" data-common="<?php echo ( $isCommon ? '1' : '0' ); ?>" data-button="<?php echo $name ?>" data-action="<?php echo $actionName ?>"><?php echo $actionTitle ?></a>
                                </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                        <ul class='opanda-actions opanda-actions-disabled'>
                            <?php foreach( $button['actions'] as $actionName => $actionData ) { ?>
                            <?php if ( !isset( $button['actions'][$actionName]['on'] ) ) { ?>
                            <?php
                                $actionTitle = $actionData['title'];
                            ?>
                            <?php $isCommon = in_array( $actionName, $commonActions ); ?>
                            <li class='opanda-action opanda-action-disabled opanda-action-<?php echo $actionName ?>'>
                                <span class="opanda-error-wrap">
                                    <input type="checkbox" disabled="disabled" value="1" />
                                    <span class='opanda-error-text'><?php echo $actionData['error'] ?></span>
                                    <a href="#" class="opanda-action-link" data-common="<?php echo ( $isCommon ? '1' : '0' ); ?>" data-button="<?php echo $name ?>" data-action="<?php echo $actionName ?>"><?php echo $actionTitle ?></a>  
                                </span>
                            </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        </div>  
        <?php
    }
    
    /**
     * Returns errors of the Facebook Connect button.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getFacebookErrors() {
        
        $appId = get_option('opanda_facebook_appid', null);
        if ( empty( $appId ) || '117100935120196' === $appId ) {
            return array(
                'text' => sprintf( __('You need to register a Facebook App for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'signinlocker'), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social') )
            );
        }
        
        return false;
    }
    
    /**
     * Returns errors of the Twitter Connect button.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getTwitterErrors() {
        
        $twitterAppMode = get_option('opanda_twitter_use_dev_keys', 'default');
        if ( 'default' === $twitterAppMode ) return false;
        
        $key = get_option('opanda_twitter_consumer_key');
        $secret = get_option('opanda_twitter_consumer_secret');
        
        if ( empty( $key ) || empty( $secret ) ) {
            return array(
                'text' => sprintf( __('Please set the Key & Secret of your Twitter App or select the default app. Please <a href="%s" target="_blank">click here</a> to learn more.', 'signinlocker'), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social') )
            );
        }
        
        return false;
    }
    
    /**
     * Returns errors of the Facebook Connect button.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getGoogleErrors() {
        
        $clientId = get_option('opanda_google_client_id', null);
        if ( empty( $clientId ) ) {
            return array(
                'text' => sprintf( __('You need to get a Google Client ID for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'signinlocker'), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social') )
            );
        }
        
        return false;
    }
    
    /**
     * Returns errors of the LinkedIn Connect button.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getLinkedInErrors() {
            
            return array(
                'name' => 'premium',
                'icon' => 'fa-lock',         
                'text' => opanda_get_premium_note( true, 'co-linkedin' )
            );
            
        

    }    
    
    /**
     * Returns errors for Email Form.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getEmailFormErrors() {
            return array(
                'name' => 'premium',
                'icon' => 'fa-lock',          
                'text' => opanda_get_premium_note( true, 'co-email' )
            );
        

    }   
    
    public function showSubscriptionService() {
        
        $info = OPanda_SubscriptionServices::getCurrentServiceInfo();
        $serviceName = ( empty( $info ) ) ? 'none' : $info['name'];

        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">

                <?php if ( 'database' === $serviceName ) { ?>
                    <?php printf( __('The emails will be saved in the <a href="%s" target="_blank">local database</a> because you haven\'t selected a mailing service', 'signinlocker'), opanda_get_subscribers_url() ) ?>
                <?php } else { ?>
                    <?php printf( __('You selected <strong>%s</strong> as your mailing service', 'signinlocker'), $info['title'] ) ?>
                <?php } ?>
  
                (<a href="<?php echo opanda_get_settings_url('subscription') ?>" target="_blank"><?php _e('change', 'signinlocker') ?></a>).
            </div>
        </div>
        <?php
    }
    
    /**
     * Removes the action 'lead' from options of the buttons because it's a virtual action.
     */
    public function onSavingForm( $postId ) {

        $buttons = array('facebook', 'twitter', 'google', 'linkedin', 'email');
        foreach( $buttons as $buttonName ) {
            
            $strActions = isset( $_POST['opanda_' . $buttonName . '_actions'] ) 
                            ? $_POST['opanda_' . $buttonName . '_actions'] 
                            : '';
            
            $rawActions = explode(',', $strActions);
            $filteredActions = array();
            
            foreach( $rawActions as $action ) {
                if ( 'lead' == $action ) continue;
                $filteredActions[] = $action;
            } 
            
            $_POST['opanda_' . $buttonName . '_actions'] = implode(',', $filteredActions);
        }
    }
}

FactoryMetaboxes321::register('OPanda_ConnectOptionsMetaBox', $bizpanda);
