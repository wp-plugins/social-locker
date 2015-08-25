<?php

class OPanda_ItemsViewTable extends FactoryViewtables320_Viewtable
{
    public function configure()
    {
        /**
         * Columns
         */
        
        $this->columns->clear();

        $this->columns->add('stats', __('<span title="Unlocks / Impressions / Conversion">U / I / %', 'bizpanda'));
        $this->columns->add('title', __('Locker Title', 'bizpanda'));

        $this->columns->add('shortcode', __('Shortcode', 'bizpanda')); 
        $this->columns->add('bulk', __('Bulk Lock', 'bizpanda'));   
        $this->columns->add('visibility', __('Visibility', 'bizpanda'));
        $this->columns->add('created', __('Created', 'bizpanda'));
        
        /**
         * Scripts & styles
         */
                
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/item-view.010000.js');       
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets//admin/css/item-view.010000.css');
    }
    
    /**
     * Column 'Title'
     */
    public function columnTitle( $post, $isFullMode ) {
        if ($isFullMode ) {
            
           $url = get_post_meta($post->ID, 'opanda_theme', true);
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
        
        $isSystem = get_post_meta( $post->ID, 'opanda_is_system', true);
        $itemTypeName = get_post_meta( $post->ID, 'opanda_item', true);
        
        $item = OPanda_Items::getItem( $itemTypeName );
        $shortcodeName = $item['shortcode'];
        
        $shortcode = '[' . $shortcodeName . '] [/' . $shortcodeName . ']';
        if (!$isSystem) $shortcode = '[' . $shortcodeName . ' id="' . $post->ID . '"] [/' . $shortcodeName . ']';

        ?>
        <input class="shortcode" type="text" value='<?php echo $shortcode ?>' />
        <?php 

        
    }
    
    /**
     * Column 'Shortcode'
     */ 
    public function columnBulk( $post, $isFullMode ) {
        ?>
        <div class='onp-sl-inner-wrap'>
            <?php echo opanda_print_bulk_locking_state( $post->ID ); ?>
        </div>
        <?php
    }
    
    /**
     * Column 'Theme'
     */
    public function columnVisibility( $post, $isFullMode ) {
        
        $theme = get_post_meta($post->ID, 'opanda_style', true);
        echo $theme;
        
        
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
    
     /**
     * Column 'Created'
     */
    public function columnStats( $post ) {
        global $optinpanda;
        
        $imperessions = intval( get_post_meta($post->ID, 'opanda_imperessions', true) );
        $conversion = '0';
        
        $unlocks = intval( get_post_meta($post->ID, 'opanda_unlocks', true) );
        
        if ( !empty( $imperessions )) {
            $conversion = round( $unlocks / $imperessions * 100, 2 );
        } elseif ( !empty( $unlocks ) ) {
            $conversion = 100;
        }
        
        $strong = ( $unlocks > 0 );        
        $url = opanda_get_admin_url('stats', array('opanda_id' => $post->ID));
                
        if ( $strong ) {
            echo '<a href="' . $url . '"><strong>' . $unlocks . '</strong> / ' . $imperessions . ' / ' . sprintf( '%.02f', $conversion ) . '%' . '</a>';
        } else {
            echo '<a href="' . $url . '" class="opanda-empty">' . $unlocks . ' / ' . $imperessions . ' / ' . sprintf( '%.02f', $conversion ) . '%' . '</a>';
        }
        ?>
            
        <?php
    }
}
 