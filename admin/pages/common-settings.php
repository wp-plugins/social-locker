<?php

// includes scripts and styles for this page
add_action('admin_enqueue_scripts', 'sociallocker_common_settings_scripts');
function sociallocker_common_settings_scripts($hook) {

    if (!empty( $hook ) && $hook == 'social-locker_page_sociallocker_settings') {
        
        wp_enqueue_script( 
            'sociallocker-bootstrap', 
            SOCIALLOCKER_PLUGIN_URL . '/factory/core/assets/js/bootstrap.js', 
            array('jquery'), 
            false, true
        );
        
        wp_enqueue_style( 
            'sociallocker-bootstrap', 
            SOCIALLOCKER_PLUGIN_URL . '/factory/core/assets/css/bootstrap.css'
        );  
        
        wp_enqueue_script( 
            'onp-common-settings', 
            SOCIALLOCKER_PLUGIN_URL . '/assets/admin/js/common-settings.020020.js', 
            array('jquery'), 
            false, true
        );  
    }
}

// list of available languages
$sociallockerLangs = array(
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

// function that is used to save a form
function sociallocker_save_settings()
{
    update_option('sociallocker_facebook_appid', $_POST['sociallocker_facebook_appid']);
    
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
    
    if ( isset($_POST['sociallocker_tracking']) ) {
        update_option('sociallocker_tracking', true);  
    } else {
        delete_option('sociallocker_tracking');    
    }
    
    if ( isset($_POST['sociallocker_debug']) ) {
        update_option('sociallocker_debug', true);  
    } else {
        delete_option('sociallocker_debug');    
    } 
    
    if ( isset($_POST['sociallocker_dynamic_theme']) ) {
        update_option('sociallocker_dynamic_theme', true);  
    } else {
        delete_option('sociallocker_dynamic_theme');    
    }   
    
    update_option('sociallocker_dynamic_theme_event', $_POST['sociallocker_dynamic_theme_event']);    
}

// function that reders the form
function sociallocker_settings() {
    global $sociallockerLangs;
    if ( isset( $_POST['save-action'] ) ) sociallocker_save_settings();
?>
<div class="wrap">
    <h2>Settings</h2>
    <p style="margin-top: 0px;">Common options for all social lockers.</p>
    
    <?php if ( isset( $_POST['save-action'] ) ) { ?>
    <div id="message" class="updated" style="margin: 0px; margin-top: 10px; margin-bottom: 10px; font-weight: bold;">
        <p>The setting have been updated successfully!</p></div>
    <?php } ?>

<div class="wpbootstrap">
<form method="post" class="form-horizontal">

<div id="facebook" class="panel like-panel">

    
 <fieldset style="padding-top: 10px;">
     <div class="control-group">
        <label class="control-label" for="sociallocker_facebook_appid">Facebook App ID</label>
        <div class="controls">
            <input type="text" class="short"  name="sociallocker_facebook_appid" id="sociallocker_facebook_appid" value="<?php echo get_option('sociallocker_facebook_appid') ?>" style="width: 180px;" />
            <span class="help-block">
                A facebook app id. By default, a developer app id is used. If you want to use a Facebook Share button you should register another app id specially for your domain.<br />
                Please read <a style="font-weight: bold;" target="_blank" href="http://support.onepress-media.com/how-to-register-a-facebook-app/">this article</a> to learn how to register one.
            </span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="sociallocker_lang">Language of buttons</label>
        <div class="controls">
            
            <select name="sociallocker_lang" id="sociallocker_lang">
            <?php foreach ($sociallockerLangs as $lang) { ?>
                <option value="<?php echo $lang[0] ?>" <?php if ($lang[0] == get_option('sociallocker_lang')) { echo " selected='selected'"; }?>>
                    <?php echo $lang[1] ?>
                </option>
            <?php } ?>
            </select>
            <span class="help-block">
                Choose the language that will be used for social buttons.
            </span>
        </div>
    </div> 
    <?php ?>
     
    <div style="border-top: 1px solid #eee; padding-bottom: 25px;"></div>

    <div class="control-group">
        <label class="control-label" for="sociallocker_dynamic_theme">I use a dynamic theme</label>
        <?php 
        $checked = get_option('sociallocker_dynamic_theme') ? 'checked="checked"' : '';
        ?>
        <div class="controls">
            
            <div class="btn-group pi-checkbox" data-toggle="buttons-radio">
                <button type="button" class="btn true <?php if ($checked) echo 'active' ?>" data-value="true">Yes</button>
                <button type="button" class="btn false <?php if (!$checked) echo 'active' ?>" data-value="false">No</button>                 
            </div>

            <input style="display: none;" type='checkbox' value='1' <?php echo $checked ?> name='sociallocker_dynamic_theme' id='sociallocker_dynamic_theme' /> 
            
            <span class="help-block">
                If your theme loads pages dynamically via ajax, say "yes" to get the lockers working.
            </span>
        </div>
    </div>
     
    <div id="onp-dynamic-theme-options" style="display:none;">
        <div class="control-group">
            <label class="control-label" for="sociallocker_dynamic_theme_event">jQuery Event</label>

            <div class="controls">

                <input type="text" class="short"  name="sociallocker_dynamic_theme_event" id="sociallocker_dynamic_theme_event" value="<?php echo get_option('sociallocker_dynamic_theme_event') ?>" style="width: 180px;" />

                <span class="help-block">
                    If pages of your site are loaded dynamically via ajax, it's necessary to catch the moment when the page is loaded in order to appear the locker.<br />Type here the javascript event that triggers after the page loading.
                </span>
            </div>
        </div>
    </div>
    
    <div style="border-top: 1px solid #eee; padding-bottom: 25px;"></div>
    
    <div class="control-group">
        <label class="control-label" for="sociallocker_debug">Debug</label>
        <?php 
        $checked = get_option('sociallocker_debug') ? 'checked="checked"' : '';
        ?>
        <div class="controls">
            
            <div class="btn-group pi-checkbox" data-toggle="buttons-radio">
                <button type="button" class="btn true <?php if ($checked) echo 'active' ?>" data-value="true">On</button>
                <button type="button" class="btn false <?php if (!$checked) echo 'active' ?>" data-value="false">Off</button>                 
            </div>

            <input style="display: none;" type='checkbox' value='1' <?php echo $checked ?> name='sociallocker_debug' id='sociallocker_debug' /> 
            
            <span class="help-block">
                if on, lockers will appear always even if you unlocked it already.
            </span>
        </div>
    </div>

</fieldset>
        
<div class="form-actions">
    <input name="save-action" class="button-primary" type="submit" value="Save changes"/>
</div>



    <div style="clear: both;"></div>
</div>
    

</form>
</div>
</div>
<?php
}