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
    
    public function __construct(Factory321_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('How to use?', 'sociallocker');
    }
  
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/howtouse.030100.css');   
    }
    
    protected $_pages = false;
    
    protected function getPages() {
        if ( $this->_pages !== false ) return $this->_pages;
            
            $items = array(
                array(
                    'name' => 'getting-started',
                    'function' => array( $this, 'gettingStarted'),
                    'title' => __('Gettings started', 'sociallocker')
                ),
                array(
                    'name' => 'premium',
                    'function' => array( $this, 'premium'),
                    'title' => '<i class="fa fa-rocket"></i> ' . __('Get more features!', 'sociallocker')
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
            
        

        
        
        
        $this->_pages = apply_filters( 'onp_sl_help_pages', $items );
        return $this->_pages;
    }
    
    protected function showNav() {
        $pages = $this->getPages();
        
        ?>
        <div class="onp-help-nav">
            <ul>
            <?php foreach( $pages as $page ) { ?>
                <li><a href='<?php $this->actionUrl('index', array('onp_sl_page' => $page['name'])) ?>'><?php echo $page['title'] ?></a></li>
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
        
        <div class="wrap factory-bootstrap-323 factory-fontawesome-320">
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
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/1.png' ?>' />
                </p>
                <p><?php _e('Click on the shortcode titled “Default Locker” to open the locker editor:', 'sociallocker'); ?></p>
                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/2.png' ?>' />
                </p>
            </div>

            <div class="onp-help-section">
                <h2>2. <?php _e('Configure the locker', 'sociallocker'); ?></h2>

                <p>1) <?php _e('Set the URL that will receive likes/tweets/plus ones or leave the field empty.', 'sociallocker'); ?></p>         
                <p>2) <?php _e('Set a clear title that attracts attention or creates a call to action (see the example below).', 'sociallocker'); ?></p>
                <p>3) <?php _e('Describe what the visitor will get after they unlock the content. This is very important, as visitors need to be aware of what they are getting. And please, only promise things you can deliver.', 'sociallocker'); ?></p> 
                </p>

                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/3-free.png' ?>' />
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
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/5.png' ?>' />
                </p>

                <p>
                    <?php _e('That’s it! Save your post and see it on your site! ', 'sociallocker'); ?>
                </p>

                <p class='onp-img'>
                    <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/6.png' ?>' />
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
        ?>
        <div class="onp-help-section">
            <h1><?php _e('Premium Version', 'sociallocker'); ?></h1>

            <p>
                <?php echo sprintf(__('This plugin is a free edition of the popular <a target="_blank" href="%s"> Social Locker plugin</a> sold on CodeCanyon. 
                You can activate premium features for a trial period <a href="%s">inside the plugin</a>.', 'sociallocker'), onp_licensing_323_get_purchase_url( $this->plugin ), onp_licensing_323_get_manager_link($this->plugin->pluginName )); ?></strong>
            </p>
        </div>

        <div class="onp-help-section">
            <h2><?php _e('What differences between the free, premium and trial versions?', 'sociallocker'); ?></h2>

            <p>
                <?php _e('The <strong>free version</strong> allows you to:', 'sociallocker'); ?>
                <ul>
                    <li><?php _e('Lock any part of the post content behind the Social Locker that will ask people "to pay" for your content with a tweet, plus one, or a like.', 'sociallocker'); ?></li>
                    <li><?php _e('Customize the text the visitor will see in place of the content.', 'sociallocker'); ?></li>
                    <li><?php _e('See increase of social engagement on your website through the built-in statistics tools.', 'sociallocker'); ?></li>
                </ul>
            </p>

            <p>
                <?php _e('The <strong>premium version</strong> allows you to:', 'sociallocker'); ?>
                <ul>
                    <li><?php _e('All features included in the free version.', 'sociallocker'); ?></li>
                    <li><?php _e('Set a separate URL for each social button. <br />
                        For example, you can set an URL to like for your Facebook Page (in order to send likes to your Facebook page and make users subscribers), set an URL to tweet for a current page and set an URL to "+1" for your main page.', 'sociallocker'); ?></li>
                    <li><?php _e('Choose one of 5 locker styles (Starter, Secrets, Dandyish, Glass, Flat) that will be more suitable for your site style.', 'sociallocker') ?></li>
                    <li><?php _e('Set visibility options for your lockers. For example, you can hide lockers and reveal locked content automatically for registered users.', 'sociallocker'); ?></li>
                    <li><?php _e('Use advanced features such as the Countdown Timer, the Close Icon, the Ajax option and more.', 'sociallocker'); ?></li>
                </ul>
            </p>

            <p>
                <?php _e('The <strong>trial version</strong> is a time-limited premium version of the Social Locker which allows you to use it for 7 days.', 'sociallocker'); ?>
            </p>
        </div>

        <div class="onp-help-section">
            <h1><?php _e('How can I activate the trial version?', 'sociallocker'); ?></h1>

            <p>
                <?php printf(__('Open the License Manager page or just <a href="%s">click here</a> to activate your free trial.', 'sociallocker'), onp_licensing_323_get_manager_link($this->plugin->pluginName, 'activateTrial')); ?>
            </p>
        </div>

        <div class="onp-help-section">
            <h1><?php _e('How can I buy the premium version?', 'sociallocker'); ?></h1>

            <p>
                 <?php printf(__('<a target="_blank" href="%s">Click here</a> to visit the plugin page on CodeCanyon and cluck the “”Purchase” button on the right sidebar.', 'sociallocker'), onp_licensing_323_get_purchase_url( $this->plugin )); ?>
            </p>
        </div> 
        <?php
    }    
}

FactoryPages320::register($sociallocker, 'OnpSL_HowToUsePage');
