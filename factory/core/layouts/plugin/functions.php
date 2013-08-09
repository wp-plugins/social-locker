<?php
/**
 * A number of global functions with the prefix 'factory_fr107_', 
 * that are used to manage Factory instances.
 */

/**
 * A Factory gateway to create an instance of the plugin. 
 * It should be invoked first in plugin file.
 */
function factory_fr107_create_plugin( $pluginPath, $data ) {
    $plugin = new FactoryFR107Plugin($pluginPath, $data );
    return $plugin;
}

/**
 * Returns nonce based on a current wordpress blog options.
 */
function factory_fr107_get_nonce() {
    $values = array('name', 'description', 'admin_email', 'url', 'language', 'version');
    $line = '';
    
    foreach($values as $value) $line .= get_bloginfo($value);
    return md5( $line );
}

/**
 * Prints nonce based on a current wordpress blog options.
 */
function factory_fr107_nonce() {
    echo factory_fr107_get_nonce();
}

// ------------------------------
// Json functions
// ------------------------------

function factory_fr107_json_error($error) {
    echo json_encode(array('error' => $error));
    exit;
}

function factory_fr107_json_success( $data = array() ) {
    $data['error'] = false;
    echo json_encode( $data );
    exit;
}

// ------------------------------
// String functions
// ------------------------------

function factory_fr107_starts_with($haystack, $needle) {
    return !strncmp($haystack, $needle, strlen($needle));
}

function factory_fr107_ends_with($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) return true;
    return (substr($haystack, -$length) === $needle);
}

// ------------------------------
// File functions
// ------------------------------

function factory_fr107_pathinfo( $path ) {
    $data = pathinfo($path);
    $data['filename'] = factory_fr107_filename_without_ext($data['basename']);
    return $data;
}

function factory_fr107_filename_without_ext($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}

function factory_fr107_filename_ext($filename){
    $result = explode('.', $filename);
    if ( empty($result) ) return null;
    return $result[count($result)-1];
}

function factory_fr107_copy_dir($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        factory_fr107_copy_dir("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}

function factory_fr107_remove_dir($directory, $empty = false)
{
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... if the path is not readable
	}elseif(!is_readable($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... else if the path is readable
	}else{

		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while (FALSE !== ($item = readdir($handle)))
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if($item != '.' && $item != '..')
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;

				// if the new path is a directory
				if(is_dir($path)) 
				{
					// we call this function with the new path
					factory_fr107_remove_dir($path);

				// if the new path is a file
				}else{
					// we remove the file
					unlink($path);
				}
			}
		}
		// close the directory
		closedir($handle);

		// if the option to empty is not set to true
		if($empty == FALSE)
		{
			// try to delete the now empty directory
			if(!rmdir($directory))
			{
				// return false if not possible
				return FALSE;
			}
		}
		// return success
		return TRUE;
	}
}

// ------------------------------
// Date functions
// ------------------------------

function factory_fr107_get_days( $startDate, $endDate ) {
    return floor( abs( $endDate - $startDate ) / (60*60*24) ) + 1;
}

function factory_fr107_get_weeks( $startDate, $endDate ) {
    $days = factory_fr107_get_days( $startDate, $endDate );
    
    $startDay = date( "w", $startDate );
    $endDay = date( "w", $endDate );
    
    if ( $days < 7 ) {
        if ( $endDay < $startDay ) {
            return 2;
        } else {
            return 1;
        }
    } else {
        return floor( $days / 7 ); 
    }
}

function factory_fr107_get_months( $startDate, $endDate ) {
    return floor( abs( $endDate - $startDate ) / (60*60*24*30) );
}

// ------------------------------
// Page functions
// ------------------------------

function factory_fr107_get_ajax_action_url( $plugin, $page, $action, $args = array() ) {
    
    $args = array_merge( $args, array(
        'fy_page'      => $page,
        'fy_action'    => $action,  
        'fy_plugin'    => $plugin,
        'fy_ajax'      => true
    ));
    
    return '?' . http_build_query( $args );
}

function factory_fr107_ajax_action_url( $plugin, $page, $action, $args = array() ) {
    echo factory_fr107_get_ajax_action_url( $plugin, $page, $action, $args );
}

function factory_fr107_get_action_url( $plugin, $page, $action, $args = array() ) {
    
    $args = array_merge( $args, array(
        'fy_page'      => $page,
        'fy_action'    => $action,  
        'fy_plugin'    => $plugin,
    ));
    
    return '?' . http_build_query( $args );
}

function factory_fr107_action_url( $plugin, $page, $action, $args = array() ) {
    echo factory_fr107_get_action_url( $plugin, $page, $action, $args );
}