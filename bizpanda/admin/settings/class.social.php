<?php
/**
 * A class for the page providing the social settings.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The Social Settings
 * 
 * @since 1.0.0
 */
class OPanda_SocialSettings extends OPanda_Settings  {
    
    public $id = 'social';
    
    /**
     * Shows the header html of the settings screen.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {
        ?>
        <p><?php _e('Set up here your social API keys and app IDs for social buttons.', 'optionpanda') ?></p>
        <?php
    }
    
    /**
     * Returns subscription options.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getOptions() {
        
        $languages = array(
            array('ca_ES', __('Catalan', 'optinpanda')),
            array('cs_CZ', __('Czech', 'optinpanda')),
            array('cy_GB', __('Welsh', 'optinpanda')),
            array('da_DK', __('Danish', 'optinpanda')),
            array('de_DE', __('German', 'optinpanda')),
            array('eu_ES', __('Basque', 'optinpanda')),
            array('en_US', __('English', 'optinpanda')),
            array('es_ES', __('Spanish', 'optinpanda')),
            array('fi_FI', __('Finnish', 'optinpanda')), 
            array('fr_FR', __('French', 'optinpanda')), 
            array('gl_ES', __('Galician', 'optinpanda')), 
            array('hu_HU', __('Hungarian', 'optinpanda')),
            array('it_IT', __('Italian', 'optinpanda')),
            array('ja_JP', __('Japanese', 'optinpanda')),
            array('ko_KR', __('Korean', 'optinpanda')),
            array('nb_NO', __('Norwegian', 'optinpanda')),
            array('nl_NL', __('Dutch', 'optinpanda')),
            array('pl_PL', __('Polish', 'optinpanda')),
            array('pt_BR', __('Portuguese (Brazil)', 'optinpanda')),
            array('pt_PT', __('Portuguese (Portugal)', 'optinpanda')),
            array('ro_RO', __('Romanian', 'optinpanda')),
            array('ru_RU', __('Russian', 'optinpanda')),
            array('sk_SK', __('Slovak', 'optinpanda')),  
            array('sl_SI', __('Slovenian', 'optinpanda')), 
            array('sv_SE', __('Swedish', 'optinpanda')),
            array('th_TH', __('Thai', 'optinpanda')),
            array('tr_TR', __('Turkish', 'optinpanda')), 
            array('ku_TR', __('Kurdish', 'optinpanda')), 
            array('zh_CN', __('Simplified Chinese (China)', 'optinpanda')), 
            array('zh_HK', __('Traditional Chinese (Hong Kong)', 'optinpanda')),
            array('zh_TW', __('Traditional Chinese (Taiwan)', 'optinpanda')), 
            array('af_ZA', __('Afrikaans', 'optinpanda')),
            array('sq_AL', __('Albanian', 'optinpanda')),
            array('hy_AM', __('Armenian', 'optinpanda')),
            array('az_AZ', __('Azeri', 'optinpanda')),
            array('be_BY', __('Belarusian', 'optinpanda')),
            array('bn_IN', __('Bengali', 'optinpanda')),
            array('bs_BA', __('Bosnian', 'optinpanda')),
            array('bg_BG', __('Bulgarian', 'optinpanda')),
            array('hr_HR', __('Croatian', 'optinpanda')),
            array('nl_BE', __('Dutch (België)', 'optinpanda')),
            array('eo_EO', __('Esperanto', 'optinpanda')),
            array('et_EE', __('Estonian', 'optinpanda')),
            array('fo_FO', __('Faroese', 'optinpanda')),
            array('ka_GE', __('Georgian', 'optinpanda')),
            array('el_GR', __('Greek', 'optinpanda')),
            array('gu_IN', __('Gujarati', 'optinpanda')),
            array('hi_IN', __('Hindi', 'optinpanda')),
            array('is_IS', __('Icelandic', 'optinpanda')),
            array('id_ID', __('Indonesian', 'optinpanda')),
            array('ga_IE', __('Irish', 'optinpanda')),
            array('jv_ID', __('Javanese', 'optinpanda')),
            array('kn_IN', __('Kannada', 'optinpanda')),
            array('kk_KZ', __('Kazakh', 'optinpanda')),
            array('la_VA', __('Latin', 'optinpanda')),
            array('lv_LV', __('Latvian', 'optinpanda')),
            array('li_NL', __('Limburgish', 'optinpanda')),
            array('lt_LT', __('Lithuanian', 'optinpanda')), 
            array('mk_MK', __('Macedonian', 'optinpanda')), 
            array('mg_MG', __('Malagasy', 'optinpanda')),
            array('ms_MY', __('Malay', 'optinpanda')),
            array('mt_MT', __('Maltese', 'optinpanda')),
            array('mr_IN', __('Marathi', 'optinpanda')),
            array('mn_MN', __('Mongolian', 'optinpanda')),
            array('ne_NP', __('Nepali', 'optinpanda')),
            array('pa_IN', __('Punjabi', 'optinpanda')),
            array('rm_CH', __('Romansh', 'optinpanda')),
            array('sa_IN', __('Sanskrit', 'optinpanda')),
            array('sr_RS', __('Serbian', 'optinpanda')),
            array('so_SO', __('Somali', 'optinpanda')),
            array('sw_KE', __('Swahili', 'optinpanda')),
            array('tl_PH', __('Filipino', 'optinpanda')),
            array('ta_IN', __('Tamil', 'optinpanda')),
            array('tt_RU', __('Tatar', 'optinpanda')), 
            array('te_IN', __('Telugu', 'optinpanda')),
            array('ml_IN', __('Malayalam', 'optinpanda')),
            array('uk_UA', __('Ukrainian', 'optinpanda')),
            array('uz_UZ', __('Uzbek', 'optinpanda')),
            array('vi_VN', __('Vietnamese', 'optinpanda')),
            array('xh_ZA', __('Xhosa', 'optinpanda')),
            array('zu_ZA', __('Zulu', 'optinpanda')),
            array('km_KH', __('Khmer', 'optinpanda')),
            array('tg_TJ', __('Tajik', 'optinpanda')),
            array('ar_AR', __('Arabic', 'optinpanda')), 
            array('he_IL', __('Hebrew', 'optinpanda')),
            array('ur_PK', __('Urdu', 'optinpanda')),
            array('fa_IR', __('Persian', 'optinpanda')),
            array('sy_SY', __('Syriac', 'optinpanda')),  
            array('yi_DE', __('Yiddish', 'optinpanda')),
            array('gn_PY', __('Guaraní', 'optinpanda')),
            array('qu_PE', __('Quechua', 'optinpanda')),
            array('ay_BO', __('Aymara', 'optinpanda')),
            array('se_NO', __('Northern Sámi', 'optinpanda')),
            array('ps_AF', __('Pashto', 'optinpanda'))
        );
        
        
        
        $options = array();
        
        $options[] = array(
            'type' => 'separator'
        );
   
        $options[] = array(
            'type'      => 'dropdown',
            'name'      => 'lang',
            'title'     => __( 'Language of Buttons', 'optinpanda' ),
            'data'      => $languages,
            'hint'      => __( 'Select the language that will be used for the social buttons in Social Lockers.', 'optinpanda' )
        );
        
        $options[] = array(
            'type' => 'separator'
        );
        
        $options[] = array(
            'type'      => 'textbox',
            'name'      => 'facebook_appid',
            'title'     => __( 'Facebook App ID', 'optinpanda' ),
            'hint'      =>  sprintf( __( 'By default, the developer app id is set. If you want to use the Facebook Share or Facebook Sign-In buttons you need to <a href="%s">register another app</a> id for your website.', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=facebook-app') ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Register App</a>', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=facebook-app') )
        );
        
        $options[] = array(
            'type'      => 'dropdown',
            'way'       => 'buttons',
            'name'      => 'facebook_version',
            'title'     => __( 'Facebook API Version', 'optinpanda' ),
            'default'   => 'v2.3',
            'data'      => array(
                array('v1.0', 'v1.0'),
                array('v2.0', 'v2.0'), 
                array('v2.3', 'v2.3')             
             ),
            'hint'      => __( 'Optional. Use the most recent version of the API (v2.3) but if Facebook buttons or widgets don\'t work on your website try to switch to other versions.', 'optinpanda' )
        );

        $options[] = array(
            'type' => 'separator'
        );

        $options[] = array(
            'type'      => 'dropdown',
            'name'      => 'twitter_use_dev_keys',
            'title'     => __( 'Twitter API Keys', 'optinpanda' ),
            'data'      => array(
                array( 'default', __( 'Use the default keys', 'optinpanda') ),
                array( 'custom', __( 'Use my own Twitter App', 'optinpanda') ),
            ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Register App</a>', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=twitter-app') ),
            'hint'      => sprintf( __( 'The Twitter Sign-In button requires a Twitter App. We prepared one for you by default. But if you worry about security, you can <a href="%s">create an own app</a>. Also by creating your own Twitter app you will be able to change the title, description and image for the "Sign In" popup window.', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=twitter-app') )
        );

        $options[] = array(
            'type'      => 'div',
            'id'        => 'opanda-twitter-custom-options',
            'class'     => 'opanda-hidden',

            'items'     => array(

                array(
                    'type'      => 'textbox',
                    'name'      => 'twitter_consumer_key',
                    'title'     => __( 'Twitter Consumer Key', 'optinpanda' ),
                    'hint'      => __( 'The Twitter Consumer Key of your Twitter App.', 'optinpanda' ),
                    'for'       => array(__('Connect Locker', 'optinpanda'))
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'twitter_consumer_secret',
                    'title'     => __( 'Twitter Consumer Secret', 'optinpanda' ),
                    'hint'      => __( 'The Twitter Consumer Secret of your Twitter App.', 'optinpanda' ),
                    'for'       => array(__('Connect Locker', 'optinpanda'))
                )
            )
        );

        $options[] = array(
            'type' => 'separator'
        );

        $options[] = array(
            'type'      => 'textbox',
            'name'      => 'google_client_id',
            'title'     => __( 'Google Client ID', 'optinpanda' ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Get Client ID</a>', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=google-client-id') ),
            'hint'      => sprintf( __( 'If you want to use the YouTube Subscribe or Google Sign-In buttons, please <a href="%s">create a Client ID</a> for your website.', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=google-client-id') )
        );  

        if ( BizPanda::hasFeature('linkedin') ) {
            
            $options[] = array(
                'type' => 'separator'
            );

            $options[] = array(
                'type'      => 'textbox',
                'name'      => 'linkedin_api_key',
                'title'     => __( 'LinkedIn API Key', 'optinpanda' ),
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Get API Key</a>', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=linkedin-api-key') ),
                'hint'      => sprintf( __( 'If you want to use the LinkedIn Sign-In button, please <a href="%s">get a API Key</a> for your website.', 'optinpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=linkedin-api-key') )
            );
        }
        
        $options[] = array(
            'type' => 'separator'
        );

        return $options;
    }
}

