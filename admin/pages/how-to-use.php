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
class OnpSL_HowToUsePage extends FactoryPages320_AdminPage  {
 
    public $menuTitle = 'How to use?';
    public $menuPostType = 'social-locker';
    
    public $id = "how-to-use";
    
    public function __construct(Factory324_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('How to use?', 'sociallocker');
    }
  
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/howtouse.030100.css');   
        $this->styles->request('bootstrap.core', 'bootstrap');
    }
    
    protected $_pages = false;
    
    protected function getPages() {
        if ( $this->_pages !== false ) return $this->_pages;
            
            $items = array(
                array(
                    'name' => 'getting-started',
                    'function' => array( $this, 'gettingStarted'),
                    'title' => __('Getting Started', 'sociallocker')
                ),
                array(
                    'name' => 'reviews',
                    'function' => array( $this, 'reviews'),
                    'title' => __('Have a review of our plugin?', 'sociallocker')
                ),
                array(
                    'name' => 'troubleshooting',
                    'function' => array( $this, 'troubleshooting'),
                    'title' => __('Troubleshooting', 'sociallocker')
                )
            );
                $items[] = array(
                    'name' => 'premium',
                    'function' => array( $this, 'premium'),
                    'title' => '<i class="fa fa-star-o"></i> ' . __('Premium Version', 'sociallocker') . ' <i class="fa fa-star-o"></i>'
                );
            

            
        

        
        
        
        $this->_pages = apply_filters( 'onp_sl_help_pages', $items );
        return $this->_pages;
    }
    
    protected function showNav() {
        $pages = $this->getPages();
        
        ?>
        <div class="onp-help-nav">
            <ul>
            <?php foreach( $pages as $page ) { ?>
                <?php if ( isset( $page['url'] ) ) { ?>
                <li><a href='<?php echo onp_licensing_324_get_purchase_url( $this->plugin ) ?>' target="_blank"><?php echo $page['title'] ?></a></li>
                <?php } else { ?>
                <li><a href='<?php $this->actionUrl('index', array('onp_sl_page' => $page['name'])) ?>'><?php echo $page['title'] ?></a></li>
                <?php } ?>
            <?php } ?>
            </ul>
        </div>
        <?php
    }
    
    /**
     * Shows one of the help pages.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        $currentPage = isset( $_GET['onp_sl_page'] ) ? $_GET['onp_sl_page'] : 'index';
        $pages = $this->getPages();
        
        $foundItem = false;
        foreach( $pages as $item ) {
            if ( $item['name'] == $currentPage ) {
                $foundItem = $item;
                break;
            }
        }
        
        ?>
        
        <div class="wrap factory-bootstrap-325 factory-fontawesome-320">
            <?php $this->showNav('getting-started') ?>
            <div class="onp-help-content">
                
            <?php
            if ( empty( $foundItem ) ) {
                $this->gettingStarted();
                return;
            }
            call_user_func( $foundItem['function'] );
            ?>
                
            </div>    
        </div> 
        <?php  
        return;
    }
    
    /**
     * Page 'Gettings Started'
     * 
     * @since 3.4.6
     */
    public function gettingStarted() {
        
        
        $lang = $this->plugin->options['lang'];
            
        ?>

            <div class="onp-help-section">
                <h1><?php _e('Getting Started', 'sociallocker'); ?></h1>

                <p>
                    <?php _e('The Social Locker plugin allows you to use shortcodes in order to lock content. During installation, the plugin created for you the shortcode [sociallocker] named "Default Locker".', 'sociallocker'); ?>
                </p>
                <p class='onp-remark'>
                    <span class="onp-inner-wrap">
                    <?php _e('You can create shortcodes for whatever you need them for. For instance, you could create one for locking video players or another one for locking download links. Each shortcode has its own settings. ', 'sociallocker'); ?>
                    </span>
                </p>
                <p>
                    <?php _e('Let\'s examine how to use the Default Locker.', 'sociallocker'); ?>
                </p>
            </div>

            <div class="onp-help-section">
                <h2>1. <?php _e('Open the editor', 'sociallocker'); ?></h2>

                <p><?php _e('Move to Social Locker -> All Social Locker in the admin menu:', 'sociallocker'); ?></p>
                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/' . $lang . '/1.png' ?>' />
                </p>
                <p><?php _e('Click on the shortcode titled “Default Locker” to open the locker editor:', 'sociallocker'); ?></p>
                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/' . $lang . '/2.png' ?>' />
                </p>
            </div>

            <div class="onp-help-section">
                <h2>2. <?php _e('Configure the locker', 'sociallocker'); ?></h2>

                <p>1) <?php _e('Set the URL that will receive likes/tweets/plus ones or leave the field empty.', 'sociallocker'); ?></p>         
                <p>2) <?php _e('Set a clear title that attracts attention or creates a call to action (see the example below).', 'sociallocker'); ?></p>
                <p>3) <?php _e('Describe what the visitor will get after they unlock the content. This is very important, as visitors need to be aware of what they are getting. And please, only promise things you can deliver.', 'sociallocker'); ?></p> 
                </p>

                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/' . $lang . '/3-free.png' ?>' />
                </p>

                <p>
                    <?php _e('Congratulations! The locker is ready to use.', 'sociallocker'); ?>
                </p>
            </div>  

            <div class="onp-help-section">
                <h2>3. <?php _e('Place the locker shortcode', 'sociallocker'); ?></h2>

                <p>
                    <?php _e('Decide what content you would like to lock. It might be:', 'sociallocker'); ?>
                    <ul>
                        <li><?php _e('A download link (for instance, a free graphic, an audio file, video resources, or a printable pdf of your article).', 'sociallocker'); ?></li>
                        <li><?php _e('A promo code (for instance, a 10% off discount, if the visitor shares your promo page).', 'sociallocker'); ?></li>
                        <li><?php _e('The end of your article (for instance, you might show the beginning of the article to gain interest, but hide the ending).', 'sociallocker'); ?></li>                 
                    </ul>
                    <?php _e('Basically, you can hide any content that would be important for visitors who are visiting your site.', 'sociallocker'); ?>
                </p>

                <p>
                    <?php _e('However, <strong>you should never</strong>:', 'sociallocker'); ?>
                    <ul>
                        <li>
                            <?php _e('Lock all of your content, posts or pages.', 'sociallocker'); ?>
                        </li>
                        <li>
                            <?php _e('Lock boring content or content that is not interesting.', 'sociallocker'); ?>
                        </li>
                    </ul>
                </p>
                <p>
                    <?php _e('In other words, don’t try to trick your visitors. If you do, people will become annoyed and will remove the likes/tweets/+1s after unlocking your content, which will not have the desired result.', 'sociallocker'); ?>
                </p>

                <p>
                    <?php _e('Open the post editor for the post where you want to put the locker. Then wrap the content you want to lock within the locker shortcode. For instance: [sociallocker] Locked Content Goes Here [/sociallocker]:', 'sociallocker'); ?>
                </p>

                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/' . $lang . '/5.png' ?>' />
                </p>

                <p>
                    <?php _e('That’s it! Save your post and see it on your site! ', 'sociallocker'); ?>
                </p>

                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/' . $lang . '/6.png' ?>' />
                </p>
            </div>    
    
        <?php
            
        

    }
    
    /**
     * Shows 'Have a plugin review?'
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function reviews() {
        ?>
        <div class="onp-help-section">
            <h1><?php _e('Additional Resources', 'sociallocker'); ?></h1>

            <p><?php _e('If you have a website and you have written a review for the Social Locker plugin or an article on how to use it, please let us know.', 'sociallocker'); ?></p>
            <p><?php _e('We\'re collecting sites that host reviews and information about the Social Locker. We hope that users will take advantage of these additional resources to improve their experience with Social Locker.', 'sociallocker'); ?></p>  

            <p><?php _e('Please send your reviews to <strong>support@byonepress.com</strong>', 'sociallocker'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Shows 'Troubleshooting'
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function troubleshooting() {
        ?>
        <div class="onp-help-section">
            <h1><?php _e('Troubleshooting', 'sociallocker'); ?></h1>

            <p><?php _e('If you have any questions or faced with any troubles while using our plugin, please check our <a href="http://support.onepress-media.com/" target="_blank">knowledge base</a>. It is possible that instructions for resolving your issue have already been posted.', 'sociallocker'); ?></p>  
            <p>
                <?php _e('If the answer to your question isn’t listed, please submit a ticket <a href="http://support.onepress-media.com/create-ticket/" target="_blank">here</a>.<br />You can also email us directly <strong>support@byonepress.com</strong>', 'sociallocker'); ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Shows 'Get more features!'
     * 
     * @sinve 1.0.0
     * @return void
     * 
     */
    public function premium() {
        global $sociallocker;
        $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);

        ?>
        <div class="onp-help-section">
            
            <?php if ( !$alreadyActivated ) { ?>
                <h1><?php _e('Try Premium Version For 7 Days For Free!', 'sociallocker'); ?></h1>
            <?php } else { ?>
                <h1><?php _e('Upgrade Social Locker To Premium!', 'sociallocker'); ?></h1>     
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>  
            <p>
                <?php printf( __('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Social Locker</a> plugin. 
                We offer you to try the premium version for 7 days absolutely for free. We sure you will love it.', 'sociallocker'), onp_licensing_324_get_purchase_url( $this->plugin ) ) ?>
            </p>
            <p>
                <?php _e('Check out the table below to know about the premium features.', 'sociallocker'); ?>
            </p>
            <?php } else { ?>
            <p>
                <?php _e('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Social Locker plugin</a> sold on CodeCanyon.', 'sociallocker') ?>
                <?php _e('Check out the table below to know about all the premium features.', 'sociallocker'); ?>
            </p>   
            <?php } ?>

        </div>

        <div class="onp-help-section">
            <h2><i class="fa fa-star-o"></i> Comparison of Free & Premium Versions</h2>
            <p>Click on the dotted title to learn more about a given feature.</p>
            <table class="table table-bordered onp-how-comparation">
                <thead>
                    <tr>
                        <th></th>
                        <th>Free</th>
                        <th class="onp-how-premium">Premium</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="onp-how-title">Unlimited Lockers</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Locking via shortcodes</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>  
                    <tr>
                        <td class="onp-how-title">Batch Locks</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#social-options">Individual settings for each button</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#extra-options">Visibility Options</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>    
                        <td class="onp-how-title"><a href="#extra-options">Advanced Options</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>      
                    <tr>
                        <td class="onp-how-group-title"><i class="fa fa-bullhorn"></i> Social Buttons</td>
                        <td class="onp-how-yes"></td>
                        <td class="onp-how-yes onp-how-premium"></td  
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
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>    
                    <tr>
                        <td class="onp-how-title">Twitter Follow</td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">LinkedIn Share</td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Google Share</td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>  
                    <tr>
                        <td class="onp-how-group-title"><i class="fa fa-adjust"></i> Overlap Modes</td>
                        <td class="onp-how-yes"></td>
                        <td class="onp-how-yes onp-how-premium"></td>   
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
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>   
                    <tr>
                        <td class="onp-how-group-title"><i class="fa fa-picture-o"></i> Themes</td>
                        <td class="onp-how-yes"></td>
                        <td class="onp-how-yes onp-how-premium"></td
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group">The 'Secrets' Theme</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">The 'Flat' Theme (new!)</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">The 'Dandyish' Theme</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>          
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#extra-themes">The 'Glass' Theme</a></td>
                        <td class="onp-how-no">no</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-group-title"><i class="fa fa-clock-o"></i> Services</td>
                        <td class="onp-how-yes"></td>
                        <td class="onp-how-yes onp-how-premium"></td
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
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_324_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by a click)</small>
                </a>
            </div>
            
            <?php } else { ?>
            
            <div class='factory-bootstrap-325'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_licensing_324_get_purchase_url( $this->plugin ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Social Locker Premium For $24
                    <i class="fa fa-star"></i>
                </a>
            </div>
            
            <?php } ?>
        </div>

        <?php if ( !$alreadyActivated ) { ?>

        <div class="onp-help-section">
            <p style="text-align: center;">
                <a href="<?php echo onp_licensing_324_get_purchase_url( $this->plugin ) ?>"><strong>Or Buy The Social Locker Right Now For $24</strong></a>
            </p>
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p><?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'sociallocker'); ?></p>
                    <p><?php printf(__('<strong>To purchase the Social Locker</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'sociallocker'), onp_licensing_324_get_purchase_url( $this->plugin )); ?></p>
                </div>
            </div>
        </div> 

        <?php } ?>

        <div class="onp-help-section">
            <p>Upgrade To Premium and get all the following features:</p>
        </div> 

        <div class="onp-help-section" id="social-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Drive More Traffic & Build Quality Followers', 'sociallocker'); ?>
            </h1>
            <p><?php _e('The premium version of the plugin provides 7 social buttons for all major social networks: Facebook, Twitter, Google, LinkedIn, including the Twitter Follow button. You can use them together or separately for customized results.', 'sociallocker') ?></p>
            <p class='onp-img'>
                <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/social-options.png' ?>' />
            </p>
            <p><?php _e('What\'s more, each button has individual settings (this way you can set an individual URL for each button).') ?>
            <p><?php _e('<strong>For example</strong>, you can set up the locker to get followers your Twitter account, fans for your Facebook page, +1s for a home page of your website.', 'sociallocker') ?></p>
        </div> 

        <div class="onp-help-section" id="extra-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Set How, When and For Whom Your Lockers Appear', 'sociallocker'); ?>
            </h1>
            
            <p>Of course, each website has its own unique audience. We know that a good business is an agile business. The premium version of Social Locker provides 8 additional options that allow you to configure the lockers flexibly to meet your needs.</p>

            <p class='onp-img'>
                <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/advanced-options.png' ?>' />
            </p>
            <div class="clearfix"></div>
        </div> 

        <div class="onp-help-section" id='blurring'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Create Highly Shareable Content Via The Blur Effect', 'sociallocker'); ?>
            </h1>
            <p>The previous versions of the plugin allowed only to hide the locked content totally. But recently we have added the long-awaited option to overlap content and make it transparent or blurred.</p>
            <p class='onp-img'>
                <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/blur-effect.png' ?>' />
            </p>
            <p>When we tested this feature on sites of some our customers, we were blown away how this feature attracts attention of the huge number of visitors. If people see and understand that they will get after unlocking, the plugin works more effectively.</p>
        </div> 

        <div class="onp-help-section" id='extra-themes'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('3 Extra Stunning Themes For Your Lockers', 'sociallocker'); ?>
            </h1>
            <p>
                <p>The premium version of Social Locker comes with 3 extra impressive, polished styles which create interest and attract attention. They are nicely animated and don't look obtrusive:</p>
                <ul>
                    <li><strong>Dandyish</strong>. A very bright theme to attract maximum attention!</li>
                    <li><strong>Flat (new!)</strong>. An extremely awesome theme based on the latest web technologies that will make your site a superstar. It's truly fascinating!</li>
                    <li><strong>Glass</strong>. A theme with transparent background which looks good on any website.</li>
                </ul>
            </p>
            <p class='onp-img'>
                <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/new-themes.png' ?>' />
            </p>
        </div> 

        <div class="onp-help-section" id='updates'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Get New Features & Updates Almost Every Week', 'sociallocker'); ?>
            </h1>
            <p>We release about 3-4 updates each month, adding new features and fixing bugs. The Free version does not guarantee that you will get all the major updates. But if you upgrade to the Premium version, your copy of the plugin will be always up-to-date.</p>
        </div> 

        <div class="onp-help-section" id='support'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Guaranteed Support Within 24h', 'sociallocker'); ?>
            </h1>
            <p>
                All of our plugins come with free support. We care about your plugin after purchase just as much as you do. We want to make your life easier and make you happy about choosing our plugins.
            </p>
            <p>
                Unfortunately we receive plenty of support requests every day and we cannot answer to all the users quickly. But for the users of the premium version (and the trial version), we guarantee to respond to every inquiry within 1 business day (typical response time is 3 hours).
            </p>
        </div> 

        <?php if ( !$alreadyActivated ) { ?>

        <div class="onp-help-section">
            <div>
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_324_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by a click)</small>
                </a>
            </div>
        </div> 

        <div class="onp-help-section">
            <p style="text-align: center;">
                <a href="<?php echo onp_licensing_324_get_purchase_url( $this->plugin ) ?>"><strong>Or Buy The Social Locker Right Now For $24</strong></a>
            </p>
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p><?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'sociallocker'); ?></p>
                    <p><?php printf(__('<strong>To purchase the Social Locker</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'sociallocker'), onp_licensing_324_get_purchase_url( $this->plugin )); ?></p>
                </div>
            </div>
        </div> 

        <?php } else { ?>
        <div class="onp-help-section">
            <div class='factory-bootstrap-325'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_licensing_324_get_purchase_url( $this->plugin ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Social Locker Premium For $24
                    <i class="fa fa-star"></i>
                </a>
            </div>
        </div> 
        <?php } ?>
        <?php
    }    
}

FactoryPages320::register($sociallocker, 'OnpSL_HowToUsePage');
