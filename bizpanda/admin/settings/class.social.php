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
            array('ca_ES', __('Catalan', 'bizpanda')),
            array('cs_CZ', __('Czech', 'bizpanda')),
            array('cy_GB', __('Welsh', 'bizpanda')),
            array('da_DK', __('Danish', 'bizpanda')),
            array('de_DE', __('German', 'bizpanda')),
            array('eu_ES', __('Basque', 'bizpanda')),
            array('en_US', __('English', 'bizpanda')),
            array('es_ES', __('Spanish', 'bizpanda')),
            array('fi_FI', __('Finnish', 'bizpanda')), 
            array('fr_FR', __('French', 'bizpanda')), 
            array('gl_ES', __('Galician', 'bizpanda')), 
            array('hu_HU', __('Hungarian', 'bizpanda')),
            array('it_IT', __('Italian', 'bizpanda')),
            array('ja_JP', __('Japanese', 'bizpanda')),
            array('ko_KR', __('Korean', 'bizpanda')),
            array('nb_NO', __('Norwegian', 'bizpanda')),
            array('nl_NL', __('Dutch', 'bizpanda')),
            array('pl_PL', __('Polish', 'bizpanda')),
            array('pt_BR', __('Portuguese (Brazil)', 'bizpanda')),
            array('pt_PT', __('Portuguese (Portugal)', 'bizpanda')),
            array('ro_RO', __('Romanian', 'bizpanda')),
            array('ru_RU', __('Russian', 'bizpanda')),
            array('sk_SK', __('Slovak', 'bizpanda')),  
            array('sl_SI', __('Slovenian', 'bizpanda')), 
            array('sv_SE', __('Swedish', 'bizpanda')),
            array('th_TH', __('Thai', 'bizpanda')),
            array('tr_TR', __('Turkish', 'bizpanda')), 
            array('ku_TR', __('Kurdish', 'bizpanda')), 
            array('zh_CN', __('Simplified Chinese (China)', 'bizpanda')), 
            array('zh_HK', __('Traditional Chinese (Hong Kong)', 'bizpanda')),
            array('zh_TW', __('Traditional Chinese (Taiwan)', 'bizpanda')), 
            array('af_ZA', __('Afrikaans', 'bizpanda')),
            array('sq_AL', __('Albanian', 'bizpanda')),
            array('hy_AM', __('Armenian', 'bizpanda')),
            array('az_AZ', __('Azeri', 'bizpanda')),
            array('be_BY', __('Belarusian', 'bizpanda')),
            array('bn_IN', __('Bengali', 'bizpanda')),
            array('bs_BA', __('Bosnian', 'bizpanda')),
            array('bg_BG', __('Bulgarian', 'bizpanda')),
            array('hr_HR', __('Croatian', 'bizpanda')),
            array('nl_BE', __('Dutch (Belgie)', 'bizpanda')),
            array('eo_EO', __('Esperanto', 'bizpanda')),
            array('et_EE', __('Estonian', 'bizpanda')),
            array('fo_FO', __('Faroese', 'bizpanda')),
            array('ka_GE', __('Georgian', 'bizpanda')),
            array('el_GR', __('Greek', 'bizpanda')),
            array('gu_IN', __('Gujarati', 'bizpanda')),
            array('hi_IN', __('Hindi', 'bizpanda')),
            array('is_IS', __('Icelandic', 'bizpanda')),
            array('id_ID', __('Indonesian', 'bizpanda')),
            array('ga_IE', __('Irish', 'bizpanda')),
            array('jv_ID', __('Javanese', 'bizpanda')),
            array('kn_IN', __('Kannada', 'bizpanda')),
            array('kk_KZ', __('Kazakh', 'bizpanda')),
            array('la_VA', __('Latin', 'bizpanda')),
            array('lv_LV', __('Latvian', 'bizpanda')),
            array('li_NL', __('Limburgish', 'bizpanda')),
            array('lt_LT', __('Lithuanian', 'bizpanda')), 
            array('mk_MK', __('Macedonian', 'bizpanda')), 
            array('mg_MG', __('Malagasy', 'bizpanda')),
            array('ms_MY', __('Malay', 'bizpanda')),
            array('mt_MT', __('Maltese', 'bizpanda')),
            array('mr_IN', __('Marathi', 'bizpanda')),
            array('mn_MN', __('Mongolian', 'bizpanda')),
            array('ne_NP', __('Nepali', 'bizpanda')),
            array('pa_IN', __('Punjabi', 'bizpanda')),
            array('rm_CH', __('Romansh', 'bizpanda')),
            array('sa_IN', __('Sanskrit', 'bizpanda')),
            array('sr_RS', __('Serbian', 'bizpanda')),
            array('so_SO', __('Somali', 'bizpanda')),
            array('sw_KE', __('Swahili', 'bizpanda')),
            array('tl_PH', __('Filipino', 'bizpanda')),
            array('ta_IN', __('Tamil', 'bizpanda')),
            array('tt_RU', __('Tatar', 'bizpanda')), 
            array('te_IN', __('Telugu', 'bizpanda')),
            array('ml_IN', __('Malayalam', 'bizpanda')),
            array('uk_UA', __('Ukrainian', 'bizpanda')),
            array('uz_UZ', __('Uzbek', 'bizpanda')),
            array('vi_VN', __('Vietnamese', 'bizpanda')),
            array('xh_ZA', __('Xhosa', 'bizpanda')),
            array('zu_ZA', __('Zulu', 'bizpanda')),
            array('km_KH', __('Khmer', 'bizpanda')),
            array('tg_TJ', __('Tajik', 'bizpanda')),
            array('ar_AR', __('Arabic', 'bizpanda')), 
            array('he_IL', __('Hebrew', 'bizpanda')),
            array('ur_PK', __('Urdu', 'bizpanda')),
            array('fa_IR', __('Persian', 'bizpanda')),
            array('sy_SY', __('Syriac', 'bizpanda')),  
            array('yi_DE', __('Yiddish', 'bizpanda')),
            array('gn_PY', __('Guarani', 'bizpanda')),
            array('qu_PE', __('Quechua', 'bizpanda')),
            array('ay_BO', __('Aymara', 'bizpanda')),
            array('se_NO', __('Northern Sami', 'bizpanda')),
            array('ps_AF', __('Pashto', 'bizpanda'))
        );
        
        
        
        $options = array();
        
        $options[] = array(
            'type' => 'separator'
        );
   
        $options[] = array(
            'type'      => 'dropdown',
            'name'      => 'lang',
            'title'     => __( 'Language of Buttons', 'bizpanda' ),
            'data'      => $languages,
            'hint'      => __( 'Select the language that will be used for the social buttons in Social Lockers.', 'bizpanda' )
        );
        
        $options[] = array(
            'type' => 'separator'
        );
        
        $options[] = array(
            'type'      => 'textbox',
            'name'      => 'facebook_appid',
            'title'     => __( 'Facebook App ID', 'bizpanda' ),
            'hint'      =>  sprintf( __( 'By default, the developer app id is set. If you want to use the Facebook Share or Facebook Sign-In buttons you need to <a href="%s">register another app</a> id for your website.', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=facebook-app') ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Register App</a>', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=facebook-app') )
        );
        
        $options[] = array(
            'type'      => 'dropdown',
            'way'       => 'buttons',
            'name'      => 'facebook_version',
            'title'     => __( 'Facebook API Version', 'bizpanda' ),
            'default'   => 'v2.3',
            'data'      => array(
                array('v1.0', 'v1.0'),
                array('v2.0', 'v2.0'), 
                array('v2.3', 'v2.3')             
             ),
            'hint'      => __( 'Optional. Use the most recent version of the API (v2.3) but if Facebook buttons or widgets don\'t work on your website try to switch to other versions.', 'bizpanda' )
        );

        $options[] = array(
            'type' => 'separator'
        );

        $options[] = array(
            'type'      => 'dropdown',
            'name'      => 'twitter_use_dev_keys',
            'title'     => __( 'Twitter API Keys', 'bizpanda' ),
            'data'      => array(
                array( 'default', __( 'Use the default keys', 'bizpanda') ),
                array( 'custom', __( 'Use my own Twitter App', 'bizpanda') ),
            ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Register App</a>', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=twitter-app') ),
            'hint'      => sprintf( __( 'The Twitter Sign-In button requires a Twitter App. We prepared one for you by default. But if you worry about security, you can <a href="%s">create an own app</a>. Also by creating your own Twitter app you will be able to change the title, description and image for the "Sign In" popup window.', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=twitter-app') )
        );

        $options[] = array(
            'type'      => 'div',
            'id'        => 'opanda-twitter-custom-options',
            'class'     => 'opanda-hidden',

            'items'     => array(

                array(
                    'type'      => 'textbox',
                    'name'      => 'twitter_consumer_key',
                    'title'     => __( 'Twitter Consumer Key', 'bizpanda' ),
                    'hint'      => __( 'The Twitter Consumer Key of your Twitter App.', 'bizpanda' ),
                    'for'       => array(__('Connect Locker', 'bizpanda'))
                ),
                array(
                    'type'      => 'textbox',
                    'name'      => 'twitter_consumer_secret',
                    'title'     => __( 'Twitter Consumer Secret', 'bizpanda' ),
                    'hint'      => __( 'The Twitter Consumer Secret of your Twitter App.', 'bizpanda' ),
                    'for'       => array(__('Connect Locker', 'bizpanda'))
                )
            )
        );

        $options[] = array(
            'type' => 'separator'
        );

        $options[] = array(
            'type'      => 'textbox',
            'name'      => 'google_client_id',
            'title'     => __( 'Google Client ID', 'bizpanda' ),
            'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Get Client ID</a>', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=google-client-id') ),
            'hint'      => sprintf( __( 'If you want to use the YouTube Subscribe or Google Sign-In buttons, please <a href="%s">create a Client ID</a> for your website.', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=google-client-id') )
        );  

        if ( BizPanda::hasFeature('linkedin') ) {
            
            $options[] = array(
                'type' => 'separator'
            );

            $options[] = array(
                'type'      => 'textbox',
                'name'      => 'linkedin_api_key',
                'title'     => __( 'LinkedIn Client ID', 'bizpanda' ),
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default">Get Client ID</a>', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=linkedin-api-key') ),
                'hint'      => sprintf( __( 'If you want to use the LinkedIn Sign-In button, please <a href="%s">get a Client ID</a> for your website.', 'bizpanda' ), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=linkedin-api-key') )
            );
        }
        
        $options[] = array(
            'type' => 'separator'
        );

        return $options;
    }
}

