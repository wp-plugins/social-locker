<?php
#build: free, premium

/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnePressFR110LicenseManagerAdminPage extends FactoryFR110AdminPage  {
    
    public $id = 'license-manager';
    public $purchasePrice = '$';
    
    public function __construct( $plugin) { 
        parent::__construct($plugin);
        $this->menuTitle = __('License Manager', 'onepress');
    }
    
    /**
     * [MAGIC] Magic method that configures assets for a page.
     * 
     * @param FactoryScriptList $scripts    Scripts that will be included.
     * @param FactoryStyleList $styles      Styles that will be includes.
     */
    public function assets(FactoryFR110ScriptList $scripts, FactoryFR110StyleList $styles) {
        
        $styles->add(ONEPRESS_FR110_URL . '/assets/css/license-manager.css');
        $scripts->add(ONEPRESS_FR110_URL . '/assets/js/license-manager.js');   
    }

    // ------------------------------------------------------------------
    // Page Actions
    // ------------------------------------------------------------------

    
    /**
     * Shows current license type.
     */
    public function indexAction() {

        $licenseManager = $license = $this->plugin->license;
        $updatesManager = $this->plugin->updates;
        $licenseKey = isset( $_POST['licensekey'] ) ? trim( $_POST['licensekey'] ) : null;
        
        $scope = isset( $_GET['scope'] ) ? $_GET['scope'] : null;  
        
        if ( isset( $_POST['licensekey'] ) ) {
            $scope = 'submit-key';
            $licenseKey = $_POST['licensekey'];
            $error = $licenseManager->activateKey( trim( $licenseKey ) );
            if ( !is_wp_error($error) ) $licenseKey = null;
        } else {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : null;
            $message = isset( $_GET['message'] ) ? $_GET['message'] : null; 
            $error = null;
            
            if ( $code && $message ) {
                $error = new WP_Error($code, base64_decode($message));
            }
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
                        <h4 class="alert-heading"><?php _e('The request has been rejected by the License Server.', 'onepress') ?></h4>
                        <p><?php echo $error->get_error_message() ?></p>
                        <p>
                            <?php printf( 
                                    __('Please <a href="%1$s">click here</a> for trying to activate your key manualy.', 'onepress'),
                                    $this->getActionUrl('activateKeyManualy', array('key' => $_POST['licensekey'] )) )?>
                        </p>
                    </div>      
                    <?php } elseif ( $code == 'invalid_license_data' ) { ?>        
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('The license data is invalid', 'onepress') ?></h4>
                            <p><?php _e('The server returned invalid license data. If you tried to submit or delete key manually please make sure that you copied and pasted the server response code entirely.', 'onepress') ?></p>
                        </div>
                    <?php } else { ?>
                
                        <?php if ($scope == 'submit-key' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to connect to the Licensing Server.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>
                                <?php printf( 
                                        __('Please <a href="%1$s">click here</a> for trying to activate your key manualy.', 'onepress'),
                                        $this->getActionUrl('activateKeyManualy', array('key' => $_POST['licensekey'] )) )?>
                            </p>
                        </div>
                        <?php } elseif ($scope == 'submit-key') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to apply the specified key.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>

                        <?php if ($scope == 'trial' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to connect to the Licensing Server.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>
                                <?php printf( 
                                        __('Please <a href="%1$s">click here</a> for trying to activate your trial manualy.', 'onepress'),
                                        $this->getActionUrl('activateTrialManualy') )?>
                            </p>
                        </div>
                        <?php } elseif ($scope == 'trial') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to get a trial license key.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>
                
                        <?php if ($scope == 'delete-key' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to connect to the Licensing Server.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                            <p>
                                <?php printf( 
                                        __('Please <a href="%1$s">click here</a> for trying to delete key manualy.', 'onepress'),
                                        $this->getActionUrl('deleteKeyManualy') )?>
                            </p>
                        </div>
                        <?php } elseif ($scope == 'delete-key') { ?>
                        <div class="alert alert-error">
                            <strong><?php _e('Unable to delete the license key.', 'onepress') ?></strong>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>
                
                        <?php if ($scope == 'check-updates' && substr($code, 0, 4) == 'http') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to connect to the Licensing Server.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } elseif ($scope == 'check-updates') { ?>
                        <div class="alert alert-error">
                            <h4 class="alert-heading"><?php _e('Unable to check updates.', 'onepress') ?></h4>
                            <p><?php echo $error->get_error_message() ?></p>
                        </div>
                        <?php } ?>                
                
                    <?php } ?>
                <?php } else { ?>
                
                        <?php if ($scope == 'trial') { ?>
                            <?php ?>
                            <div class="alert alert-normal alert-warning-icon">
                                <strong><?php _e('Your trial version has been activated successfully.', 'onepress') ?></strong>
                                <p><?php _e('Please check the <a href="plugins.php">Plugins</a> page and update the plugin to complete activation if it\'s needed.', 'onepress') ?></p>                     
                            </div>
                            <?php 
 ?>
                        <?php } ?> 
                
                        <?php if ($scope == 'submit-key' ) { ?>
                            <?php ?>
                            <div class="alert alert-normal alert-warning-icon">
                                <strong><?php _e('The key has been activated successfully.', 'onepress') ?></strong>
                                <p><?php _e('Please check the <a href="plugins.php">Plugins</a> page and update the plugin to complete activation if it\'s needed.', 'onepress') ?></p>                    
                            </div>
                            <?php 
 ?>
                        <?php } ?>
                
                        <?php if ($scope == 'delete-key') { ?>
                        <div class="alert alert-normal alert-warning-icon">
                            <strong><?php _e('The key has been deleted successfully.', 'onepress') ?></strong>
                            <p><?php _e('Please check the <a href="plugins.php">Plugins</a> page and update the plugin to complete deletion if it\'s needed.', 'onepress') ?></p>    
                        </div>
                        <?php } ?>          
                
                        <?php if ($scope == 'check-updates') { ?>
                        <div class="alert alert-normal">
                            <strong><?php _e('The updates have been checked successfully.', 'onepress') ?></strong>
                            <p>
                            <?php if ( $updatesManager->isActualVersion() ) { ?>
                                <?php _e('You use the actual version of the plugin.', 'onepress') ?>
                            <?php } else { ?>
                                <?php if ( $updatesManager->needChangeAssembly() ) { ?>
                                <?php printf( 
                                        __('You need to upgrade to the %1$s version. <a href="plugins.php">Click here</a> to get the update.', 'onepress'), $this->plugin->license->build  ) ?>
                                <?php } else { ?>
                                <?php printf( 
                                        __('The %1$s version is available to download. <a href="plugins.php">Click here</a> to get the update.', 'onepress'),
                                        $updatesManager->lastCheck['Build'] . '-' . $updatesManager->lastCheck['Version'] ) ?>
                                <?php } ?>
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
                        <a href="<?php echo $this->plugin->options['premium'] ?>" id="purchase-premium">
                            <span class="btn btn-gold btn-inner-wrap">
                            <?php if ( !empty( $this->purchasePrice ) ) { ?>
                            <i class="icon-star icon-white"></i> <?php printf( __('Upgrade to Premium for %1$s', 'onepress'), $this->purchasePrice ) ?> <i class="icon-star icon-white"></i>
                            <?php } else { ?>
                            <i class="icon-star icon-white"></i> <?php _e('Upgrade to Premium', 'onepress') ?> <i class="icon-star icon-white"></i>
                            <?php } ?>
                            </span>
                        </a>
                        <?php } ?>
                    <?php 
 ?>   
                    <?php if ( empty( $this->plugin->pluginTitle ) ) { ?>
                    <p><?php _e('Your current license:', 'onepress') ?></p>
                    <?php } else { ?>
                    <p><?php printf( __('Your current license for %1$s:', 'onepress'), $this->plugin->pluginTitle ) ?></p>   
                    <?php } ?>
                    <div class="license-details-block <?php echo $licenseData['Category'] ?>-details-block">
                        
                        <?php if ( $licenseManager->hasKey() ) { ?>
                        <a href="<?php $this->actionUrl('deleteKey') ?>" class="btn btn-small license-delete-button"><i class="icon-remove-sign"></i> <?php _e('Delete Key', 'onepress') ?></a>
                        <?php } ?>
                        
                        <h3><?php echo $licenseData['Title'] ?></h3>
                        <?php if ( $licenseManager->hasKey() ) { ?>
                        <div class="licanse-key-identity"><?php echo $licenseData['Key'] ?></div>
                        <?php } ?>
                        
                        <div class="licanse-key-description">
                        <?php if ( $licenseManager->data['Build'] == 'premium' ) { ?>
                            <?php if ( $licenseManager->data['Category'] == 'free' ) { ?>
                                <p>
                                    <?php _e('Please, activate the plugin to get started. Enter a key you received with the plugin into the form below. Don\'t know where the key is? <a href="#" id="open-faq">Click here</a>.', 'onepress') ?>
                                </p>
                                <p class="activate-trial-hint">
                                    <?php printf( __('Also you can activate a <a href="%1$s">trial version</a> for 7 days to test the plugin on this site.', 'onepress'), $this->getActionUrl('activateTrial') ) ?>
                                </p>
                            <?php } else { ?>
                                <?php echo $licenseData['Description'] ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ( $licenseManager->data['Category'] == 'free' ) { ?>
                                <p><?php _e('Public License is a GPLv2 compatible license allowing you to change and use this version of the plugin for free. Please keep in mind this license covers only free edition of the plugin. Premium versions are distributed with other type of a license.') ?>
                                </p>
                                <p class="activate-trial-hint">
                                    <?php printf( __('Also you can <a href="%1$s">activate</a> a premium version for a trial period (7 days).', 'onepress'), $this->getActionUrl('activateTrial') ) ?>
                                    <?php printf( __('Click <a target="_blank" href="%1$s">here</a> to learn more about the premium version.', 'onepress'), $this->plugin->options['premium'] ) ?>
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
                                    <span class="license-value-name"><?php _e('domain', 'onepress') ?></span>
                                </td>   
                                <td class="license-param license-param-version">
                                    <span class="license-value"><?php echo $this->plugin->version ?> <small><?php _e('version', 'onepress') ?></small></span>
                                    <?php if ( $updatesManager->isVersionChecked() ) { ?>
                                        <?php if ( $updatesManager->isActualVersion() ) { ?>
                                            <span class="license-value-name"><?php _e('up-to-date', 'onepress') ?></span>
                                        <?php } else { ?>
                                            <?php if ( $updatesManager->needChangeAssembly() ) { ?>
                                            <span class="license-value-name">
                                                <a href="plugins.php" class="link-to-upgrade">
                                                <?php printf( __('upgrade to %s', 'onepress'), $this->plugin->license->build ) ?>
                                                </a>
                                            </span>    
                                            <?php } else { ?>
                                            <span class="license-value-name">
                                                <a href="plugins.php" class="link-to-upgrade">
                                                <?php echo $updatesManager->lastCheck['Build'] ?>-<?php echo $updatesManager->lastCheck['Version'] ?> <?php _e('available', 'onepress') ?>
                                                </a>
                                            </span>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                    <span class="license-value-name"><span><?php _e('up-to-date', 'onepress') ?></span></span>
                                    <?php } ?>
                                </td>  
                                <td class="license-param license-param-days">
                                    <span class="license-value"><?php echo $licenseManager->data['Build'] ?></span>                                   
                                    <span class="license-value-name"><?php _e('assembly', 'onepress') ?></span>
                                </td>
                                <td class="license-param license-param-days">
                                    <?php if ( $licenseManager->isExpired() ) {?>
                                        <span class="license-value"><?php _e('EXPIRED!', 'onepress') ?></span>
                                        <span class="license-value-name"><?php _e('please update the key', 'onepress') ?></span>
                                    <?php } else { ?>  
                                        <span class="license-value">                               
                                            <?php if ( $isInfinity ) {?><?php _e('infinity', 'onepress') ?><?php } else { ?>
                                                <?php echo $remained; ?><small> <?php _e('day(s)', 'onepress') ?></small>
                                            <?php } ?>
                                        </span>
                                        <span class="license-value-name"><?php _e('remained', 'onepress') ?></span>
                                    <?php } ?>    
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="license-input">
                    <form action="<?php $this->actionUrl("index") ?>" method="post">
                        <p><?php _e('Have a key to activate the premium version? Paste it here:', 'onepress') ?><p>
                            <a href="#" class="btn" id="license-submit">
                                <i class="icon-ok-sign"></i> <?php _e('Submit Key', 'onepress') ?>
                            </a>  
                        <div class="license-key-wrap">
                            <input type="text" id="license-key" name="licensekey" value="<?php echo $licenseKey ?>" />
                        </div>
                        <p style="margin-top: 10px;">
                            <?php printf( __('<a href="%1$s">Lean more</a> about the premium version and get the license key to activate it now!', 'onepress'), $this->plugin->options['premium'] ) ?>
                        </p>
                    </form>
                </div>
            </div>
            <div id="plugin-update-block">
                <?php if ( $licenseManager->data['Build'] !== 'free' ) { ?>
                    <?php if ( !$updatesManager->isVersionChecked() ) { ?>
                        <?php if ( isset( $updatesManager->lastCheck['Checked'] ) ) { ?>
                            <?php printf( __('The upadtes were checked at <strong>%1$s</strong>.', 'onepress'), date( 'g:i a, j M y', $updatesManager->lastCheck['Checked'] ) ) ?>
                        <?php } else { ?>
                            <?php _e('The updates were checked <strong>never</strong>.', 'onepress') ?>
                        <?php } ?>
                    <?php } else { ?>
                        <?php if ( $updatesManager->isActualVersion() ) { ?>
                            <?php printf( __('The updates were checked at <strong>%1$s</strong>, you use the up-to-date version. ', 'onepress'), date( 'g:i a, j M y', $updatesManager->lastCheck['Checked'] ) ) ?>
                        <?php } else { ?>
                            <?php printf( __('The updates were checked at <strong>%1$s</strong>, <strong>%2$s</strong>. ', 'onepress'), date( 'g:i a, j M y', $updatesManager->lastCheck['Checked'] ), $updatesManager->lastCheck['Version'] ) ?>
                        <?php } ?>
                    <?php } ?>
                    <?php printf( __('Click <a href="%1$s">here</a> to check updates manually.', 'onepress'), $this->getActionUrl('checkUpdates') ) ?>
                <?php } ?>
                <span class="gray-link">[ <a href="<?php echo $this->getActionUrl('internalKeys') ?>"><?php _e('internal keys', 'onepress') ?></a> ]</span>       
            </div>
            <div id="faq-block">
                <ul>
                    <li>
                        <a class="faq-header" id="how-to-find-the-key">
                            <?php _e('I purchased the plugin, but I cannot find the license key. Where is it?', 'onepress') ?>
                        </a>
                        <div>
                            <p>
                                <?php _e('The premium version of the plugin is sold on <a href="http://onepress-media.com/portfolio" target="_blank">CodeCanyon</a>.', 'onepress') ?>
                                <?php _e('After purchasing visit your Downloads section and click Licence Certificate.', 'onepress') ?>
                                <?php _e('Find Item Purchase Code in the text document and paste it into the form above.', 'onepress') ?>
                            </p>
                            <p style="text-align: center;">
                                <img src="<?php echo ONEPRESS_FR110_URL . '/assets/img/how-to-find-key.png' ?>" />
                            </p>
                        </div>
                    </li>
                    <li>
                        <a class="faq-header">
                            <?php _e('The plugin I purchased comes with the bonus. Where I can find a key to activate the bonus?', 'onepress') ?>
                        </a>
                        <div>
                            <p>
                                <?php _e('You can activate the bonus plugin via the key you got for the principal plugin.', 'onepress') ?>
                            </p>
                        </div>
                    </li>
                    <li>
                        <a class="faq-header">
                            <?php _e('Is it possible to get the premium version without the License Manager?', 'onepress') ?>
                        </a>
                        <div>
                            <p>
                                <?php _e('Yes, it\'s possible if you want to distribute the plugin as a part of your product. Please contact us to discuss details: support@onepress-media.com', 'onepress') ?>
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
            
            if (is_wp_error($error)) {
                $this->redirectToAction('index', array(
                    'scope' => 'delete-key', 
                    'code' => $error->get_error_code(), 
                    'message' => base64_encode( $error->get_error_message())));
            }
            $this->redirectToAction('index', array('scope' => 'delete-key'));
        }
        
        $this->indexAction(); 
    }   
    
    /**
     * Trys to activate the trial license and then redirect to the index page.
     */
    public function activateTrialAction() {
        $licenseManager = $this->plugin->license;
        $error = $licenseManager->activateTrial();   
        
        if (is_wp_error($error)) {
            $this->redirectToAction('index', array(
                'scope' => 'trial', 
                'code' => $error->get_error_code(), 
                'message' => base64_encode( $error->get_error_message())));
        }
        $this->redirectToAction('index', array('scope' => 'trial'));
    }
    
    /**
     * Allows to activate a key manualy.
     */
    public function activateKeyManualyAction() {
        $licenseManager = $this->plugin->license;


        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->activateKeyManualy( $_POST['response'] );
            if (is_wp_error($error)) {
                $this->redirectToAction('index', array(
                    'scope' => 'submit-key', 
                    'code' => $error->get_error_code(), 
                    'message' => base64_encode( $error->get_error_message())));
            }
            $this->redirectToAction('index', array('scope' => 'submit-key'));
        } else {
            $this->requestUrl = $licenseManager->getLinkToActivateKey( $_GET['key'] );  
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('activateKeyManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;"><?php _e('Key Activation', 'onepress') ?></h2>
                <p style="margin-top: 0px;"><?php _e('Please perfome the following steps to activate the plugin manualy.', 'onepress') ?></p>
                <ul>
                    <li>
                        1. <?php printf( __('<a href="%s" target="_blank">Click here</a> to send the activation request.', 'onepress'), $this->requestUrl ) ?>
                    </li>
                    <li>
                        2. <?php _e('Copy the code from the field on the site and paste it below, then submit the form:', 'onepress') ?>
                        <textarea name="response" class="license-reponse-code" placeholder="<?php _e('Response code from the Licensing Server', 'onepress') ?>"></textarea>
                    </li>
                </ul>
                <a href="#" class="btn btn-large btn-inverse btn-uppercase" id="manual-trial-submit">
                    <i class="icon-ok-sign icon-white"></i> <?php _e('verify code') ?>
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
        $this->requestUrl = $licenseManager->getLinkToDeleteKey();

        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->deleteKeyManualy( $_POST['response'] );
            
            if (is_wp_error($error)) {
                $this->redirectToAction('index', array(
                    'scope' => 'delete-key', 
                    'code' => $error->get_error_code(), 
                    'message' => base64_encode( $error->get_error_message())));
            }
            $this->redirectToAction('index', array('scope' => 'delete-key', 'code' => 'ok'));
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('deleteKeyManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;"><?php _e('Key Deactivation', 'onepress') ?></h2>
                <p style="margin-top: 0px;"><?php _e('Please perfome the following steps to activate the plugin manualy.', 'onepress') ?></p>
                <ul>
                    <li>
                        1. <?php printf( __('<a href="%s" target="_blank">Click here</a> to send the deactivation request.', 'onepress'), $this->requestUrl ) ?>
                    </li>
                    <li>
                        2. <?php _e('Copy the code from the field on the site and paste it below, then submit the form:', 'onepress') ?>
                        <textarea name="response" class="license-reponse-code" placeholder="<?php _e('Response code from the Licensing Server', 'onepress') ?>"></textarea>
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
        
        $this->requestUrl = $licenseManager->getLinkToActivateTrial();

        if ( isset( $_POST['response'] ) ) {
            $error = $licenseManager->activateKeyManualy( $_POST['response'] );
            
            if (is_wp_error($error)) {
                $this->redirectToAction('index', array(
                    'scope' => 'trial', 
                    'code' => $error->get_error_code(), 
                    'message' => base64_encode( $error->get_error_message())));
            }
            $this->redirectToAction('index', array('scope' => 'trial'));
        }
        
        ?>
        <div class="wpbootstrap license-manager-content" id="activate-key-manual">
            <form action="<?php $this->actionUrl('activateTrialManualy') ?>" method="post">
            <div class="license-manager-box">
                <h2 style="margin-bottom: 10px;">Trial Activation</h2>
                <p style="margin-top: 0px;">Please perfome the following steps to activate the plugin manualy.</p>
                <ul>
                    <li>
                        1. <?php printf( __('<a href="%s" target="_blank">Click here</a> to send the activation request.', 'onepress'), $this->requestUrl ) ?>
                    </li>
                    <li>
                        2. <?php _e('Copy the code from the field on the site and paste it below, then submit the form:', 'onepress') ?>
                        <textarea name="response" class="license-reponse-code" placeholder="<?php _e('Response code from the Licensing Server', 'onepress') ?>"></textarea>
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
        $error = $this->plugin->updates->checkUpdates();
        
        if (is_wp_error($error)) {
            $this->redirectToAction('index', array(
                'scope' => 'check-updates', 
                'code' => $error->get_error_code(), 
                'message' => base64_encode( $error->get_error_message())));
        }
        $this->redirectToAction('index', array('scope' => 'check-updates'));
    }
    
    public function internalKeysAction( $sender = 'index' ) {
        $licenseManager = $this->plugin->license;
        
        $saved = false;
        
        if ( isset( $_POST['site_secret'] ) ) {
            update_option('fy_license_site_secret', trim( $_POST['site_secret'] ));
            $licenseManager->secret = trim( $_POST['site_secret'] );
            $saved = true;
        }
        
        $siteSecret = $licenseManager->secret;
        $keySecret = ( !empty($licenseManager->data ) && isset($licenseManager->data['KeySecret']) ) 
                ? $licenseManager->data['KeySecret'] 
                : '';
        
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
                    </div>

                    <div style="clear: both;"></div>
                </div>


            </form>
            </div>
        <?php
    }
}