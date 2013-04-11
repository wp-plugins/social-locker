<?php

if ( class_exists('ether_builder_widget'))
{
    class ether_socillocker_widget extends ether_row_base_widget
    {
	public function __construct()
	{
            parent::__construct('row-1-socoallocker', 'Social Locker');

            $this->label = 'Dont forget that also you can use the [sociallocker] shortcode
                             inside content of widgets to lock its parts.';
            
            $this->title = 'Social Locker';
            
            $this->core = TRUE;
            $this->cols = '1';
            $this->col_count = 1;
	}

        public function widget($widget)
        {
            $output = '';
            
            if (isset($widget['col-1'])) {
                $output .= '[sociallocker]' . $widget['col-1'] . '[/sociallocker]' ._n;
                $output = do_shortcode($output);
            } else {
                return $output;
            }

            return $output;
        }
        
        public function form($widget)
        {
                $cols = '<div class="builder-widget-row cols-'.$this->cols.'">';
                $options = '<div class="builder-widget-row-options cols-'.$this->cols.'">';

                for ($i = 1; $i <= $this->col_count; $i++)
                {
                        $cols .= '<div class="col builder-widget-column">'.(isset($widget['col-'.$i]) ? $widget['col-'.$i] : '').'</div>';
                        $options .= ' <div class="col builder-widget-column-options"><button name="builder-widget-add" class="button-1 button-1-1 builder-widget-add"><span>'.ether::langr('Add widget').'</span></button></div>';
                }

                $cols .= '</div>';
                $options .= '</div>';

                return $cols.$options;
        }
    }
    
    add_action( 'admin_head', 'sociallocker_ether_widget_icon');
    function sociallocker_ether_widget_icon() 
    {      
        ?>
        <style type="text/css" media="screen">
            .builder-widget-icon-row-1-socoallocker {
                background: url('<?php echo SOCIALLOCKER_PLUGIN_URL . '/assets/admin/img/sociallocker-48px.png' ?>') no-repeat;
            }
        </style>
        <?php
    }
    
    ether_builder::register_widget('ether_socillocker_widget');
}

