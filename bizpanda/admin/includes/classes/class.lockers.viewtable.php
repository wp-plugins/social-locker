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
        
        if ( !BizPanda::isSinglePlugin() ) {
            $this->columns->add('type', __('Type', 'bizpanda'));
        }
        
        $this->columns->add('shortcode', __('Shortcode', 'bizpanda'));
        $this->columns->add('theme', __('Theme', 'bizpanda'));
        $this->columns->add('bulk', __('Bulk Lock', 'bizpanda'));
        $this->columns->add('visibility', __('Visibility Conditions', 'bizpanda'));
        
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
     * Column 'Type'
     */
    public function columnType( $post, $isFullMode ) {
        $item = get_post_meta($post->ID, 'opanda_item', true);
        echo $item;
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
            <?php opanda_print_bulk_locking_state( $post->ID ); ?>
        </div>
        <?php
    }
    
    /**
     * Column 'Theme'
     */
    public function columnTheme( $post, $isFullMode ) {
        
        $theme = get_post_meta($post->ID, 'opanda_style', true);
        echo $theme;
        
        
    }
    
     /**
     * Column 'Visibility Conditions'
     */
    public function columnVisibility( $post, $isFullMode ) {
        
        $mode = get_post_meta($post->ID, 'opanda_visibility_mode', true);
        if ( empty( $mode) ) $mode = 'simple';
        
        ?>
        <div class='onp-sl-inner-wrap'>
            <?php if ( $mode === 'simple' ) { ?>
                <?php opanda_print_simple_visibility_options( $post->ID ); ?>
            <?php } else { ?>
                <?php opanda_print_visibility_conditions( $post->ID ); ?>
            <?php } ?>
        </div>
        <?php
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
 