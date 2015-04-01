<?php

function onp_updates_324_set_site_transient( $transient, $value, $expiration = 0, $actions = false ) {
	global $_wp_using_ext_object_cache;

        if ( $actions ) {
            $value = apply_filters( 'pre_set_site_transient_' . $transient, $value );
        }
        
	if ( $_wp_using_ext_object_cache ) {
		$result = wp_cache_set( $transient, $value, 'site-transient', $expiration );
	} else {
		$transient_timeout = '_site_transient_timeout_' . $transient;
		$transient = '_site_transient_' . $transient;

		if ( false === get_site_option($transient) ) {
			if ( $expiration )
				add_site_option( $transient_timeout, time() + $expiration );
			$result = add_site_option( $transient, $value );
		} else {
			if ( $expiration )update_site_option( $transient_timeout, time() + $expiration );
                        delete_site_option($transient);
			$result = update_site_option( $transient, $value );
		}
	}
	if ( $result && $actions ) {
		do_action( 'set_site_transient_' . $transient );
		do_action( 'setted_site_transient', $transient );
	}
	return $result;
}