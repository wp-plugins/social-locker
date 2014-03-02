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
class OnpSL_HowToUsePage extends FactoryPages306_AdminPage  {
 
    public $menuTitle = 'How to use?';
    public $menuPostType = 'social-locker';
    
    public $id = "how-to-use";
  
    public function assets() {
        $this->scripts->request('jquery');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/howtouse.030100.css');   
    }
    
    protected function showNav() {
            
            ?>
                <div class="onp-help-nav">
                    <ul>
                        <li><a href='<?php $this->actionUrl('index') ?>'>Gettings started</a></li>
                        <li><a href='<?php $this->actionUrl('premium') ?>'><i class="fa fa-rocket"></i> Get more features!</a></li>
                        <li><a href='<?php $this->actionUrl('review') ?>'>Have a plugin review?</a></li>
                        <li><a href='<?php $this->actionUrl('troubleshooting') ?>'>Troubleshooting</a></li>
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
        <div class="wrap factory-bootstrap-305 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1>Getting started</h1>
                    
                    <p>
                        The Social Locker plugin provides shortcodes to lock content.
                        While the instaltion, the plugin created for you the shortcode [sociallocker] named "Default Locker".
                    </p>
                    <p class='onp-remark'>
                        <span class="onp-inner-wrap">
                        You can create extra shortcodes. 
                        For example, one for locking video players, one for locking download links.
                        Every shortcode has own settings.
                        </span>
                    </p>
                    <p>
                        Let's examine how to use the Default Locker.
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h2>1. Open the editor</h2>

                    <p>Move to Social Locker -> All Social Locker by using the admin menu:</p>
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/1.png' ?>' />
                    </p>
                    <p>Click the shortcode title "Default Locker" to open the locker editor:</p>
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/2.png' ?>' />
                    </p>
                </div>

                <div class="onp-help-section">
                    <h2>2. Configure the locker</h2>
                    
                    <p>1) Set the URL that will receive likes/tweets/plus ones or leave the field empty.</p>         
                    <p>2) Set a clear title that attracts attention or calls to action (see the example below).</p>
                    <p>3) Describe that a visitor will get after unlocking.<br />
                    It's a very important point. Visitors need to realize that they will get. And that should be truth.</p> 
                    </p>
                    
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/3-free.png' ?>' />
                    </p>
                    
                    <p>
                        Congratulations! The locker is ready to use.
                    </p>
                </div>  

                <div class="onp-help-section">
                    <h2>3. Put the locker shortcode</h2>

                    <p>
                        Decide which content you will to lock. It may be:
                        <ul>
                            <li>a download link (etc. free graphical, audio, video resources, a printable pdf of your article)</li>
                            <li>a promo code (etc. 10% discount, if a visitor shares your promo page)</li>
                            <li>an ending of your article (etc. show a beginning of an interesting story and hide its ending)</li>                 
                        </ul>
                        That is, all what can be interesting for people who visit your site.
                    </p>

                    <p>
                        At the same time:
                        <ul>
                            <li>
                                <strong>Never lock</strong> all content of your posts and pages.
                            </li>
                            <li>
                                <strong>Never lock</strong> useless and uninteresting content.
                            </li>
                        </ul>
                    </p>
                    <p>
                        In other words, don't fool visitors of your site. Otherwise, that will lead to annoyance of people
                        which will remove likes/tweets/posts immediately after unlocking your content.
                    </p>

                    <p>
                        Open the post editor for the post where you want to put the locker. And wrap the content you
                        want to lock via the locker shortcode [sociallocker] [/sociallocker]:
                    </p>
                    
                    <p class='onp-img'>
                        <img src='<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/how-to-use/5.png' ?>' />
                    </p>
                    
                    <p>
                        That's done. Save your post and and view how it looks on your site.
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
        <div class="wrap factory-bootstrap-305 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1>Extra Resources</h1>
                   
                    <p>If you have a website and you have written a review for the Social Locker plugin or an article on how to use it, please let us know.</p>
                    <p>We're collecting links where the plugin was reviewed in order to recommend and promote them as extra education resources in the next version.</p>  
                    
                    <p>Please send your reviews to <strong>support@byonepress.com</strong></p>
                </div>
            </div> 
            
        </div>
        <?php
    }
    
    public function troubleshootingAction() {
        ?>
        <div class="wrap factory-bootstrap-305 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1>Troubleshooting</h1>
                   
                    <p>If you faced with any troubles while using the plugin, please check out <a href="http://support.onepress-media.com/" target="_blank">our knowlage base</a>. May be your issue has been already resolved.</p>  
                    <p>
                        If it doesn't help, feel free to contact us.<br />
                        Submit a ticket <a href="http://support.onepress-media.com/create-ticket/" target="_blank">here</a> or email directly to <strong>support@byonepress.com</strong>
                    </p>
                </div>
            </div> 
            
        </div>
        <?php
    }
    
    public function premiumAction() {
        ?>
        <div class="wrap factory-bootstrap-305 factory-fontawesome-305">
            
            <?php $this->showNav('getting-started') ?>
            
            <div class="onp-help-content">

                <div class="onp-help-section">
                    <h1>Premium Version</h1>
                   
                    <p>
                        This plugin is a free edition of the popular <a target="_blank" href="<?php echo $this->plugin->options['premium'] ?>"> Social Locker plugin</a> sold on CodeCanyon. 
                        You can activate premium features for a trial period <a href="<?php onp_licensing_306_manager_link($this->plugin->pluginName ) ?>">inside the plugin</a>.</strong>
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h2>What differences between the free, premium and trial versions?</h2>
                    
                    <p>
                        The <strong>free version</strong> allows:
                        <ul>
                            <li>To lock any part of the post content behind the Social Locker that will ask people "to pay" for your content with a tweet, plus one, or a like.</li>
                            <li>To set messages for the locker that people will see instead of the content.</li>
                            <li>To view social impact by using the built-in statistics tools.</li>
                        </ul>
                    </p>
                    
                    <p>
                        The <strong>premium version</strong> allows:
                        <ul>
                            <li>To do the same things as the free version</li>
                            <li>To set a separate URL for each social button. <br />
                                For example, you can set an URL to like for your Facebook Page (in order to send likes to your Facebook page and make users subscribers), set an URL to tweet for current page and set an URL to plus one for main page in order to increase your site SEO scores. - To choose one of 3 locker styles (Secrets, Dandyish and Glass) that will be more suitable for your site style.</li>
                            <li>To set visibility options for your lockers. For example, you can hide lockers and reveal locked content automatically for registered users.</li>
                            <li>To use advanced features like the Countdown Timer, the Close Icon, the Ajax option and more.</li>
                        </ul>
                    </p>
                    
                    <p>
                        The <strong>trial version</strong> is the exactly same like the premium version but works within 7 days.
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h1>How to activate the trial version?</h1>
                   
                    <p>
                        You can make that on the License Manager page or just <a href="<?php onp_licensing_306_manager_link($this->plugin->pluginName, 'activateTrial') ?>">click here</a> to activate the trial version.
                    </p>
                </div>
                
                <div class="onp-help-section">
                    <h1>How to buy the premium version?</h1>
                   
                    <p>
                        Move to the <a target="_blank" href="<?php echo $this->plugin->options['premium'] ?>">plugin page</a> on CodeCanyon and click the Purchase button on the right sidebar.
                    </p>
                </div> 
                
            </div> 
            
        </div>
        <?php
    }    
}

FactoryPages306::register($sociallocker, 'OnpSL_HowToUsePage');