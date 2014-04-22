<?php
/**
 * The file contains a page that shows the common settings for the plugin.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The page Common Settings.
 * 
 * @since 1.0.0
 */
class OnpSL_CommonSettingsPage extends FactoryPages306_AdminPage  {
 
    /**
     * The title of the page in the admin menu.
     * 
     * @see FactoryPages306_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $menuTitle = 'Common Settings';
    
    /**
     * The parent menu of the page in the admin menu.
     * 
     * @see FactoryPages306_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $menuPostType = 'social-locker';
    
    /**
     * The id of the page in the admin menu.
     * 
     * Mainly used to navigate between pages.
     * @see FactoryPages306_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $id = "common-settings";
    
    /**
     * Available languages.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    protected $languages = array(
        array('ca_ES', 'Catalan'),
        array('cs_CZ', 'Czech'),
        array('cy_GB', 'Welsh'),
        array('da_DK', 'Danish'),
        array('de_DE', 'German'),
        array('eu_ES', 'Basque'),
        array('en_US', 'English'),
        array('es_ES', 'Spanish'),
        array('fi_FI', 'Finnish'), 
        array('fr_FR', 'French'), 
        array('gl_ES', 'Galician'), 
        array('hu_HU', 'Hungarian'),
        array('it_IT', 'Italian'),
        array('ja_JP', 'Japanese'),
        array('ko_KR', 'Korean'),
        array('nb_NO', 'Norwegian'),
        array('nl_NL', 'Dutch'),
        array('pl_PL', 'Polish'),
        array('pt_BR', 'Portuguese (Brazil)', 'pt-BR'),
        array('pt_PT', 'Portuguese (Portugal)', 'pt-PT'),
        array('ro_RO', 'Romanian'),
        array('ru_RU', 'Russian'),
        array('sk_SK', 'Slovak'),  
        array('sl_SI', 'Slovenian'), 
        array('sv_SE', 'Swedish'),
        array('th_TH', 'Thai'),
        array('tr_TR', 'Turkish'), 
        array('ku_TR', 'Kurdish'), 
        array('zh_CN', 'Simplified Chinese (China)', 'zh-CN'), 
        array('zh_HK', 'Traditional Chinese (Hong Kong)', 'zh-HK'),
        array('zh_TW', 'Traditional Chinese (Taiwan)', 'zh-TW'), 
        array('af_ZA', 'Afrikaans'),
        array('sq_AL', 'Albanian'),
        array('hy_AM', 'Armenian'),
        array('az_AZ', 'Azeri'),
        array('be_BY', 'Belarusian'),
        array('bn_IN', 'Bengali'),
        array('bs_BA', 'Bosnian'),
        array('bg_BG', 'Bulgarian'),
        array('hr_HR', 'Croatian'),
        array('nl_BE', 'Dutch (België)'),
        array('eo_EO', 'Esperanto'),
        array('et_EE', 'Estonian'),
        array('fo_FO', 'Faroese'),
        array('ka_GE', 'Georgian'),
        array('el_GR', 'Greek'),
        array('gu_IN', 'Gujarati'),
        array('hi_IN', 'Hindi'),
        array('is_IS', 'Icelandic'),
        array('id_ID', 'Indonesian'),
        array('ga_IE', 'Irish'),
        array('jv_ID', 'Javanese'),
        array('kn_IN', 'Kannada'),
        array('kk_KZ', 'Kazakh'),
        array('la_VA', 'Latin'),
        array('lv_LV', 'Latvian'),
        array('li_NL', 'Limburgish'),
        array('lt_LT', 'Lithuanian'), 
        array('mk_MK', 'Macedonian'), 
        array('mg_MG', 'Malagasy'),
        array('ms_MY', 'Malay'),
        array('mt_MT', 'Maltese'),
        array('mr_IN', 'Marathi'),
        array('mn_MN', 'Mongolian'),
        array('ne_NP', 'Nepali'),
        array('pa_IN', 'Punjabi'),
        array('rm_CH', 'Romansh'),
        array('sa_IN', 'Sanskrit'),
        array('sr_RS', 'Serbian'),
        array('so_SO', 'Somali'),
        array('sw_KE', 'Swahili'),
        array('tl_PH', 'Filipino'),
        array('ta_IN', 'Tamil'),
        array('tt_RU', 'Tatar'), 
        array('te_IN', 'Telugu'),
        array('ml_IN', 'Malayalam'),
        array('uk_UA', 'Ukrainian'),
        array('uz_UZ', 'Uzbek'),
        array('vi_VN', 'Vietnamese'),
        array('xh_ZA', 'Xhosa'),
        array('zu_ZA', 'Zulu'),
        array('km_KH', 'Khmer'),
        array('tg_TJ', 'Tajik'),
        array('ar_AR', 'Arabic'), 
        array('he_IL', 'Hebrew'),
        array('ur_PK', 'Urdu'),
        array('fa_IR', 'Persian'),
        array('sy_SY', 'Syriac'),  
        array('yi_DE', 'Yiddish'),
        array('gn_PY', 'Guaraní'),
        array('qu_PE', 'Quechua'),
        array('ay_BO', 'Aymara'),
        array('se_NO', 'Northern Sámi'),
        array('ps_AF', 'Pashto')
    );
    
    /**
     * Requests assets (js and css) for the page.
     * 
     * @see FactoryPages306_AdminPage
     * 
     * @since 1.0.0
     * @return void 
     */
    public function assets($scripts, $styles) {
        
        $this->scripts->request('jquery');
        
        $this->scripts->request( array( 
            'control.checkbox'
            ), 'bootstrap' );
        
        $this->styles->request( array( 
            'bootstrap.core', 
            'bootstrap.form-group',
            'bootstrap.separator', 
            'control.checkbox'
            ), 'bootstrap' ); 
        
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/settings.030000.js');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/settings.030000.css');   
    }
    
    /**
     * Renders the page 
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        
        global $sociallockerLangs;
        
        $form = new FactoryForms307_Form(array(
            'scope' => 'sociallocker'
        ));
        
        $form->controlTheme = 'mendeleev-000';
        $form->setProvider( new FactoryForms307_OptionsValueProvider(array(
            'scope' => 'sociallocker'
        )));
        
        $form->add(array(
            
            array(
                'type'      => 'textbox',
                'name'      => 'facebook_appid',
                'title'     => __( 'Facebook App ID', 'sociallocker' ),
                'hint'      => 'A facebook app id. By default, the developer app id is used. If you want to use the Facebook Share button you should register another app id specially for your domain.
                                Please read <a style="font-weight: bold;" target="_blank" href="http://support.onepress-media.com/how-to-register-a-facebook-app/">this article</a> to learn how to register one.'
            ),
            array(
                'type'      => 'dropdown',
                'name'      => 'lang',
                'title'     => __( 'Language of buttons', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'Choose the language that will be used for social buttons.'
            ),
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'interrelation',
                'title'     => 'Interrelation',
                'hint'      => 'Set On to make lockers interrelated. When any interrelated locker on your site is unlocked, the rest others will be unlocked too.<br />If Off, only lockers having the same URLs to like/tweet/+1/share will be unlocked.',
                'default'   => false
            ),   
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'dynamic_theme',
                'title'     => __( 'I use a dynamic theme', 'sociallocker' ),
                'hint'      => 'If your theme loads pages dynamically via ajax, set "On" to get the lockers working.'
            ),
            array(
                'type'      => 'div',
                'id'        => 'onp-dynamic-theme-options',
                'items'     => array(
                    
                    array(
                        'type'      => 'textbox',
                        'name'      => 'dynamic_theme_event',
                        'title'     => __( 'jQuery Events', 'sociallocker' ),
                        'hint'      => 'If pages of your site are loaded dynamically via ajax, it\'s necessary to catch ' . 
                                       'the moment when the page is loaded in order to appear the locker.<br />By default the plugin covers ' .
                                       '99% possible events. So <strong>you don\'t need to set any value here</strong>.<br />' .
                                       'But if you know how it works and sure that it will help, you can put here the javascript event ' .
                                       'that triggers after loading of pages on your site.'
                    )   
                )
            ), 
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'google_analytics',
                'title'     => __( 'Google Analytics', 'sociallocker' ),
                'hint'      => 'If set On, the plugin will generate <a href="https://support.google.com/analytics/answer/1033068?hl=en" target="_blank">events</a> for the Google Analytics when the content is unlocked.<br /><strong>Note:</strong> before enabling this feature, please <a href="https://support.google.com/analytics/answer/1008015?hl=en" target="_blank">make sure</a> that your website contains the Google Analytics tracker code.'
            ),
            array(
                'type'      => 'html',
                'html'      => array($this, 'statsHtml')
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',  
                'name'      => 'tracking',
                'title'     => __( 'Tracking', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'Track how users interact with lockers placed on pages of your site. Makes the statistical data available.'
            ),
            array(
                'type' => 'separator'
            ),  
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'rss',
                'title'     => 'Content for RSS',
                'hint'      => 'Set On to make locked content visible in the RSS feed.',
                'default'   => false
            ),
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'debug',
                'title'     => __( 'Debug', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'if On, lockers will appear always even if they were unlocked already.'
            ),
        ));
        
        if ( isset( $_POST['save-action'] ) ) {
            $form->save();
            
            $selectedLang = $_POST['sociallocker_lang'];
            update_option('sociallocker_lang', $selectedLang);
            
            if ( !empty( $selectedLang ) ) {

                $langItem = null;
                global $sociallockerLangs;
                foreach( $sociallockerLangs as $lang ) {
                    if ( $lang[0] == $selectedLang ) {
                        $langItem = $lang;
                        break;
                    }
                }

                $parts = explode('_', $selectedLang);
                update_option('sociallocker_short_lang', $parts[0]);

                if ( $langItem && isset( $langItem[2] ) ) {
                    update_option('sociallocker_google_lang', $langItem[2] );   
                } else {
                    delete_option('sociallocker_google_lang');   
                }
            }
            
            return $this->redirectToAction('index', array('saved' => 1));
        }

        ?>
        <div class="wrap ">
            <h2><?php _e('Common Settings', 'sociallocker') ?></h2>
            <p style="margin-top: 0px;"><?php _e('These settings are applied to all social lockers.', 'sociallocker') ?></p>
            
            <div class="factory-bootstrap-308">
            <form method="post" class="form-horizontal">

                <?php if ( isset( $_GET['saved'] ) ) { ?>
                <div id="message" class="alert alert-success">
                    <p>The settings have been updated successfully!</p>
                </div>
                <?php } ?>

                <div style="padding-top: 10px;">
                <?php $form->html(); ?>
                </div>
                    
                <div class="form-group form-horizontal">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="control-group controls col-sm-10">
                    <input name="save-action" class="btn btn-primary" type="submit" value="Save changes"/>
                    </div>
                </div>
            
            </form>
            </div>  
                
        </div>
        <?php
    }
    
    /**
     * Render the html block on how much the statistics data takes places.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function statsHtml() {
        global $wpdb;
        
        $dataSizeInBytes = $wpdb->get_var(
            "SELECT round(data_length + index_length) as 'size_in_bytes' FROM information_schema.TABLES WHERE " . 
            "table_schema = '" . DB_NAME . "' AND table_name = '{$wpdb->prefix}so_tracking'");
        
        $count = $wpdb->get_var("SELECT COUNT(*) AS n FROM {$wpdb->prefix}so_tracking");
        $humanDataSize = factory_308_get_human_filesize( $dataSizeInBytes );
        
        ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php _e('Statistical Data', 'sociallocker') ?></label>
                <div class="control-group controls col-sm-10">
                    <p class="onp-sl-inline">
                        <?php if ( $count == 0 ) { ?>
                        <?php printf( __( 'The statistical data is <strong>empty</strong>.', 'sociallocker' ), $humanDataSize ); ?>
                        <?php } else { ?>
                        <?php printf( __( 'The statistical data takes <strong>%s</strong> on your server', 'sociallocker' ), $humanDataSize ); ?>
                        (<a href="<?php $this->actionUrl('clearStatsData') ?>"><?php _e('remove', 'sociallocker') ?></a>)
                        <?php } ?>
                    </p>
                </div>
            </div>
        <?php
    }
    
    /**
     * Clears the statisticals data.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function clearStatsDataAction() {
        
        if ( !isset( $_REQUEST['onp_confirmed'] ) ) {
            return $this->confirm(array(
                'title' => __('Are you sure that you want to clear the current statistical data?', 'wppolice'),
                'description' => __('All the statistical data will be removed.', 'wppolice'),
                'actions' => array(
                    'onp_confirm' => array(
                        'class' => 'btn btn-danger',
                        'title' => __("Yes, I'm sure", 'wppolice'),
                        'url' => $this->getActionUrl('clearStatsData', array(
                            'onp_confirmed' => true
                        ))
                    ),
                    'onp_cancel' => array(
                        'class' => 'btn btn-default',
                        'title' => __("No, return back", 'wppolice'),
                        'url' => $this->getActionUrl('index')
                    ),
                )
            ));
        }
        
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}so_tracking");
        return $this->redirectToAction('index', array('onp_table_cleared' => true));
    }
    
    /**
     * Shows the html block with a confirmation dialog.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function confirm( $data ) {
        ?>
        <div class="onp-page-wrap factory-bootstrap-308" id="onp-confirm-dialog">
            <div id="onp-confirm-dialog-wrap">
                <h1><?php echo $data['title'] ?></h1>
                <p><?php echo $data['description'] ?></p>
                <div class='onp-actions'>
                    <?php foreach( $data['actions'] as $action ) { ?>
                        <a href='<?php echo $action['url'] ?>' class='<?php echo $action['class'] ?>'>
                           <?php echo $action['title'] ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

FactoryPages306::register($sociallocker, 'OnpSL_CommonSettingsPage');