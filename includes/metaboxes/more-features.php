<?php
/**
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
class OnpSL_MoreFeaturesMetaBox extends FactoryMetaboxes320_Metabox
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
    public $title;
    
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
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
        $this->title = __('More Features?', 'sociallocker');
    }
    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes320_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        global $sociallocker;
        $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);
                
        
    ?>
        <div class="factory-bootstrap-325 factory-fontawesome-320">
            
        <div class="sl-header">
            <strong><?php _e('More Features?', 'sociallocker'); ?></strong>
            <p><?php _e('You Use Only 30% of Social Locker!', 'sociallocker'); ?></p>
            <?php if ( FACTORY_FLAT_ADMIN ) { ?>
            <div class="progress progress-striped">
              <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                <span class="sr-only"><?php _e('30% Complete', 'sociallocker'); ?></span>
              </div>
            </div>
            <?php } else { ?>
            <div class="progress progress-danger progress-striped active">
              <div class="bar" style="width: 30%;"></div>
            </div>
            <?php } ?>
        </div>
        <div class="sl-seporator"></div>
        <?php ?>
        <ul>
            <li><span data-target="demo-social-options"><?php _e('More Social Buttons (+4)', 'sociallocker'); ?></span></li>
            <li><span data-target="demo-themes"><?php _e('More themes (+4)', 'sociallocker'); ?></span></li>
            <li><span data-target="demo-blurring-effect"><?php _e('Blurring Effect', 'sociallocker'); ?></span></li>
            <li><span data-target="demo-advanced-options"><?php _e('Advanced options (+8)', 'sociallocker'); ?></span></li>
        </ul>
        <?php 
 ?>
        <div class="sl-seporator"></div>

        <?php if ( !$alreadyActivated ) { ?>
            <div class="sl-footer">
                <?php echo sprintf(__('<a href="%s" class="btn btn-primary btn-large">Try 7-days Trial Version<br /><span>(activate by one click)</span></a><a href="%s" class="sl-buy"> or <strong>buy</strong> the full premium version now!</a>', 'sociallocker'), onp_licensing_324_manager_link($sociallocker->pluginName, 'activateTrial', false), onp_licensing_324_get_purchase_url( $sociallocker )); ?>
            </div>
        <?php } else { ?>
            <div class="sl-footer">
                <?php echo sprintf(__('<a href="%s" class="btn btn-primary btn-large">Get Premium for $24<br /><span>(it will take a pair of minutes)</span></a>', 'sociallocker'), onp_licensing_324_get_purchase_url( $sociallocker ), onp_licensing_324_manager_link($sociallocker->pluginName, 'activateTrial', false)); ?>
            </div>
        <?php } ?>
        
        <div style="display: none">
            <div class="demo-social-options"></div>
            <div class="demo-themes"></div>
            <div class="demo-blurring-effect"></div>   
            <div class="demo-advanced-options"></div>
        </div>
        
        </div>
    <?php
    }
}

FactoryMetaboxes320::register('OnpSL_MoreFeaturesMetaBox', $sociallocker);
