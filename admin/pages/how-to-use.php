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
class OnpSL_HowToUsePage extends FactoryPages310_AdminPage  {
 
    public $menuTitle = 'How to use?';
    public $menuPostType = 'social-locker';
    
    public $id = "how-to-use";
    
    public function __construct(Factory310_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('How to use?', 'sociallocker');
    }
  
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/howtouse.030100.css');   
    }
    
    protected function showNav() {
            
            ?>
                <div class="onp-help-nav">
                    <ul>
                        <li><a href='<?php $this->actionUrl('index') ?>'><?php _e('Gettings started', 'sociallocker'); ?></a></li>
                        <li><a href='<?php $this->actionUrl('premium') ?>'><i class="fa fa-rocket"></i> <?php _e('Get more features!', 'sociallocker'); ?></a></li>
                        <li><a href='<?php $this->actionUrl('review') ?>'><?php _e('Have a plugin review?', 'sociallocker'); ?></a></li>
                        <li><a href='<?php $this->actionUrl('troubleshooting') ?>'><?php _e('Troubleshooting', 'sociallocker'); ?></a></li>
                    </ul>
                </div>
            <?php
            
        

    }
    
    /**
     * Shows an index page where a user can set settings.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
            
        ?>
        <div class="wrap factory-bootstrap-312 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1><?php _e('Getting started', 'sociallocker'); ?></h1>
                    
                    <p>
                        <?php _e('The Social Locker plugin provides shortcodes to lock content. While the instaltion, the plugin created for you the shortcode [sociallocker] named "Default Locker".', 'sociallocker'); ?>
                    </p>
                    <p class='onp-remark'>
                        <span class="onp-inner-wrap">
                        <?php _e('You can create extra shortcodes. For example, one for locking video players, one for locking download links. Every shortcode has own settings.', 'sociallocker'); ?>
                        </span>
                    </p>
                    <p>
                        <?php _e('Let\'s examine how to use the Default Locker.', 'sociallocker'); ?>
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h2>1. <?php _e('Open the editor', 'sociallocker'); ?></h2>

                    <p><?php _e('Move to Social Locker -> All Social Locker by using the admin menu:', 'sociallocker'); ?></p>
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/1.png' ?>' />
                    </p>
                    <p><?php _e('Click the shortcode title "Default Locker" to open the locker editor:', 'sociallocker'); ?></p>
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/2.png' ?>' />
                    </p>
                </div>

                <div class="onp-help-section">
                    <h2>2. <?php _e('Configure the locker', 'sociallocker'); ?></h2>
                    
                    <p>1) <?php _e('Set the URL that will receive likes/tweets/plus ones or leave the field empty.', 'sociallocker'); ?></p>         
                    <p>2) <?php _e('Set a clear title that attracts attention or calls to action (see the example below).', 'sociallocker'); ?></p>
                    <p>3) <?php _e('Describe that a visitor will get after unlocking.<br /> It\'s a very important point. Visitors need to realize that they will get. And that should be truth.', 'sociallocker'); ?></p> 
                    </p>
                    
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/3-free.png' ?>' />
                    </p>
                    
                    <p>
                        <?php _e('Congratulations! The locker is ready to use.', 'sociallocker'); ?>
                    </p>
                </div>  

                <div class="onp-help-section">
                    <h2>3. <?php _e('Put the locker shortcode', 'sociallocker'); ?></h2>

                    <p>
                        <?php _e('Decide which content you will to lock. It may be:', 'sociallocker'); ?>
                        <ul>
                            <li><?php _e('a download link (etc. free graphical, audio, video resources, a printable pdf of your article)', 'sociallocker'); ?></li>
                            <li><?php _e('a promo code (etc. 10% discount, if a visitor shares your promo page)', 'sociallocker'); ?></li>
                            <li><?php _e('an ending of your article (etc. show a beginning of an interesting story and hide its ending)', 'sociallocker'); ?></li>                 
                        </ul>
                        <?php _e('That is, all what can be interesting for people who visit your site.', 'sociallocker'); ?>
                    </p>

                    <p>
                        <?php _e('At the same time:', 'sociallocker'); ?>
                        <ul>
                            <li>
                                <?php _e('<strong>Never lock</strong> all content of your posts and pages.', 'sociallocker'); ?>
                            </li>
                            <li>
                                <?php _e('<strong>Never lock</strong> useless and uninteresting content.', 'sociallocker'); ?>
                            </li>
                        </ul>
                    </p>
                    <p>
                        <?php _e('In other words, don\'t fool visitors of your site. Otherwise, that will lead to annoyance of people which will remove likes/tweets/posts immediately after unlocking your content.', 'sociallocker'); ?>
                    </p>

                    <p>
                        <?php _e('Open the post editor for the post where you want to put the locker. And wrap the content you want to lock via the locker shortcode [sociallocker] [/sociallocker]:', 'sociallocker'); ?>
                    </p>
                    
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/5.png' ?>' />
                    </p>
                    
                    <p>
                        <?php _e('That\'s done. Save your post and and view how it looks on your site.', 'sociallocker'); ?>
                    </p>

                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/6.png' ?>' />
                    </p>
                </div>    
    
            </div> 
            
        </div>
        <?php
            
        

    }
    
    /**
     * Shows a review pag.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function reviewAction() {
        
        ?>
        <div class="wrap factory-bootstrap-312 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1><?php _e('Extra Resources', 'sociallocker'); ?></h1>
                   
                    <p><?php _e('If you have a website and you have written a review for the Social Locker plugin or an article on how to use it, please let us know.', 'sociallocker'); ?></p>
                    <p><?php _e('We\'re collecting links where the plugin was reviewed in order to recommend and promote them as extra education resources in the next version.', 'sociallocker'); ?></p>  
                    
                    <p><?php _e('Please send your reviews to <strong>support@byonepress.com</strong>', 'sociallocker'); ?></p>
                </div>
            </div> 
            
        </div>
        <?php
    }
    
    public function troubleshootingAction() {
        ?>
        <div class="wrap factory-bootstrap-312 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1><?php _e('Troubleshooting', 'sociallocker'); ?></h1>
                   
                    <p><?php _e('If you faced with any troubles while using the plugin, please check out <a href="http://support.onepress-media.com/" target="_blank">our Knowledge Base</a>. May be your issue has been already resolved.', 'sociallocker'); ?></p>  
                    <p>
                        <?php _e('If it doesn\'t help, feel free to contact us.<br />Submit a ticket <a href="http://support.onepress-media.com/create-ticket/" target="_blank">here</a> or email directly to <strong>support@byonepress.com</strong>', 'sociallocker'); ?>
                    </p>
                </div>
            </div> 
            
        </div>
        <?php
    }
    
    public function premiumAction() {
        ?>
        <div class="wrap factory-bootstrap-312 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1><?php _e('Premium Version', 'sociallocker'); ?></h1>
                   
                    <p>
                        <?php echo sprintf(__('This plugin is a free edition of the popular <a target="_blank" href="%s"> Social Locker plugin</a> sold on CodeCanyon. 
                        You can activate premium features for a trial period <a href="%s">inside the plugin</a>.', 'sociallocker'), onp_licensing_311_purchase_url( $this->plugin ), onp_licensing_311_manager_link($this->plugin->pluginName )); ?></strong>
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h2><?php _e('What differences between the free, premium and trial versions?', 'sociallocker'); ?></h2>
                    
                    <p>
                        <?php _e('The <strong>free version</strong> allows:', 'sociallocker'); ?>
                        <ul>
                            <li><?php _e('To lock any part of the post content behind the Social Locker that will ask people "to pay" for your content with a tweet, plus one, or a like.', 'sociallocker'); ?></li>
                            <li><?php _e('To set messages for the locker that people will see instead of the content.', 'sociallocker'); ?></li>
                            <li><?php _e('To view social impact by using the built-in statistics tools.', 'sociallocker'); ?></li>
                        </ul>
                    </p>
                    
                    <p>
                        <?php _e('The <strong>premium version</strong> allows:', 'sociallocker'); ?>
                        <ul>
                            <li><?php _e('To do the same things as the free version', 'sociallocker'); ?></li>
                            <li><?php _e('To set a separate URL for each social button. <br />
                                For example, you can set an URL to like for your Facebook Page (in order to send likes to your Facebook page and make users subscribers), set an URL to tweet for current page and set an URL to plus one for main page in order to increase your site SEO scores. - To choose one of 3 locker styles (Secrets, Dandyish and Glass) that will be more suitable for your site style.', 'sociallocker'); ?></li>
                            <li><?php _e('To set visibility options for your lockers. For example, you can hide lockers and reveal locked content automatically for registered users.', 'sociallocker'); ?></li>
                            <li><?php _e('To use advanced features like the Countdown Timer, the Close Icon, the Ajax option and more.', 'sociallocker'); ?></li>
                        </ul>
                    </p>
                    
                    <p>
                        <?php _e('The <strong>trial version</strong> is the exactly same like the premium version but works within 7 days.', 'sociallocker'); ?>
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h1><?php _e('How to activate the trial version?', 'sociallocker'); ?></h1>
                   
                    <p>
                        <?php sprintf(__('You can make that on the License Manager page or just <a href="%s">click here</a> to activate the trial version.', 'sociallocker'), onp_licensing_311_manager_link($this->plugin->pluginName, 'activateTrial')); ?>
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h1><?php _e('How to buy the premium version?', 'sociallocker'); ?></h1>
                   
                    <p>
                         <?php sprintf(__('Move to the <a target="_blank" href="%s">plugin page</a> on CodeCanyon and click the Purchase button on the right sidebar.', 'sociallocker'), onp_licensing_311_purchase_url( $this->plugin )); ?>
                    </p>
                </div> 
                
            </div> 
            
        </div>
        <?php
    }    
}

FactoryPages310::register($sociallocker, 'OnpSL_HowToUsePage');