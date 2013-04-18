<?php
/**
 * Factory Core
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 */

// Checks if the one is already loaded.
// For example, other plugin may use Factory with the same version.
// Via this code, we prevent to load the same version of Factory twice.
if (defined('FACTORY_FR106_LOADED')) return;
define('FACTORY_FR106_LOADED', true);

// Absolute path and URL to the files and resources of Factory Core.
define('FACTORY_FR106_DIR', dirname(__FILE__));
define('FACTORY_FR106_URL', plugins_url(null,  __FILE__ ));

// - Load layouts

// kernel
include(FACTORY_FR106_DIR. '/layouts/kernel/list.class.php');
include(FACTORY_FR106_DIR . '/layouts/kernel/providers/value-provider.interface.php');
include(FACTORY_FR106_DIR . '/layouts/kernel/providers/value-provider-fake.class.php');
include(FACTORY_FR106_DIR . '/layouts/kernel/providers/value-provider-meta.class.php');

// Asset managment
include(FACTORY_FR106_DIR . '/layouts/assets/assets-list.class.php');
include(FACTORY_FR106_DIR . '/layouts/assets/script-list.class.php');
include(FACTORY_FR106_DIR . '/layouts/assets/style-list.class.php');

// Metaboxes
include(FACTORY_FR106_DIR . '/layouts/metaboxes/metabox.class.php');
include(FACTORY_FR106_DIR . '/layouts/metaboxes/metabox-collection.class.php');
include(FACTORY_FR106_DIR . '/layouts/metaboxes/metabox-manager.class.php');
include(FACTORY_FR106_DIR . '/layouts/metaboxes/default-metaboxes/save-metabox.class.php');

// Shortcodes
include(FACTORY_FR106_DIR . '/layouts/shortcodes/shortcodes-manager.class.php');
include(FACTORY_FR106_DIR . '/layouts/shortcodes/shortcode-tracking.php');
include(FACTORY_FR106_DIR . '/layouts/shortcodes/shortcode.class.php');

// Types
include(FACTORY_FR106_DIR . '/layouts/types/type.class.php');
include(FACTORY_FR106_DIR . '/layouts/types/type-menu.class.php');

// View tables
include(FACTORY_FR106_DIR . '/layouts/viewtables/viewtable-columns.class.php');
include(FACTORY_FR106_DIR . '/layouts/viewtables/viewtable.class.php');

// Pages
include(FACTORY_FR106_DIR . '/layouts/pages/page.class.php');
include(FACTORY_FR106_DIR . '/layouts/pages/admin-page.class.php');
include(FACTORY_FR106_DIR . '/layouts/pages/admin-page-manager.class.php');

// Plugin activation
include(FACTORY_FR106_DIR . '/layouts/installation/activation.class.php');
include(FACTORY_FR106_DIR . '/layouts/installation/update.class.php');

// Plugin loader and managment of plugin items

include(FACTORY_FR106_DIR . '/layouts/plugin/functions.php');
include(FACTORY_FR106_DIR . '/layouts/plugin/plugin.class.php');

// Transient
include(FACTORY_FR106_DIR . '/layouts/transient/transient.functions.php');

// Admin notices
if ( is_admin() ) {
    include(FACTORY_FR106_DIR . '/layouts/notices/notice-functions.php');
    include(FACTORY_FR106_DIR . '/layouts/notices/notice-manager.class.php');
}

// ... and others that are represented via Factory Modules


