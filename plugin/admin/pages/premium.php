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
class OnpSL_PremiumPage extends FactoryPages321_AdminPage  {
 
    public $menuPostType = OPANDA_POST_TYPE;
    public $id = "premium";
    
    public function __construct(Factory325_Plugin $plugin) {   
        parent::__construct($plugin);
        add_filter( 'factory_menu_title_premium-sociallocker-next' , array( $this, 'fixMenuTitle') ) ;
    }
    
    public function fixMenuTitle() {
        if ( BizPanda::isSinglePlugin() ) return __('Go Premium', 'optinpanda');
        return __('<span class="factory-fontawesome-320"><i class="fa fa-star-o" style="margin-right: 5px;"></i>Social Locker</span>', 'optinpanda');
    }
    
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/premium.030100.css');   
        $this->styles->request('bootstrap.core', 'bootstrap');
    }

    /**
     * Shows 'Get more features!'
     * 
     * @sinve 1.0.0
     * @return void
     * 
     */
    public function indexAction() {
        global $sociallocker;
        
        $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);
        
        $skipTrial = get_option('onp_sl_skip_trial', false);
        if ( $skipTrial ) {
            wp_redirect( onp_sl_get_premium_url('go-premium') );
            exit;
        }
        
        ?>
        <div class="wrap factory-bootstrap-329 factory-fontawesome-320">
            <div class="onp-page-content">
                <div class="onp-inner-wrap">
                    
        <div class="onp-page-section">
            
            <?php if ( !$alreadyActivated ) { ?>
                <h1><?php _e('Try Premium Version For 7 Days For Free!', 'plugin-sociallocker'); ?></h1>
            <?php } else { ?>
                <h1><?php _e('Upgrade Social Locker To Premium!', 'plugin-sociallocker'); ?></h1>     
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>  
            <p>
                <?php printf( __('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Social Locker</a> plugin. 
                We offer you to try the premium version for 7 days absolutely for free. We sure you will love it.', 'plugin-sociallocker'), onp_sl_get_premium_url('go-premium') ) ?>
            </p>
            <p>
                <?php _e('Check out the table below to know about the premium features.', 'plugin-sociallocker'); ?>
            </p>
            <?php } else { ?>
            <p>
                <?php _e('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Social Locker plugin</a> sold on CodeCanyon.', 'plugin-sociallocker') ?>
                <?php _e('Check out the table below to know about all the premium features.', 'plugin-sociallocker'); ?>
            </p>   
            <?php } ?>

        </div>

        <div class="onp-page-section">
            <h2><i class="fa fa-star-o"></i> Comparison of Free & Premium Versions</h2>
            <p>Click on the dotted title to learn more about a given feature.</p>
            <table class="table table-bordered onp-how-comparation">
                <tbody>
                    
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-cogs"></i> Common Features</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Unlimited Lockers</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Manual Lock (via shortcodes)</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>  
                    <tr>
                        <td class="onp-how-title">Batch Lock (3 modes)</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#extra-options">Visibility Options</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>    
                        <td class="onp-how-title"><a href="#extra-options">Advanced Options</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>   
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-bullhorn"></i> Social Locker</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title">Facebook Like</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Twitter Tweet</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Google +1</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Facebook Share</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>    
                    <tr>
                        <td class="onp-how-title">Twitter Follow</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">LinkedIn Share</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>    
                    </tr>
                    <tr>
                        <td class="onp-how-title">Google Share</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">YouTube Subscribe</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-user"></i> Sign-In Locker</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title">Facebook Sign-In</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Twitter Sign-In</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Google Sign-In</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">LinkedIn Sign-In</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>    
                    </tr>    
                    <tr>
                        <td class="onp-how-title">Sign-In via Email</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Action "Twitter Follow"</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Action "LinkedIn Follow"</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>
                    <tr>
                        <td class="onp-how-title">Action "Subscribe to Youtube"</td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><strong>Export Leads In CSV</strong></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-adjust"></i> Overlap Modes</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title">Full</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Transparency</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#blurring">Blurring (new!)</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>   
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-picture-o"></i> Themes</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title onp-how-group-in-group">The 'Secrets' Theme</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">Theme 'Flat' (new!)</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">Theme 'Dandyish' </a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>          
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">Theme 'Glass'</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">Theme 'Friendly Giant'</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>          
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">Theme 'Dark Force'</a></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>    
                    </tr>
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-picture-o"></i> Services</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium">Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#updates">Updates</a></td>
                        <td class="onp-how-no">not guaranteed</td>
                        <td class="onp-how-yes onp-how-premium"><strong>primary updates</strong></td>   
                    </tr>      
                    <tr>
                        <td class="onp-how-title"><a href="#support">Support</a></td>
                        <td class="onp-how-no">not guaranteed</td>
                        <td class="onp-how-yes onp-how-premium"><strong>dedicated support</strong></td>   
                    </tr>  
                </tbody>
            </table>
            
            <?php if ( !$alreadyActivated ) { ?>
            
            <div>
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by one click)</small>
                </a>
            </div>
            
            <?php } else { ?>
            
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_sl_get_premium_url( 'go-premium' ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Social Locker Premium For $25 Only
                    <i class="fa fa-star"></i>
                </a>
            </div>
            
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>

            <p style="text-align: center; margin-top: 20px;">
                <a href="<?php echo onp_sl_get_premium_url( 'go-premium' ) ?>" style="color: #111;"><strong>Or Buy The Social Locker Right Now For $25 Only</strong></a>
            </p>

            <?php } ?>
            
        </div>
                    
        <div class="onp-page-section" id="social-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Drive More Traffic & Build Quality Followers', 'plugin-sociallocker'); ?>
            </h1>
            <p><?php _e('The premium version of the plugin provides 8 social buttons for all major social networks: Facebook, Twitter, Google, LinkedIn, YouTube, including the Twitter Follow button. You can use them together or separately for customized results.', 'plugin-sociallocker') ?></p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/social-options-a.png' />
            </p>
        </div> 

        <div class="onp-page-section" id="extra-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Set How, When and For Whom Your Lockers Appear', 'plugin-sociallocker'); ?>
            </h1>
            
            <p>Each website has its own unique audience. We know that a good business is an agile business. The premium version of Social Locker provides 8 additional options that allow you to configure the lockers flexibly to meet your needs.</p>

            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/advanced-options.png' />
            </p>
            <div class="clearfix"></div>
        </div> 

        <div class="onp-page-section" id='blurring'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Create Highly Shareable Content Via The Blur Effect', 'plugin-sociallocker'); ?>
            </h1>
            <p>The previous versions of the plugin allowed only to hide the locked content totally. But recently we have added the long-awaited option to overlap content and make it transparent or blurred.</p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/blur-effect.png' />
            </p>
            <p>When we tested this feature on sites of some our customers, we were blown away how this feature attracts attention of the huge number of visitors. If people see and understand that they will get after unlocking, the plugin works more effectively.</p>
        </div> 

        <div class="onp-page-section" id='extra-themes'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('5 Extra Stunning Themes For Your Lockers', 'plugin-sociallocker'); ?>
            </h1>
            <p>
                <p>The premium version of Social Locker comes with 5 extra impressive, polished styles which create interest and attract attention (3 for the classic Social Locker and 2 for the Sign-In Locker). They are nicely animated and don't look obtrusive:</p>
            </p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/new-themes.png' />
            </p>
        </div> 

        <div class="onp-page-section" id='updates'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Get New Features & Updates Almost Every Week', 'plugin-sociallocker'); ?>
            </h1>
            <p>We release about 3-4 updates each month, adding new features and fixing bugs. The Free version does not guarantee that you will get all the major updates. But if you upgrade to the Premium version, your copy of the plugin will be always up-to-date.</p>
        </div> 

        <div class="onp-page-section" id='support'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Guaranteed Support Within 24h', 'plugin-sociallocker'); ?>
            </h1>
            <p>
                All of our plugins come with free support. We care about your plugin after purchase just as much as you do. We want to make your life easier and make you happy about choosing our plugins.
            </p>
            <p>
                Unfortunately we receive plenty of support requests every day and we cannot answer to all the users quickly. But for the users of the premium version (and the trial version), we guarantee to respond to every inquiry within 1 business day (typical response time is 3 hours).
            </p>
        </div> 

        <?php if ( !$alreadyActivated ) { ?>

        <div class="onp-page-section">
            <div>
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by one click)</small>
                </a>
            </div>
        </div> 

        <div class="onp-page-section">
            <p style="text-align: center;">
                <a href="<?php echo onp_sl_get_premium_url( 'go-premium' ) ?>" style="color: #111;"><strong>Or Buy The Social Locker Right Now For $25 Only</strong></a>
            </p>
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p><?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'plugin-sociallocker'); ?></p>
                    <p><?php printf(__('<strong>To purchase the Social Locker</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'plugin-sociallocker'), onp_sl_get_premium_url( 'go-premium' )); ?></p>
                </div>
            </div>
        </div> 

        <?php } else { ?>
        <div class="onp-page-section">
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_sl_get_premium_url( 'go-premium' ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Social Locker Premium For $25 Only
                    <i class="fa fa-star"></i>
                </a>
            </div>
        </div> 
        <?php } ?>
                    
                </div>
            </div>    
        </div> 
        <?php
    }
}

FactoryPages321::register($sociallocker, 'OnpSL_PremiumPage');
