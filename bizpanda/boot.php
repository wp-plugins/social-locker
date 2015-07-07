<?php

// a condition which allows to create the BizPanda instance only once
if ( defined('OPANDA_ACTIVE') ) { BizPanda::countCallerPlugin(); return; }
define('OPANDA_ACTIVE', true);

define('BIZPANDA_VERSION', 116);

define('OPANDA_WORDPRESS', true);
define('OPANDA_POST_TYPE', 'opanda-item');

define('OPANDA_BIZPANDA_DIR', dirname(__FILE__));
define('OPANDA_BIZPANDA_URL', plugins_url( null, __FILE__ ));

// creating a plugin via the factory
require('libs/factory/core/boot.php');
global $optinpanda;



global $bizpanda;
$bizpanda = new Factory325_Plugin(__FILE__, array( 
    'name' => 'bizpanda', 
    'lang' => 'en_US',
    'version' => '1.1.6',
    'updates' => OPANDA_BIZPANDA_DIR . '/plugin/updates/',
    'styleroller' =>  'http://api.byonepress.com/public/1.0/get/?product=styleroller'
));

// requires factory modules
$bizpanda->load(array(
    array( 'libs/factory/bootstrap', 'factory_bootstrap_329', 'admin' ),
    array( 'libs/factory/font-awesome', 'factory_fontawesome_320', 'admin' ),
    array( 'libs/factory/forms', 'factory_forms_328', 'admin' ),
    array( 'libs/factory/notices', 'factory_notices_323', 'admin' ),
    array( 'libs/factory/pages', 'factory_pages_321', 'admin' ),
    array( 'libs/factory/viewtables', 'factory_viewtables_320', 'admin' ),
    array( 'libs/factory/metaboxes', 'factory_metaboxes_321', 'admin' ),
    array( 'libs/factory/shortcodes', 'factory_shortcodes_320' ),
    array( 'libs/factory/types', 'factory_types_322' )
));

#comp merge
require(OPANDA_BIZPANDA_DIR . '/includes/panda-items.php');
require(OPANDA_BIZPANDA_DIR . '/includes/functions.php');
require(OPANDA_BIZPANDA_DIR . '/includes/assets.php');
require(OPANDA_BIZPANDA_DIR . '/includes/post-types.php');
require(OPANDA_BIZPANDA_DIR . '/includes/shortcodes.php');
#endcomp

if ( is_admin() ) require( OPANDA_BIZPANDA_DIR . '/admin/boot.php' );