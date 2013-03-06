<?php
/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnePressFR100LicenseManagerAdminPage extends FactoryFR100AdminPage  {
        
    public $id = 'license-manager';
    public $menuTitle = 'License Manager';
    
    public $purchaseUrl = '';
    public $purchasePrice = '$';
    
    /**
     * [MAGIC] Magic method that configures assets for a page.
     * 
     * @param FactoryFR100ScriptList $scripts    Scripts that will be included.
     * @param FactoryFR100StyleList $styles      Styles that will be includes.
     */
    public function assets(FactoryFR100ScriptList $scripts, FactoryFR100StyleList $styles) {
        
        $styles->add(ONEPRESS_FR100_URL . '/assets/css/license-manager.css');
        $scripts->add(ONEPRESS_FR100_URL . '/assets/js/license-manager.js');   
    }
    
    // ------------------------------------------------------------------
    // Page Actions
    // ------------------------------------------------------------------

    
    /**
     * Shows current license type.
     */
    public function indexAction( $sender = 'index', $error = null ) {
        $licenseManager = $license = $this->plugin->license;
        $licenseKey = isset( $_POST['licensekey'] ) ? $_POST['licensekey'] : null;
        
        if ( isset( $_POST['licensekey'] ) ) {
            $licenseKey = $_POST['licensekey'];
            $error = $licenseManager->activateKey( trim( $licenseKey ) );
            if ( !is_wp_error($error) ) $licenseKey = null;
        }
        
        $licenseData = $licenseManager->data;
        $remained = round( ( $licenseData['Expired'] - time() ) / (60 * 60 * 24), 2 );
        $isInfinity = empty( $licenseData['Expired']);
        
        ?>
        <div class="wpbootstrap license-manager-content <?php echo $licenseData['Category'] ?>-license-manager-content" id="license-manager">
            <div class="license-message <?php echo $licenseData['Category'] ?>-license-message">
                <?php if (is_wp_error($error)) { 
                    $code = $error->get_error_code(); ?>
                
                    <?php if ( $code == 'license_invalide_secret') { ?>
                    <div class="alert alert-error">
                        <h4 class="alert-heading">The request has been rejected by the License Server.</h4>
                        <p><?php echo $error->get_error_message() ?></p>
                        <p>Try to <a href="<?php $this->actionUrl('siteRegistration', array('redirect' => urlencode( $this->getActionUrl('index') ))) ?>">click here</a> to register your site again.</p>
                    </div>                    
                    <?php } else { ?>
                
                        <?php if ($sender == 'index' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to connect to the Licensing Server.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>Please <a href="<?php $this->actionUrl('activateKeyManualy', array('key' => $_POST['licensekey'] )) ?>">click here</a> for trying to activate your key manualy.</p>
                        </div>
                        <?php } elseif ($sender == 'index') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to apply the specified key.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>

                        <?php if ($sender == 'trial' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to connect to the Licensing Server.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>Please <a href="<?php $this->actionUrl('activateTrialManualy' ) ?>">click here</a> for trying to activate your trial manualy.</p>
                        </div>
                        <?php } elseif ($sender == 'trial') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to get trial license key.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>
                
                        <?php if ($sender == 'delete-key' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to connect to the Licensing Server.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>Please <a href="<?php $this->actionUrl('deleteKeyManualy' ) ?>">click here</a> for trying to delete key manualy.</p>
                        </div>
                        <?php } elseif ($sender == 'delete-key') { ?>
                        <div class="alert alert-error">
                            <strong>Unable to delete the license key.</strong>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>
                
                        <?php if ($sender == 'check-updates' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to connect to the Licensing Server.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } elseif ($sender == 'check-updates') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Unable to check updates.</h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>                
                
                    <?php } ?>
                <?php } else { ?>
                
                        <?php if ($sender == 'trial') { ?>
                            <?php ?>
                            <div class="alert alert-normal alert-warning-icon">
                                <strong>Your trial version has been activated successfully.</strong>
                                <p>Please check the <a href="plugins.php">Plugins</a> page and update the plugin to complete activation if it's needed.</p>                     
                            </div>
                            <?php 
 ?>
                        <?php } ?> 
                
                        <?php if ($sender == 'index' && isset( $_POST['licensekey']) ) { ?>
                            <?php ?>
                            <div class="alert alert-normal alert-warning-icon">
                                <strong>The key has been activated successfully.</strong>
                                <p>Please check the <a href="plugins.php">Plugins page</a> and update the plugin to complete activation if it's needed.</p>                   
                            </div>
                            <?php 
 ?>
                        <?php } ?>
                

                        <?php if ($sender == 'delete-key') { ?>
                        <div class="alert alert-normal alert-warning-icon">
                            <strong>The key has been deleted successfully.</strong>
                            <p>Please check the <a href="plugins.php">Plugins</a> page and update the plugin to complete deletion if it's needed.</p>       
                        </div>
                        <?php } ?>          
                
                        <?php if ($sender == 'check-updates') { ?>
                        <div class="alert alert-normal alert-warning-icon">
                            <h4 class="alert-heading">The updates have been checked successfully.</h4>
                            <p>
                            <?php if ( $licenseManager->isActualVersion() ) { ?>
                                You use the actual version of the plugin.
                            <?php } else { ?>
                                The <?php echo $licenseManager->versionCheck['Build'] ?>-<?php echo $licenseManager->versionCheck['Version'] ?> version is available to update.
                                <a href="plugins.php">Click here</a> to get the update.
                            <?php } ?>
                            </p>
                        </div>
                        <?php } ?>  
                
                <?php } ?>
            </div>
            
            <div class="license-manager-box">
                <div class="license-details">
                    <?php ?>
                        <?php if ( $licenseManager->hasUpgrade() ) { ?>
                        <a href="<?php echo $this->purchaseUrl ?>" id="purchase-premium">
                            <span class="btn btn-gold btn-inner-wrap">
                            <i class="icon-star icon-white"></i> Upgrade to Premium for <?php echo $this->purchasePrice ?> <i class="icon-star icon-white"></i>
                            </span>
                        </a>
                        <?php } ?>
                    <?php 
 ?>             
                    <p>Your current license:</p>
                    <div class="license-details-block <?php echo $licenseData['Category'] ?>-details-block">
                        
                        <?php if ( $licenseManager->hasKey() ) { ?>
                        <a href="<?php $this->actionUrl('deleteKey') ?>" class="btn btn-small license-delete-button"><i class="icon-remove-sign"></i> Delete Key</a>
                        <?php } ?>
                        
                        <h3><?php echo $licenseData['Title'] ?></h3>
                        <?php if ( $licenseManager->hasKey() ) { ?>
                        <div class="licanse-key-identity"><?php echo $licenseData['Key'] ?></div>
                        <?php } ?>
                        
                        <div class="licanse-key-description">
                        <?php if ( $licenseManager->data['Build'] == 'premium' ) { ?>
                            <?php if ( $licenseManager->data['Category'] == 'free' ) { ?>
                                <p>Please, activate the plugin to get started. Enter a key you received with the plugin 
                                into the form below. Don't know where the key is? <a href="#" id="open-faq">Click here</a>.
                                </p>
                                <p class="activate-trial-hint">
                                    Also you can activate a <a href="<?php $this->actionUrl('activateTrial') ?>">trial version</a> for 7 days to test the plugin on this site.
                                </p>
                            <?php } else { ?>
                                <?php echo $licenseData['Description'] ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ( $licenseManager->data['Category'] == 'free' ) { ?>
                                <p>Public License is a GPLv2 compatible license. It allows you to change this version of the plugin and to use the plugin free. 
                                    Please remember this license covers only free edition of the plugin. Premium versions are distributed with other type of a license.
                                </p>
                                <p class="activate-trial-hint">
                                    Also you can <a href="<?php $this->actionUrl('activateTrial') ?>">activate</a> a premium version for a trial period (7 days). Click <a target="_blank" href="<?php echo $this->purchaseUrl ?>">here</a> to learn more about the premium version.
                                </p>
                            <?php } else { ?>
                                <?php echo $licenseData['Description'] ?>
                            <?php } ?>
                        <?php } ?>
                        </div>
                        
                        <table class="license-params" colspacing="0" colpadding="0">
                            <tr>
                                <td class="license-param license-param-domain">
                                    <span class="license-value"><?php echo $this->plugin->license->domain ?></span>
                                    <span class="license-value-name">domain</span>
                                </td>   
                                <td class="license-param license-param-version">
                                    <span class="license-value"><?php echo $this->plugin->version ?> <small>version</small></span>
                                    <?php if ( $licenseManager->isVersionChecked() ) { ?>
                                        <?php if ( $licenseManager->isActualVersion() ) { ?>
                                            <span class="license-value-name">up-to-date</span>
                                        <?php } else { ?>
                                            <span class="license-value-name">
                                                <a href="plugins.php" class="link-to-upgrade">
                                                <?php echo $licenseManager->versionCheck['Build'] ?>-<?php echo $licenseManager->versionCheck['Version'] ?> available
                                                </a>
                                            </span>
                                        <?php } ?>
                                    <?php } else { ?>
                                    <span class="license-value-name"><span>up-to-date</span></span>
                                    <?php } ?>
                                </td>  
                                <td class="license-param license-param-days">
                                    <span class="license-value"><?php echo $licenseManager->data['Build'] ?></span>                                   
                                    <span class="license-value-name">build</span>
                                </td>
                                <td class="license-param license-param-days">
                                    <?php if ( $licenseManager->isExpired() ) {?>
                                        <span class="license-value">EXPIRED!</span>
                                        <span class="license-value-name">please update the key</span>
                                    <?php } else { ?>  
                                        <span class="license-value">                               
                                            <?php if ( $isInfinity ) {?>infinity<?php } else { ?>
                                                <?php echo $remained; ?><small> day(s)</small>
                                            <?php } ?>
                                        </span>
                                        <span class="license-value-name">remained</span>
                                    <?php } ?>    
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="license-input">
                    <form action="<?php $this->actionUrl("index") ?>" method="post">
                        <p>Have a key to activate the premium version? Paste it here:<p>
                            <a href="#" class="btn" id="license-submit">
                                <i class="icon-ok-sign"></i> Submit Key
                            </a>  
                        <div class="license-key-wrap">
                            <input type="text" id="license-key" name="licensekey" value="<?php echo $licenseKey ?>" />
                        </div>
                        <p style="margin-top: 10px;">
                            <a href="<?php echo $this->purchaseUrl ?>">Leam more</a> about the premium version and get the license key to activate it now!
                        </p>
                    </form>
                </div>
            </div>
            <div id="plugin-update-block">
                <?php if ( $licenseManager->data['Build'] !== 'free' ) { ?>
                    <?php if ( !$licenseManager->isVersionChecked() ) { ?>
                        <?php if ( isset( $licenseManager->versionCheck['Checked'] ) ) { ?>
                            The upadtes were checked at <strong><?php echo date( 'g:i a, j M y', $licenseManager->versionCheck['Checked'] ) ?></strong>.
                        <?php } else { ?>
                            The upadtes were checked <strong>never</strong>.
                        <?php } ?>
                    <?php } else { ?>
                        <?php if ( $licenseManager->isActualVersion() ) { ?>
                        The upadtes were checked at <?php echo date( 'g:i a, d M y', $licenseManager->versionCheck['Checked'] ) ?>, you use the up-to-date version. 
                        <?php } else { ?>
                        The upadtes were checked at <?php echo date( 'g:i a, d M y', $licenseManager->versionCheck['Checked'] ) ?>, <strong><?php echo $licenseManager->versionCheck['Version'] ?> is available</strong>.
                        <?php } ?>
                    <?php } ?>
                    Click <a href="<?php $this->actionUrl('checkUpdates') ?>">here</a> to check new updates manually.
                <?php } ?>
                <span class="gray-link">[ <a href="<?php echo $this->actionUrl('internalKeys') ?>">internal keys</a> ]</span>       
            </div>
            <div id="faq-block">
                <ul>
                    <li>
                        <a class="faq-header" id="how-to-find-the-key">I purchased the plugin, but I cannot find the license key. Where I can find it?</a>
                        <div>
                            <p>
                                The premium version of the plugin is sold on <a href="http://onepress-media.com/portfolio" target="_blank">CodeCanyon</a>. 
                                After purchase visit your Downloads section and click Licence Certificate. 
                                Find Item Purchase Code in the document and paste it into the form. Please, see 
                                image below:
                            </p>
                            <p style="text-align: center;">
                                <img src="<?php echo ONEPRESS_FR100_URL . '/assets/img/how-to-find-key.png' ?>" />
                            </p>
                        </div>
                    </li>
                    <li>
                        <a class="faq-header">The plugin I purchased comes with the bonus. Where I can find a key to activate the bonus?</a>
                        <div>
                            <p>
                                You can activate the bonus plugin via the key you got for the principal plugin.
                            </p>
                        </div>
                    </li>
                    <li>
                        <a class="faq-header">Is it possible to get the premium version without the License Manager?</a>
                        <div>
                            <p>
                                Yes, it's possible if you want to add the plugin to a template for sale or want to distribute the plugin as a part of your product. 
                                Please contact us to discuss details: support@onepress-media.com
                            </p>
                        </div>
                    </li>    
                </ul>
            </div>
        </div>
        <?php
    }
    
    public function clearLicenseDataAction() {
        $licenseManager = $license = $this->plugin->license;
        $licenseManager->clearLicenseData();
        $this->internalKeysAction( 'reset' ); 
    }
    
    /**
     * Deletes a current key.
     */
    public function deleteKeyAction() {
        
        $licenseManager = $license = $this->plugin->license;
        if ( $licenseManager->hasKey()) {
            $error = $licenseManager->deleteKey();
            $this->indexAction( 'delete-key', $error ); 
            return;
        }
        
        $this->indexAction(); 
    }   
    
    /**
     * Trys to activate the trial license and then redirect to the index page.
     */
    public function activateTrialAction() {
        $licenseManager = $this->plugin->license;
        $error = $licenseManager->activateTrial();            
        $this->indexAction( 'trial', $error ); 
    }
    
    /**
     * Allows to activate a key manualy.
     */
    public function activateKeyManualyAction() {
        $licenseManager = $this->plugin->license;
        $url = $licenseManager->getLinkToActivateKey( $_GET['key']);

        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->activateKeyManualy( $_POST['response'] );
            $this->indexAction( 'index', $error );
            exit;
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('activateKeyManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;">Key Activation</h2>
                <p style="margin-top: 0px;">Please perfome the following steps to activate the plugin manualy.</p>
                <ul>
                    <li>
                        1. <a target="_blank" href="<?php echo $url ?>">Click here</a> to send activation request.
                    </li>
                    <li>
                        2. Copy the code from the field on the site and paste it below, then submit the form:
                        <textarea name="response" class="license-reponse-code" placeholder="Response code from the Licensing Server"></textarea>
                    </li>
                </ul>
                <a href="#" class="btn btn-large btn-inverse btn-uppercase" id="manual-trial-submit">
                    <i class="icon-ok-sign icon-white"></i> verify code
                </a>  
            </div>
            </form>
        </div>
        <?php
    }   
    
    /**
     * Deletes a current key.
     */
    public function deleteKeyManualyAction() {
        $licenseManager = $this->plugin->license;
        $url = $licenseManager->getLinkToDeleteKey();

        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->deleteKeyManualy( $_POST['response'] );
            $this->indexAction( 'delete-key', $error );
            exit;
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('deleteKeyManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;">Key Deactivation</h2>
                <p style="margin-top: 0px;">Please perfome the following steps to activate the plugin manualy.</p>
                <ul>
                    <li>
                        1. <a target="_blank" href="<?php echo $url ?>">Click here</a> to send deactivation request.
                    </li>
                    <li>
                        2. Copy the code from the field on the site and paste it below, then submit the form:
                        <textarea name="response" class="license-reponse-code" placeholder="Response code from the Licensing Server"></textarea>
                    </li>
                </ul>
                <a href="#" class="btn btn-large btn-inverse btn-uppercase" id="manual-trial-submit">
                    <i class="icon-ok-sign icon-white"></i> verify code
                </a>  
            </div>
            </form>
        </div>
        <?php
    }   
    
    public function activateTrialManualyAction() {
        $licenseManager = $this->plugin->license;
        $url = $licenseManager->getLinkToActivateTrial();

        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->activateKeyManualy( $_POST['response'] );
            $this->indexAction( 'trial', $error );
            exit;
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('activateTrialManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;">Trial Activation</h2>
                <p style="margin-top: 0px;">Please perfome the following steps to activate the plugin manualy.</p>
                <ul>
                    <li>
                        1. <a target="_blank" href="<?php echo $url ?>">Click here</a> to send activation request.
                    </li>
                    <li>
                        2. Copy the code from the field on the site and paste it below, then submit the form:
                        <textarea name="response" class="license-reponse-code" placeholder="Response code from the Licensing Server"></textarea>
                    </li>
                </ul>
                <a href="#" class="btn btn-large btn-inverse btn-uppercase" id="manual-trial-submit">
                    <i class="icon-ok-sign icon-white"></i> verify code
                </a>  
            </div>
            </form>
        </div>
        <?php
    }
    
    public function checkUpdatesAction() {
        $error = $this->plugin->license->checkUpdates();
        $this->indexAction( 'check-updates', $error ); 
    }
    
    public function internalKeysAction( $sender = 'index' ) {
        $licenseManager = $this->plugin->license;
        $saved = false;
        
        if ( isset( $_POST['site_secret'] ) ) {
            update_option('fy_license_site_secret', $_POST['site_secret']);
            $licenseManager->siteSecret = $_POST['site_secret'];
            $saved = true;
        }
        
        $siteSecret = $licenseManager->siteSecret;
        $keySecret = !empty($licenseManager->data) ? $licenseManager->data['KeySecret'] : '';
        
        ?>
            <div class="wpbootstrap" style="max-width: 800px;">
                <form method="post" class="form-horizontal" action="<?php echo $this->actionUrl('internalKeys') ?>">

                <div id="facebook" class="panel like-panel">
                    <h2 style="margin-bottom: 10px;">License Manager Internal Keys</h2>
                    <p style="margin-top: 0px; margin-bottom: 25px;">You actually don't need to change something here. Please change the values below only if OnePress supports ask you to do it.</p>
                
                    <?php if ( $saved ) { ?>
                    <div class="alert alert-success" style="margin-bottom: 25px;">
                        The changes has been saved successfully!
                    </div>
                    <?php } ?>
                    
                    <?php if ( $sender == 'reset' ) { ?>
                    <div class="alert alert-success" style="margin-bottom: 25px;">
                        The license has been reset successfully!
                    </div>
                    <?php } ?>                    
                    
                    <div class="control-group">
                        <label class="control-label" for="site_secret">Site Secret</label>
                        <div class="controls">
                            <input type="text" name="site_secret" id="site_secret" value="<?php echo $siteSecret ?>" />
                        </div>
                    </div> 
                    
                    <div class="control-group">
                        <label class="control-label" for="key_secret">Key Secret</label>
                        <div class="controls">
                            <input type="text" name="key_secret" id="key_secret" value="<?php echo $keySecret ?>" />
                        </div>
                    </div>           
               
                    <div class="form-actions">
                        <input name="save-action" class="button-primary" type="submit" value="Save changes"/>
                        <a style="margin-left: 10px;" href="<?php echo $this->actionUrl('clearLicenseData') ?>">Reset license</a>
                    </div>

                    <div style="clear: both;"></div>
                </div>


            </form>
            </div>
        <?php
    }
}