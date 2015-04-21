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
        <p><?php _e('You can change primary front-end text in the settings of a particular locker. Here you can change the remaining text. It will be applied to all your lockers.', 'opanda') ?></p>
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
            'hint'      => __('Appears when the locker asks the user to confirm one\'s email.', 'opanda'),
            'items'     => array(
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_title',
                    'title'     => __('Header', 'opanda'),
                    'default'   => __('Please Confirm Your Email', 'opanda')
                ),
                array(
                    'type'      => 'textarea',
                    'name'      => 'res_confirm_screen_instructiont',
                    'title'     => __('Instruction', 'opanda'),
                    'hint'      => __('Explain here that the user has to do to confirm one\'s email. Use the tag {email} to display an email address of the user.', 'opanda'),
                    'default'   => __('We have sent a confirmation email to {email}. Please click on the confirmation link in the email to reveal the content.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_note1',
                    'title'     => __('Note #1', 'opanda'),
                    'hint'      => __('Clarify when the content will be unlocked.', 'opanda'),
                    'default'   => __('The content will be unlocked automatically within 10 seconds after confirmation.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_note2',
                    'title'     => __('Note #2', 'opanda'),
                    'hint'      => __('Clarify that delivering the confirmation email may take some time.', 'opanda'),
                    'default'   => __('Note delivering the email may take several minutes.', 'opanda')
                ),    
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_cancel',
                    'title'     => __('Cancel Link', 'opanda'),
                    'default'   => __('(cancel)', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_confirm_screen_open',
                    'title'     => __('Open My Inbox Button', 'opanda'),
                    'default'   => __('Open my inbox on {service}', 'opanda'),
                    'hint'      => __('Use the tag {service} to display a name of a mailbox of the user.', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                )
            )
        ); 
        
        $onestepScreenOptions = array(
            'type'      => 'form-group',
            'title'     => 'The Screen "One Step To Complete"',
            'hint'      => __('Appears when a social network does not return an email address and the locker asks the users to enter it manually.', 'opanda'),
            'items'     => array(
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_onestep_screen_title',
                    'title'     => __('Header', 'opanda'),
                    'default'   => __('One Step To Complete', 'opanda')
                ),
                array(
                    'type'      => 'textarea',
                    'name'      => 'res_onestep_screen_instructiont',
                    'title'     => __('Instruction', 'opanda'),
                    'default'   => __('Please enter your email below to continue.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_onestep_screen_button',
                    'title'     => __('Button', 'opanda'),
                    'default'   => __('OK, complete', 'opanda')
                )
            )
        );
        
        $signinOptions = array(
            'type'      => 'form-group',
            'title'     => __( 'Sign-In Buttons', 'opanda' ),
            'hint'      => __('The text which are located on the Sign-In Buttons.', 'opanda'),
            'items'     => array(
                
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_long',
                    'title'     => __('Long Text', 'opanda'),
                    'hint'      => __('Displayed on a wide Sign-In Button', 'opanda'),
                    'default'   => __('Sign in via {name}', 'opanda'),
                    'cssClass'  => 'opanda-width-short',
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_short',
                    'title'     => __('Short Text', 'opanda'),
                    'hint'      => __('Displayed on a narrow Sign-In Button', 'opanda'),
                    'default'   => __('via {name}', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_facebook_name',
                    'title'     => __('Facebook', 'opanda'),
                    'default'   => __('Facebook', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_twitter_name',
                    'title'     => __('Twitter', 'opanda'),
                    'default'   => __('Twitter', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_google_name',
                    'title'     => __('Google', 'opanda'),
                    'default'   => __('Google', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_signin_linkedin_name',
                    'title'     => __('LinkedIn', 'opanda'),
                    'default'   => __('LinkedIn', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                )
            )
        );
        
        $miscOptions = array(
            'type'      => 'form-group',
            'title'     => 'Miscellaneous',
            'hint'      => __('Various text used usually with all lockers and screens.', 'opanda'),
            'items'     => array(
                
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_data_processing',
                    'title'     => __('Processing Data', 'opanda'),
                    'default'   => __('Processing data, please wait...', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or_enter_email',
                    'title'     => __('Enter Your Email Manually', 'opanda'),
                    'default'   => __('or enter your email manually to sign in', 'opanda')
                ),
                array(
                    'type'      => 'separator'
                ), 
                'res_misc_enter_your_name' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_enter_your_name',
                    'title'     => __('Enter Your Name', 'opanda'),
                    'default'   => __('enter your name', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_enter_your_email',
                    'title'     => __('Enter Your Email Address', 'opanda'),
                    'default'   => __('enter your email address', 'opanda')
                ),
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_your_agree_with',
                    'title'     => __('You Agree With', 'opanda'),
                    'hint'      => __('Use the tag {links} to display the links to the Terms Of Use and Privacy Policy.', 'opanda'),
                    'default'   => __('By clicking on the button(s), you agree with {links}', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_terms_of_use',
                    'title'     => __('Terms Of Use', 'opanda'),
                    'default'   => __('Terms of Use', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_privacy_policy',
                    'title'     => __('Privacy Policy', 'opanda'),
                    'default'   => __('Privacy Policy', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'separator'
                ), 
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or_wait',
                    'title'     => __('Or Wait', 'opanda'),
                    'default'   => __('or wait {timer}s', 'opanda'),
                    'hint'      => __('Use the tag {timer} to display the number of seconds remaining to unlocking.'),
                    'cssClass'  => 'opanda-width-short'
                ),
                
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_close',
                    'title'     => __('Close', 'opanda'),
                    'default'   => __('close', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_misc_or',
                    'title'     => __('Or', 'opanda'),
                    'default'   => __('OR', 'opanda'),
                    'cssClass'  => 'opanda-width-short'
                ),
            )
        );
        
        if ( !BizPanda::hasPlugin('optinpanda') ) {
            unset( $miscOptions['items']['res_misc_enter_your_name'] );
        }

        $errosOptions = array(
            'type'      => 'form-group',
            'title'     => __('Errors & Notices','opanda'),
            'hint'      => __('The text which users see when something goes wrong.', 'opanda'),
            'items'     => array(
               
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_empty_email',
                    'title'     => __('Empty Email', 'opanda'),
                    'default'   => __('Please enter your email address.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_inorrect_email',
                    'title'     => __('Incorrect Email', 'opanda'),
                    'default'   => __('It seems you entered an incorrect email address. Please check it.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_empty_name',
                    'title'     => __('Empty Name', 'opanda'),
                    'default'   => __('Please enter your name.', 'opanda')
                ),
                array(
                    'type'      => 'separator'
                ),
                'res_errors_subscription_canceled' => array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_subscription_canceled',
                    'title'     => __('Subscription Canceled', 'opanda'),
                    'default'   => __('You have canceled your subscription.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_not_signed_in',
                    'title'     => __('Not Signed In', 'opanda'),
                    'default'   => __('Sorry, but you have not signed in. Please try again.', 'opanda')
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'res_errors_not_granted',
                    'title'     => __('Not Granted Permissions', 'opanda'),
                    'hint'      => __('Use the tag {permissions} to show required permissions.'),
                    'default'   => __('Sorry, but you have not granted all the required permissions ({permissions}). Please try again.', 'opanda')
                )
            )
        );
        
        if ( !BizPanda::hasPlugin('optinpanda') ) {
            unset( $errosOptions['items']['res_errors_subscription_canceled'] );
        }
        
        $options = array();
        
        if ( BizPanda::hasPlugin('optinpanda') ) {
            $options[] = $confirmScreenOptions;
            $options[] = $signinOptions;
            $options[] = $miscOptions;
        } else {
            $options[] = $signinOptions;
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

