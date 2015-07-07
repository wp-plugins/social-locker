<?php

add_action('wp_ajax_opanda_avatar', 'opanda_avatar');


function opanda_avatar() {    

    $leadId = isset( $_GET['opanda_lead_id'] ) ? intval( $_GET['opanda_lead_id'] ) : 0;
    if ( empty( $leadId) ) exit;
    
    $size = isset( $_GET['opanda_size'] ) ? intval( $_GET['opanda_size'] ) : 40;
    
    if ( $size > 500 ) $size = 500;
    if ( $size <= 0 ) $size = 40;
    
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/leads.php';
    $imageSource = OPanda_Leads::getLeadField( $leadId, 'externalImage' );

    if ( empty( $imageSource ) || !function_exists('wp_get_image_editor') ) exit;
    
    $upload_dir = wp_upload_dir(); 
    $basePath = $upload_dir['path'] . '/bizpanda/avatars/';
    
    if (!file_exists($basePath) && !is_dir($basePath)) mkdir($basePath, 0777, true );
	
    $pathAvatar = $basePath . $leadId . 'x' . $size . '.jpeg';
    $pathOriginal = $basePath . $leadId . 'x' . $size . '_org.jpeg';
    
    $response = wp_remote_get($imageSource);
    if ( 
        is_wp_error( $response ) || 
        !isset( $response['headers']['content-type'] ) || 
        !isset( $response['body'] ) ||  
        empty( $response['body'] ) ||  
        !preg_match( "/image/i", $response['headers']['content-type'] ) ) {

        OPanda_Leads::removeLeadField($leadId, 'externalImage');
        exit;
    }
    
    file_put_contents($pathOriginal, $response['body']);
    $image = wp_get_image_editor( $pathOriginal );
    
    if ( is_wp_error( $image ) ) {
        OPanda_Leads::removeLeadField($leadId, 'externalImage');
        exit;
    }
    
    $image->resize( $size, $size, true );
    
    $image->set_quality( 90 );
    $image->save( $pathAvatar );
    
    $imageSource = OPanda_Leads::updateLeadField( $leadId, '_image' . $size, $leadId . 'x' . $size . '.jpeg' );
    
    $image->stream();
    
    exit;
}