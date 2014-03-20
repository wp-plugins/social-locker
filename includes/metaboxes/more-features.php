<?php  /**
 * The file contains a class to configure the metabox "More Features?".
 * 
 * Created via the Factory Metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The class to configure the metabox "More Features?".
 * 
 * @since 1.0.0
 */
class OnpSL_MoreFeaturesMetaBox extends FactoryMetaboxes305_Metabox
{
    /**
     * A visible title of the metabox.
     * 
     * Inherited from the class FactoryMetabox.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @var string
     */
    public $title = 'More Features?';
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'core';
    
    /**
     * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $context = 'side';
    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes305_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        global $sociallocker;
        
    ?>
        <div class="factory-bootstrap-305 factory-fontawesome-305">
            
        <div class="sl-header">
            <strong>More Features?</strong>
            <p>You Use Only 30% of Social Locker!</p>
            <?php if ( FACTORY_FLAT_ADMIN_030800 ) { ?>
            <div class="progress progress-striped"">
              <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                <span class="sr-only">30% Complete</span>
              </div>
            </div>
            <?php } else { ?>
            <div class="progress progress-danger progress-striped active">
              <div class="bar" style="width: 30%;"></div>
            </div>
            <?php } ?>
        </div>
        <div class="sl-seporator"></div>
        <ul>
            <li><span data-target="demo-social-options">Extra social buttons (+4)</span></li>
            <li><span data-target="demo-themes">Extra themes (+2)</span></li>
            <li><span data-target="demo-visibility-options">Visibility options (+3)</span></li>
            <li><span data-target="demo-advanced-options">Advanced options (+5)</span></li>
        </ul>
        <div class="sl-seporator"></div>
        
        <?php if ( FACTORY_FLAT_ADMIN_030800 ) { ?>
            <?php if ( !get_option('fy_trial_activated_' . $sociallocker->pluginName, false) ) { ?>
                <div class="sl-footer">
                    <a href="<?php onp_licensing_307_manager_link($sociallocker->pluginName, 'activateTrial') ?>" class="btn btn-primary btn-large">
                        Try 7-days Trial Version<br /><span>(activate by one click)</span>
                    </a>
                    <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="sl-buy">or <strong>buy</strong> the full premium version now!</a>
                </div>
            <?php } else { ?>
                <div class="sl-footer">
                    <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="btn btn-primary btn-large">
                        Get Premium for $21<br /><span>(it will take no more a minute)</span>
                    </a>
                    <a href="<?php onp_licensing_307_manager_link($sociallocker->pluginName, 'activateTrial') ?>" class="sl-buy">or <strong>try</strong> the trial version</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <?php if ( !get_option('fy_trial_activated_' . $sociallocker->pluginName, false) ) { ?>
                <div class="sl-footer">
                    <a href="<?php onp_licensing_307_manager_link($sociallocker->pluginName, 'activateTrial') ?>" class="btn btn-danger btn-large">
                        Try 7-days Trial Version<br /><span>(activate by one click)</span>
                    </a>
                    <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="sl-buy">or <strong>buy</strong> the full premium version now!</a>
                </div>
            <?php } else { ?>
                <div class="sl-footer">
                    <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="btn btn-danger btn-large">
                        Get Premium for $21<br /><span>(it will take no more a minute)</span>
                    </a>
                    <a href="<?php onp_licensing_307_manager_link($sociallocker->pluginName, 'activateTrial') ?>" class="sl-buy">or <strong>try</strong> the trial version</a>
                </div>
            <?php } ?>
        <?php } ?>
        
        <div style="display: none">
            <div class="demo-social-options"></div>
            <div class="demo-themes"></div>
            <div class="demo-visibility-options"></div>   
            <div class="demo-advanced-options"></div>
        </div>
        
        </div>
    <?php
    }
}

FactoryMetaboxes305::register('OnpSL_MoreFeaturesMetaBox');