<?php
/**
 * Factory Function Library
 * 
 * ToDo: remove this file in future
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Returns a nonce based on a current wordpress blog options.
 * 
 * @since 1.0.0
 * @return string
 */
function factory_300_get_nonce() {
    $values = array('name', 'description', 'admin_email', 'url', 'language', 'version');
    $line = '';
    
    foreach($values as $value) $line .= get_bloginfo($value);
    return md5( $line );
}

/**
 * Prints a nonce based on a current wordpress blog options.
 * 
 * @since 1.0.0
 * @return string
 */
function factory_300_nonce() {
    echo factory_300_get_nonce();
}

// ------------------------------
// String functions
// ------------------------------

/**
 * Checks if $haystack strats with $needle.
 * 
 * @since 1.0.0
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function factory_300_starts_with($haystack, $needle) {
    return !strncmp($haystack, $needle, strlen($needle));
}

/**
 * Checks if $haystack ends with $needle.
 * 
 * @since 1.0.0
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function factory_300_ends_with($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) return true;
    return (substr($haystack, -$length) === $needle);
}

// ------------------------------
// File functions
// ------------------------------

/**
 * Gets data about a given path.
 * 
 * @since 1.0.0
 * @param type $path A path to get data.
 * @return string[]
 */
function factory_300_pathinfo( $path ) {
    $data = pathinfo($path);
    $data['filename'] = factory_300_filename_without_ext($data['basename']);
    return $data;
}

/**
 * Gets a file name without its extention.
 * 
 * @since 1.0.0
 * @param string $filename A file name to process.
 * @return string
 */
function factory_300_filename_without_ext($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}

/**
 * Gets a file extention.
 * 
 * @since 1.0.0
 * @param string $filename A file name to get an extention.
 * @return string
 */
function factory_300_filename_ext($filename){
    $result = explode('.', $filename);
    if ( empty($result) ) return null;
    return $result[count($result)-1];
}

/**
 * Copies a dir recursivly and safetly.
 * 
 * @since 1.0.0
 * @return bool
 */
function factory_300_copy_dir($source, $dest, $permissions = 0755)
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
        factory_300_copy_dir("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}

/**
 * Removes a dir recursivly and safetly.
 * 
 * @since 1.0.0
 * @return bool
 */
function factory_300_remove_dir($directory, $empty = false)
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
					factory_300_remove_dir($path);

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

/**
 * @since 1.0.0
 */
function factory_300_get_days( $startDate, $endDate ) {
    return floor( abs( $endDate - $startDate ) / (60*60*24) ) + 1;
}

/**
 * @since 1.0.0
 */
function factory_300_get_weeks( $startDate, $endDate ) {
    $days = factory_300_get_days( $startDate, $endDate );
    
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

/**
 * @since 1.0.0
 */
function factory_300_get_months( $startDate, $endDate ) {
    return floor( abs( $endDate - $startDate ) / (60*60*24*30) );
}