<?php
/**
 * The file contains a page that shows settings for the plugin.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OnpSL_CommonSettingsPage extends FactoryPages300_AdminPage  {
 
    public $menuTitle = 'Common Settings';
    public $menuPostType = 'social-locker';
    
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
        array('es_LA', 'Spanish'),
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
    
    public function assets() {
        $this->scripts->request('jquery');
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/settings.030000.js');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/settings.030000.css');   
    }
    
    /**
     * Shows an index page where a user can set settings.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        
        global $sociallockerLangs;
        
        $form = new FactoryForms300_Form(array(
            'scope' => 'sociallocker'
        ));
        
        $form->controlTheme = 'mendeleev-300';
        $form->setProvider( new FactoryForms300_OptionsValueProvider(array(
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
                'type'      => 'checkbox',
                'name'      => 'tracking',
                'title'     => __( 'Tracking', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'Allows to track of unlocking events on your site and provides statistics.'
            ),
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'name'      => 'dynamic_theme',
                'title'     => __( 'I use a dynamic theme', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'If your theme loads pages dynamically via ajax, say "yes" to get the lockers working.'
            ),
            array(
                'type'      => 'div',
                'id'        => 'onp-dynamic-theme-options',
                'items'     => array(
                    
                    array(
                        'type'      => 'textbox',
                        'name'      => 'dynamic_theme_event',
                        'title'     => __( 'jQuery Events', 'sociallocker' ),
                        'data'      => $this->languages,
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
                'name'      => 'debug',
                'title'     => __( 'Debug', 'sociallocker' ),
                'data'      => $this->languages,
                'hint'      => 'if On, lockers will appear always even if they were unlocked already.'
            ),
        ));
        
        if ( isset( $_POST['save-action'] ) ) {
            $form->save();
            return $this->redirectToAction('index', array('saved' => 1));
        }

        ?>
        <div class="wrap ">
            <h2>Common Settings</h2>
            <p style="margin-top: 0px;">Common settings for all social lockers.</p>
            
            <div class="factory-bootstrap-300">
            <form method="post" class="form-horizontal">

                <?php if ( isset( $_GET['saved'] ) ) { ?>
                <div id="message" class="alert alert-success">
                    <p>The settings have been updated successfully!</p>
                </div>
                <?php } ?>

                <div style="padding-top: 10px;">
                <?php $form->html(); ?>
                </div>
                    
                <div class="form-actions form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                    <input name="save-action" class="btn btn-primary" type="submit" value="Save changes"/>
                    </div>
                </div>
            
            </form>
            </div>  
                
        </div>
        <?php
    }
}

FactoryPages300::register($sociallocker, 'OnpSL_CommonSettingsPage');