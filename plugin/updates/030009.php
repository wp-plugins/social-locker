<?php

/**
 * Changes names of options because since the 3th version we introduced a new agreement of names.
 * 
 * @since 3.0.0
 */
class SocialLockerUpdate030009 extends Factory325_Update {

    public function install() {
        global $wpdb;
        $args = array(
            'posts_per_page'   => -1,           
            'orderby'          => 'post_date',            
            'post_type'        => 'social-locker',          
            'post_status'      => 'publish'           
        );       
    }
}