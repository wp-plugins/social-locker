<?php

class SocialLockerViewTable extends FactoryViewtables320_Viewtable
{
    public function configure()
    {
        /**
         * Columns
         */
        
        $this->columns->clear();
        $this->columns->add('title', 'Title');
        $this->columns->add('shortcode', 'Shortcode'); 
        $this->columns->add('bulk', 'Bulk Locking');   
        $this->columns->add('theme', 'Theme');
        $this->columns->add('created', 'Created');
        
        /**
         * Scripts & styles
         */
                
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/sociallocker.viewtable.030000.js');       
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets//admin/css/sociallocker.viewtable.030000.css');
    }
    
    /**
     * Column 'Title'
     */
    public function columnTitle( $post, $isFullMode ) {
        if ($isFullMode ) {
            
           $url = get_post_meta($post->ID, 'sociallocker_theme', true);
           if ( empty($url) ) $url = '<i>[current page]</i>';
           
           echo '<p>' . $post->post_title . '</p>';
           echo '<p>' . $url . '</p>';
        } else {
            echo $post->post_title;
        }
    }
    
    /**
     * Column 'Shortcode'
     */ 
    public function columnShortcode( $post, $isFullMode ) {
        
        $isSystem = get_post_meta( $post->ID, 'sociallocker_is_system', true);
        
        if ($isSystem) {
            
            ?>
            <input class="shortcode" type="text" value='[sociallocker] [/sociallocker]' />
            <?php 
            
        } else {
            
            ?>
            <input class="shortcode" type="text" value='[sociallocker id="<?php echo $post->ID ?>"] [/sociallocker]' />
            <?php  
            
        }
    }
    
    /**
     * Column 'Shortcode'
     */ 
    public function columnBulk( $post, $isFullMode ) {
        ?>
        <div class='onp-sl-inner-wrap'>
            <?php echo onp_sl_print_bulk_locking_state( $post->ID ); ?>
        </div>
        <?php
    }
    
    /**
     * Column 'Theme'
     */
    public function columnTheme( $post, $isFullMode ) {
        
        $theme = get_post_meta($post->ID, 'sociallocker_style', true);
        
        switch($theme) {
            
            case null || '':
                echo '(default)';
                break;
            case 'ui-social-locker-secrets':
                echo 'Secrets';
                break;
            case 'ui-social-locker-dandyish':
                echo 'Dandish';
                break;             
            default:
                echo $theme;
                break;
        }
    }
    
     /**
     * Column 'Created'
     */
    public function columnCreated( $post, $isFullMode ) {
        
        $t_time = get_the_time( 'Y/m/d g:i:s A' );
        $m_time = $post->post_date;
        $time = get_post_time( 'G', true, $post );

        $time_diff = time() - $time;

        if ( $time_diff > 0 && $time_diff < 24*60*60 )
            $h_time = sprintf( '%s ago', human_time_diff( $time ) );
        else
            $h_time = mysql2date( 'Y/m/d', $m_time );
        
        echo '<abbr title="' . esc_attr( $t_time ) . '">' . $h_time . '</abbr><br />';
    }
}