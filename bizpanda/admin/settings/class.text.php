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
class OPanda_TextSettings extends OPanda_Settings  {
 
    public $id = 'text';
    
    /**
     * Shows the header html of the settings screen.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {
        ?>
        <p><?php _e('You can change primary front-end text in the settings of a particular locker. Here you can change the remaining text. It will be applied to all your lockers.', 'bizpanda') ?></p>
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
        
        $pages = get_pages();
        $result = array();
        
        $result[] = array('0', '- none -');
        foreach( $pages as $page ) {
            $result[] = array($page->ID, $page->post_title . ' [ID=' . $page->ID . ']');
        }
        
        $confirmScreenOptions = array(
            'type'      => 'form-group',
            'title'     => 'The Screen "Please Confirm Your Email"',
            'hint'      => __('Appears when the locker asks the user to confirm one\'s email.', 'bizpanda'),
            'items'     => array(
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_title',
                    'title'     => __('Header', 'bizpanda'),
                    'default'   => __('Please Confirm Your Email', 'bizpanda')
                ),
                array(
                    'type'      => 'textarea',
                    'name'      => 'res_confirm_screen_instructiont',
                    'title'     => __('Instruction', 'bizpanda'),
                    'hint'      => __('Explain here that the user has to do to confirm one\'s email. Use the tag {email} to display an email address of the user.', 'bizpanda'),
                    'default'   => __('We have sent a confirmation email to {email}. Please click on the confirmation link in the email to reveal the content.', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_note1',
                    'title'     => __('Note #1', 'bizpanda'),
                    'hint'      => __('Clarify when the content will be unlocked.', 'bizpanda'),
                    'default'   => __('The content will be unlocked automatically within 10 seconds after confirmation.', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_note2',
                    'title'     => __('Note #2', 'bizpanda'),
                    'hint'      => __('Clarify that delivering the confirmation email may take some time.', 'bizpanda'),
                    'default'   => __('Note delivering the email may take several minutes.', 'bizpanda')
                ),    
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_cancel',
                    'title'     => __('Cancel Link', 'bizpanda'),
                    'default'   => __('(cancel)', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_open',
                    'title'     => __('Open My Inbox Button', 'bizpanda'),
                    'default'   => __('Open my inbox on {service}', 'bizpanda'),
                    'hint'      => __('Use the tag {service} to display a name of a mailbox of the user.', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                )
            )
        ); 
        
        $onestepScreenOptions = array(
            'type'      => 'form-group',
            'title'     => 'The Screen "One Step To Complete"',
            'hint'      => __('Appears when a social network does not return an email address and the locker asks the users to enter it manually.', 'bizpanda'),
            'items'     => array(
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_onestep_screen_title',
                    'title'     => __('Header', 'bizpanda'),
                    'default'   => __('One Step To Complete', 'bizpanda')
                ),
                array(
                    'type'      => 'textarea',
                    'name'      => 'res_onestep_screen_instructiont',
                    'title'     => __('Instruction', 'bizpanda'),
                    'default'   => __('Please enter your email below to continue.', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_onestep_screen_button',
                    'title'     => __('Button', 'bizpanda'),
                    'default'   => __('OK, complete', 'bizpanda')
                )
            )
        );
        
        $signinOptions = array();
        if ( BizPanda::hasFeature('signin-locker')) {
        
            $signinOptions = array(
                'type'      => 'form-group',
                'title'     => __( 'Sign-In Buttons', 'bizpanda' ),
                'hint'      => __('The text which are located on the Sign-In Buttons.', 'bizpanda'),
                'items'     => array(

                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_long',
                        'title'     => __('Long Text', 'bizpanda'),
                        'hint'      => __('Displayed on a wide Sign-In Button', 'bizpanda'),
                        'default'   => __('Sign in via {name}', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short',
                    ),
                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_short',
                        'title'     => __('Short Text', 'bizpanda'),
                        'hint'      => __('Displayed on a narrow Sign-In Button', 'bizpanda'),
                        'default'   => __('via {name}', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short'
                    ),
                    array(
                        'type'      => 'separator'
                    ), 
                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_facebook_name',
                        'title'     => __('Facebook', 'bizpanda'),
                        'default'   => __('Facebook', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short'
                    ),
                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_twitter_name',
                        'title'     => __('Twitter', 'bizpanda'),
                        'default'   => __('Twitter', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short'
                    ),
                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_google_name',
                        'title'     => __('Google', 'bizpanda'),
                        'default'   => __('Google', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short'
                    ),
                    array(
                        'type'      => 'textbox',
                        'name'      => 'res_signin_linkedin_name',
                        'title'     => __('LinkedIn', 'bizpanda'),
                        'default'   => __('LinkedIn', 'bizpanda'),
                        'cssClass'  => 'opanda-width-short'
                    )
                )
            );
         
        }

        $miscOptions = array(
            'type'      => 'form-group',
            'title'     => 'Miscellaneous',
            'hint'      => __('Various text used usually with all lockers and screens.', 'bizpanda'),
            'items'     => array(
                
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_data_processing',
                    'title'     => __('Processing Data', 'bizpanda'),
                    'default'   => __('Processing data, please wait...', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or_enter_email',
                    'title'     => __('Enter Your Email Manually', 'bizpanda'),
                    'default'   => __('or enter your email manually to sign in', 'bizpanda')
                ),
                array(
                    'type'      => 'separator'
                ), 
                'res_misc_enter_your_name' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_enter_your_name',
                    'title'     => __('Enter Your Name', 'bizpanda'),
                    'default'   => __('enter your name', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_enter_your_email',
                    'title'     => __('Enter Your Email Address', 'bizpanda'),
                    'default'   => __('enter your email address', 'bizpanda')
                ),
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_your_agree_with',
                    'title'     => __('You Agree With', 'bizpanda'),
                    'hint'      => __('Use the tag {links} to display the links to the Terms Of Use and Privacy Policy.', 'bizpanda'),
                    'default'   => __('By clicking on the button(s), you agree with {links}', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_terms_of_use',
                    'title'     => __('Terms Of Use', 'bizpanda'),
                    'default'   => __('Terms of Use', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_privacy_policy',
                    'title'     => __('Privacy Policy', 'bizpanda'),
                    'default'   => __('Privacy Policy', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or_wait',
                    'title'     => __('Or Wait', 'bizpanda'),
                    'default'   => __('or wait {timer}s', 'bizpanda'),
                    'hint'      => __('Use the tag {timer} to display the number of seconds remaining to unlocking.'),
                    'cssClass'  => 'opanda-width-short'
                ),
                
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_close',
                    'title'     => __('Close', 'bizpanda'),
                    'default'   => __('close', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or',
                    'title'     => __('Or', 'bizpanda'),
                    'default'   => __('OR', 'bizpanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
            )
        );
        
        if ( !BizPanda::hasPlugin('optinpanda') ) {
            unset( $miscOptions['items']['res_misc_enter_your_name'] );
        }

        $errosOptions = array(
            'type'      => 'form-group',
            'title'     => __('Errors & Notices', 'bizpanda'),
            'hint'      => __('The text which users see when something goes wrong.', 'bizpanda'),
            'items'     => array(
               
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_empty_email',
                    'title'     => __('Empty Email', 'bizpanda'),
                    'default'   => __('Please enter your email address.', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_inorrect_email',
                    'title'     => __('Incorrect Email', 'bizpanda'),
                    'default'   => __('It seems you entered an incorrect email address. Please check it.', 'bizpanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_empty_name',
                    'title'     => __('Empty Name', 'bizpanda'),
                    'default'   => __('Please enter your name.', 'bizpanda')
                ),
                array(
                    'type'      => 'separator'
                ),
                'res_errors_subscription_canceled' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_subscription_canceled',
                    'title'     => __('Subscription Canceled', 'bizpanda'),
                    'default'   => __('You have canceled your subscription.', 'bizpanda')
                ),
                'res_errors_not_signed_in' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_not_signed_in',
                    'title'     => __('Not Signed In', 'bizpanda'),
                    'default'   => __('Sorry, but you have not signed in. Please try again.', 'bizpanda')
                ),
                'res_errors_not_granted' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_not_granted',
                    'title'     => __('Not Granted Permissions', 'bizpanda'),
                    'hint'      => __('Use the tag {permissions} to show required permissions.'),
                    'default'   => __('Sorry, but you have not granted all the required permissions ({permissions}). Please try again.', 'bizpanda')
                )
            )
        );
        
        if ( !BizPanda::hasFeature('signin-locker')) {
            unset( $errosOptions['items']['res_errors_not_signed_in'] );
            unset( $errosOptions['items']['res_errors_not_granted'] );
        }
        
        if ( !BizPanda::hasPlugin('optinpanda') ) {
            unset( $errosOptions['items']['res_errors_subscription_canceled'] );
        }
        
        $options = array();
        
        if ( BizPanda::hasPlugin('optinpanda') ) {
            $options[] = $confirmScreenOptions;
            if ( !empty( $signinOptions ) ) $options[] = $signinOptions;
            $options[] = $miscOptions;
        } else {
            if ( !empty( $signinOptions ) ) $options[] = $signinOptions;
            $options[] = $miscOptions; 
        }
        
        $options[] = $onestepScreenOptions;
        $options[] = $errosOptions; 

        $options[] = array(
            'type' => 'separator'
        );
        
        return $options;
    }
}

