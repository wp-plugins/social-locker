<?php
/**
 * Returns CSS generating rules for the Great Atrractor.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_great_attractor_theme_css_rules() {

    $result = array();
    
    require 'css-rules/container.php';
    require 'css-rules/social-buttons.php';
    require 'css-rules/form-fields.php';
    require 'css-rules/form-buttons.php';
    
    return $result;
}
