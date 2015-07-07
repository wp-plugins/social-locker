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
class OPanda_HowToUsePage extends FactoryPages321_AdminPage  {
 
    public $menuPostType = OPANDA_POST_TYPE;
    public $id = "how-to-use";
    
    public function __construct(Factory325_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('How to use?', 'bizpanda');
    }
  
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/howtouse.030100.css');   
        $this->styles->request('bootstrap.core', 'bootstrap');
    }
    
    protected $_pages = false;
    
    /**
     * Returns an array of the pages of the section 'How to use?'.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    protected function getPages() {
        if ( $this->_pages !== false ) return $this->_pages;
        
        $items = array(
            array(
                'name' => 'social-apps',
                'title' => __('Creating Social Apps', 'bizpanda'),
                'hollow' => true,
                
                'items' => array(
                    array(
                        'name' => 'facebook-app',
                        'title' => __('Creating Facebook App', 'bizpanda')
                    ),
                    array(
                        'name' => 'twitter-app',
                        'title' => __('Creating Twitter App', 'bizpanda')
                    ),
                    array(
                        'name' => 'google-client-id',
                        'title' => __('Getting Google Client ID', 'bizpanda')
                    )  
                )
            ),
            array(
                'name' => 'troubleshooting',
                'title' => __('Troubleshooting', 'bizpanda')
            )
        );
        
        if ( BizPanda::hasFeature('linkedin') ) {
            $items[0]['items'][] = array(
                'name' => 'linkedin-api-key',
                'title' => __('Getting LinkedIn API Key', 'bizpanda')
            );
        } 
        
        
        $this->_pages = apply_filters( 'opanda_help_pages', $items );
        return $this->_pages;
    }
    
    /**
     * Returns a current page name.
     * 
     * @since 1.0.0
     * @return string The current page name or null.
     */
    protected function _getCurrentPageName() {
        if ( isset( $_GET['onp_sl_page'] ) ) return $_GET['onp_sl_page'];
        
        $pages = $this->getPages();
        return $pages[0]['name'];
    }

    /**
     * Returns a parent page name of the current page.
     * 
     * @since 1.0.0
     * @return string|null A page name of the current parent page or null.
     */
    protected function _getCurrentParentPage() {
        $current = $this->_getCurrentPageName();
        
        $page = $this->getPageData( $current );
        if ( $page ) return $page['parent'];
        
        return null;
    }
    
    /**
     * Returns data of the specified page, including the parent page name.
     * 
     * @since 1.0.0
     * @return mixed[]|null The page data or null.
     */
    protected function _getPageData( $name, $parent = null, $haystack = null ) {
        $haystack = ( empty( $haystack ) ) ? $this->getPages() : $haystack;
        
        foreach( $haystack as $page ) {
            
            if ( $page['name'] == $name ) {
                $page['parent'] = $parent['name'];
                return $page;
            } 
            
            if ( isset( $page['items'] ) ) {
                $result = $this->_getPageData( $name, $page, $page['items'] );
                if ( $result ) return $result;
            }
        }
        
        return null;
    }
    
    /**
     * Gets the full path (which includes all parent pages) to a given page.
     * 
     * @param type $pageName A page name to return the full path.
     * @return mixed[] The navigation branch.
     */
    protected function _getPageTree( $pageName = null ) {
        if ( empty( $pageName ) ) $pageName = $this->_getCurrentPageName();

        $tree = array();
        $pageNameToSearch = $pageName;
        
        while( true ) {
            
            $pageData = $this->_getPageData( $pageNameToSearch );

            if ( empty( $pageData ) ) break;
            
            $tree[] = $pageData['name'];
            if ( empty( $pageData['parent']) ) break;
            
            $pageNameToSearch = $pageData['parent'];
        }

        return $tree;
    }
    
    /**
     * Renders the navigation.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function _renderNav( $currents = array() ) {
        $pages = $this->getPages();
        $index = 1;        
        ?>
        <div class="onp-help-nav">
            <?php foreach( $pages as $item ) { 
                $item['title'] = $index . '. ' . $item['title'];
                $index++;
                
                $this->_renderNavItem( $item, 0, $currents ); 
            } ?>
        </div>
        <?php
    }
    
    /**
     * Renders a single navigation item including its childs.
     * 
     * @since 1.0.0
     * @param string[] $item Navigation item to renders.
     * @param int $level Current navigation level, used in the recursion.
     * @return void
     */
    protected function _renderNavItem( $item, $level = 0, $currents = array() ) {
        
        $classes = array();
        
        $classes[] = 'onp-help-nav-level';
        $classes[] = 'onp-help-nav-level-' . $level;
        
        switch($level) {
            case 0:
                $classes[] = 'onp-help-nav-category';
                break;
            case 1:
                $classes[] = 'onp-help-nav-page';
                break;
            case 2:
                $classes[] = 'onp-help-nav-subpage';
                break;
        }
        
        $classes[] = 'onp-help-' . $item['name'];
        
        if ( in_array( $item['name'], $currents ) ) {
            $classes[] =  'onp-help-active-item';
        }
        
        $isGroup = isset( $item['items'] );
        if ( $isGroup ) $classes[] = 'onp-has-subitems';
        
        $class = implode(' ', $classes);
        $level = $level + 1;
        

        $url = $isGroup && ( isset( $item['hollow'] ) && $item['hollow'] )
            ? $this->getActionUrl('index', array('onp_sl_page' => $item['items'][0]['name'] ) )
            : $this->getActionUrl('index', array('onp_sl_page' => $item['name'] ) );
         
        ?>
            <div class="<?php echo $class ?>">
                <div class="onp-inner-wrap">
                    
                    <a href="<?php echo $url; ?>">
                        <?php if ( $isGroup ): ?>
                        <l class="fa fa-plus-square-o"></l>
                        <l class="fa fa-minus-square-o"></l>
                        <?php endif ?>
                        <span><?php echo $item['title'] ?></span>
                    </a>

                    <?php if ( isset( $item['items'] )): ?>
                    <div class="onp-help-nav-subitems">
                        <?php foreach( $item['items'] as $subItem ) { $this->_renderNavItem( $subItem, $level, $currents ); } ?>
                    </div>
                    <?php endif ?>
                    
                </div>
            </div>
        <?php
    }

    /**
     * Shows the content table for a given navigatin item (category, page).
     * 
     * @param mixed[] $page An 
     * @return type
     */
    public function showContentTable( $page = null ) {
        $page = empty( $page ) ? $this->current : $page;
        if ( empty( $page) ) return;

        $data = $this->_getPageData( $page );
        if ( empty( $data['items']) ) return;
        ?>

        <ul>
        <?php foreach( $data['items'] as $item ) { ?>
            <li>
                <a href="<?php $this->actionUrl('index', array( 'onp_sl_page' => $item['name'] )) ?>"><?php echo $item['title'] ?></a>
            </li>
        <?php } ?>
        </ul>

        <?php
    }
    
    /**
     * Shows one of the help pages.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        
        $this->current = $this->_getCurrentPageName();
        $this->currents = $this->_getPageTree();
        
        add_action('opanda_help_page_facebook-app', array($this, 'facebookApp' ));
        add_action('opanda_help_page_twitter-app', array($this, 'twitterApp' ));
        add_action('opanda_help_page_google-client-id', array($this, 'googleClientId' ));
        add_action('opanda_help_page_linkedin-api-key', array($this, 'linkedinApiKey' ));
        add_action('opanda_help_page_troubleshooting', array($this, 'troubleshooting' ));
        
        ?>
        <div class="wrap factory-bootstrap-329 factory-fontawesome-320">
            <?php $this->_renderNav( $this->currents ) ?>
            <div class="onp-help-content">
                <div class="onp-inner-wrap">
                    <?php do_action('opanda_help_page_' . $this->current, $this ) ?>
                </div>
            </div>    
        </div> 
        <?php  
        return;
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
            <h1><?php _e('Troubleshooting', 'bizpanda'); ?></h1>

            <p><?php _e('If you have any questions or faced with any troubles while using our plugin, please check our <a href="http://support.onepress-media.com/" target="_blank">knowledge base</a>. It is possible that instructions for resolving your issue have already been posted.', 'bizpanda'); ?></p>  
            <p>
                <?php _e('If the answer to your question isn\'t listed, please submit a ticket <a href="http://support.onepress-media.com/create-ticket/" target="_blank">here</a>.<br />You can also email us directly <strong>support@byonepress.com</strong>', 'bizpanda'); ?>
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
        global $optinpanda;
        $alreadyActivated = get_option('onp_trial_activated_' . $optinpanda->pluginName, false);

        ?>
        <div class="onp-help-section">
            
            <?php if ( !$alreadyActivated ) { ?>
                <h1><?php _e('Try Premium Version For 7 Days For Free!', 'bizpanda'); ?></h1>
            <?php } else { ?>
                <h1><?php _e('Upgrade Opt-In Panda To Premium!', 'bizpanda'); ?></h1>     
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>  
            <p>
                <?php printf( __('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Opt-In Panda</a> plugin. 
                We offer you to try the premium version for 7 days absolutely for free. We sure you will love it.', 'bizpanda'), onp_licensing_325_get_purchase_url( $this->plugin ) ) ?>
            </p>
            <p>
                <?php _e('Check out the table below to know about the premium features.', 'bizpanda'); ?>
            </p>
            <?php } else { ?>
            <p>
                <?php _e('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Opt-In Panda plugin</a> sold on CodeCanyon.', 'bizpanda') ?>
                <?php _e('Check out the table below to know about all the premium features.', 'bizpanda'); ?>
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
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by a click)</small>
                </a>
            </div>
            
            <?php } else { ?>
            
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_licensing_325_get_purchase_url( $this->plugin ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Opt-In Panda Premium For $24
                    <i class="fa fa-star"></i>
                </a>
            </div>
            
            <?php } ?>
        </div>

        <?php if ( !$alreadyActivated ) { ?>

        <div class="onp-help-section">
            <p style="text-align: center;">
                <a href="<?php echo onp_licensing_325_get_purchase_url( $this->plugin ) ?>"><strong>Or Buy The Opt-In Panda Right Now For $24</strong></a>
            </p>
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p><?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'bizpanda'); ?></p>
                    <p><?php printf(__('<strong>To purchase the Opt-In Panda</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'bizpanda'), onp_licensing_325_get_purchase_url( $this->plugin )); ?></p>
                </div>
            </div>
        </div> 

        <?php } ?>

        <div class="onp-help-section">
            <p>Upgrade To Premium and get all the following features:</p>
        </div> 

        <div class="onp-help-section" id="social-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Drive More Traffic & Build Quality Followers', 'bizpanda'); ?>
            </h1>
            <p><?php _e('The premium version of the plugin provides 7 social buttons for all major social networks: Facebook, Twitter, Google, LinkedIn, including the Twitter Follow button. You can use them together or separately for customized results.', 'bizpanda') ?></p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/social-options.png' />
            </p>
            <p><?php _e('What\'s more, each button has individual settings (this way you can set an individual URL for each button).') ?>
            <p><?php _e('<strong>For example</strong>, you can set up the locker to get followers your Twitter account, fans for your Facebook page, +1s for a home page of your website.', 'bizpanda') ?></p>
        </div> 

        <div class="onp-help-section" id="extra-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Set How, When and For Whom Your Lockers Appear', 'bizpanda'); ?>
            </h1>
            
            <p>Of course, each website has its own unique audience. We know that a good business is an agile business. The premium version of Opt-In Panda provides 8 additional options that allow you to configure the lockers flexibly to meet your needs.</p>

            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/advanced-options.png' />
            </p>
            <div class="clearfix"></div>
        </div> 

        <div class="onp-help-section" id='blurring'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Create Highly Shareable Content Via The Blur Effect', 'bizpanda'); ?>
            </h1>
            <p>The previous versions of the plugin allowed only to hide the locked content totally. But recently we have added the long-awaited option to overlap content and make it transparent or blurred.</p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/blur-effect.png' />
            </p>
            <p>When we tested this feature on sites of some our customers, we were blown away how this feature attracts attention of the huge number of visitors. If people see and understand that they will get after unlocking, the plugin works more effectively.</p>
        </div> 

        <div class="onp-help-section" id='extra-themes'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('3 Extra Stunning Themes For Your Lockers', 'bizpanda'); ?>
            </h1>
            <p>
                <p>The premium version of Opt-In Panda comes with 3 extra impressive, polished styles which create interest and attract attention. They are nicely animated and don't look obtrusive:</p>
                <ul>
                    <li><strong>Dandyish</strong>. A very bright theme to attract maximum attention!</li>
                    <li><strong>Flat (new!)</strong>. An extremely awesome theme based on the latest web technologies that will make your site a superstar. It's truly fascinating!</li>
                    <li><strong>Glass</strong>. A theme with transparent background which looks good on any website.</li>
                </ul>
            </p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/new-themes.png' />
            </p>
        </div> 

        <div class="onp-help-section" id='updates'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Get New Features & Updates Almost Every Week', 'bizpanda'); ?>
            </h1>
            <p>We release about 3-4 updates each month, adding new features and fixing bugs. The Free version does not guarantee that you will get all the major updates. But if you upgrade to the Premium version, your copy of the plugin will be always up-to-date.</p>
        </div> 

        <div class="onp-help-section" id='support'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Guaranteed Support Within 24h', 'bizpanda'); ?>
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
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
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
                <a href="<?php echo onp_licensing_325_get_purchase_url( $this->plugin ) ?>"><strong>Or Buy The Opt-In Panda Right Now For $24</strong></a>
            </p>
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p><?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'bizpanda'); ?></p>
                    <p><?php printf(__('<strong>To purchase the Opt-In Panda</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'bizpanda'), onp_licensing_325_get_purchase_url( $this->plugin )); ?></p>
                </div>
            </div>
        </div> 

        <?php } else { ?>
        <div class="onp-help-section">
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_licensing_325_get_purchase_url( $this->plugin ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Opt-In Panda Premium For $24
                    <i class="fa fa-star"></i>
                </a>
            </div>
        </div> 
        <?php } ?>
        <?php
    }
    
     /**
     * Page 'Creating Social Apps'
     * 
     * @since 1.0.0
     * @return void
     */
    public function socialApps() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/social-apps.php';
    }   
    
    /**
     * Page 'Creating Social Apps' => 'Creating Facebook App'
     * 
     * @since 1.0.0
     * @return void
     */
    public function facebookApp() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/facebook-app.php';
    }
    
    /**
     * Page 'Creating Social Apps' => 'Creating Twitter App'
     * 
     * @since 1.0.0
     * @return void
     */
    public function twitterApp() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/twitter-app.php';
    }
    
    /**
     * Page 'Creating Social Apps' => 'Getting Google Client ID'
     * 
     * @since 1.0.0
     * @return void
     */
    public function googleClientId() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/google-app.php';
    }
    
     /**
     * Page 'Creating Social Apps' => 'Getting LinkedIn API Key'
     * 
     * @since 1.0.0
     * @return void
     */
    public function linkedinApiKey() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/linkedin-app.php';
    }   
    
     /**
     * Page 'Important Notes'
     * 
     * @since 1.0.0
     * @return void
     */
    public function notes() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/notes.php';   
    }
    
     /**
     * Page 'Important Notes' => 'Using the Facebook Like with the Social Locker'
     * 
     * @since 1.0.0
     * @return void
     */
    public function facebookLike() {
        require OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use/facebook-like.php';   
    } 
}

FactoryPages321::register($bizpanda, 'OPanda_HowToUsePage');
