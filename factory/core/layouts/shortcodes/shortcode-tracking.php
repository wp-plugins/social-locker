<?php

$tr_shortcodes_data = array();
$tr_shortcodes = array();

/**
 * Registes a new shortcode to process.
 * @param string $shortcodeName
 */
function factory_fr103_tr_register_shortcode( $shortcodeName, $callback ) {
    global $tr_shortcodes_data, $tr_shortcodes;
    
    $tr_shortcodes_data[$shortcodeName] = array(
        'name' => $shortcodeName,
        'callback' => $callback
    );

    $tr_shortcodes[] = $shortcodeName;    
}

/**
 * Checks content.
 * @param string $content
 * @param integer $postId
 */
function factory_fr103_tr_check_content( $content, $postId ) {
    global $tr_shortcodes_data, $tr_shortcodes;
    if (count($tr_shortcodes) == 0 ) return;
    
    $matches = array();

    $tagregexp = join( '|', $tr_shortcodes );

    $start = '(\[(' . $tagregexp . ')([^\[\]]*)\])';
    $end = '\[\/\2\]';
    $pattern = '/' . $start . '(.*?)' . $end . '/is';
    
    $count = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
    if (!$count) return $content;
    
    foreach($matches as $order => $match) {

        $shortcode = $match[2];
        $attrContent = str_replace('\\', '', $match[3] );
        $innerContent = str_replace('\\', '', $match[4]);

        $attrs = shortcode_parse_atts($attrContent);

        call_user_func( 
            $tr_shortcodes_data[$shortcode]['callback'], 
            $shortcode, $attrs, $innerContent, $postId
        );
    }
}