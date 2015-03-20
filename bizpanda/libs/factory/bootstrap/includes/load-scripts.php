<?php
/**
 * Этот файл отвечает за конкатенацию скриптов из сабмодулей
 * 
 * Создано для Factory Metaboxes.
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

define('ONP_SCRIPTS_DIR', '..');

if ( isset( $_GET['test']) ) {
    echo 'success';
    exit;
}

/**
 * Фукнция получает контент файла
 * @param type $path - путь к файлу
 * @return string
 */
function get_file($path) {
    if ( function_exists('realpath') ) $path = realpath($path);
    if ( !$path || ! @is_file($path) ) return '';
    return @file_get_contents($path);
}

//Получает имена модулей разделенные запятой
$load = isset( $_GET['load'] ) ? $_GET['load'] : null;
$debug = isset( $_GET['debug'] ) ? $_GET['debug'] : false;

if ( empty($load ) ) exit;

if ( is_array( $load ) )
    $load = implode( '', $load );

$load = preg_replace( '/[^a-z0-9,._-]+/i', '', $load );
$load = explode( ',', $load );

$out = '';
$compress = ( isset($_GET['c']) && $_GET['c'] );
$force_gzip = ( $compress && 'gzip' == $_GET['c'] );
$expires_offset = 31536000; // 1 year
$cache_filename = md5(join(',',$load));

if ( empty($load) ) exit;
 
foreach( $load as $key => $val ) {
    $load[$key] = ONP_SCRIPTS_DIR . "/assets/js/$val.js";
}

//Собираем в строку
foreach( $load as $handle ) {	
    if ( file_exists( $handle ) ) $out .= get_file($handle) . "\n";
}

//Говорим, что этот javascript файл 
header('Content-Type: application/x-javascript; charset=UTF-8');
header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
header("Cache-Control: public, max-age=$expires_offset");

if ( $compress && ! ini_get('zlib.output_compression') && 'ob_gzhandler' != ini_get('output_handler') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) {
    header('Vary: Accept-Encoding'); // Handle proxies
    if ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') && function_exists('gzdeflate') && ! $force_gzip ) {
        header('Content-Encoding: deflate');
        $out = gzdeflate( $out, 3 );
    } elseif ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && function_exists('gzencode') ) {
        header('Content-Encoding: gzip');
        $out = gzencode( $out, 3 );
    }
}

echo $out;
exit;