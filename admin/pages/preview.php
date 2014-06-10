<?php
add_action("wp_ajax_onp_sl_preview", 'onp_lock_preview');
function onp_lock_preview() {
?>
<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8" />
         <style>
             body {
                 padding: 0px;
                 margin: 0px;
                 font: normal normal 400 14px/170% Arial;
                 color: #333333;
                 text-align: justify;
             }
             * {
                 padding: 0px;
                 margin: 0px;
             }
             #wrap {
                 padding: 20px;
                 overflow: hidden;
             }
             p {
                 margin: 0px;
             }
             p + p {
                 margin-top: 8px;
             }
             .content-to-lock a {
                 color: #3185AB;
             }
             .content-to-lock {
                 text-shadow: 1px 1px 1px #fff;
                 padding: 20px 40px;
             }
         </style>
         <script type="text/javascript" src="<?php echo get_site_url() ?>/wp-includes/js/jquery/jquery.js"></script>
         <script type="text/javascript" src="<?php echo get_site_url() ?>/wp-includes/js/jquery/ui/jquery.ui.core.min.js"></script>     
         <script type="text/javascript" src="<?php echo get_site_url() ?>/wp-includes/js/jquery/ui/jquery.ui.effect.min.js"></script>
         <script type="text/javascript" src="<?php echo get_site_url() ?>/wp-includes/js/jquery/ui/jquery.ui.effect-highlight.min.js"></script>
         
         <script type="text/javascript" src="<?php echo ONP_SL_PLUGIN_URL ?>/assets/admin/js/json2.js"></script>    
         
         <?php ?>
         <script type="text/javascript" src="<?php echo ONP_SL_PLUGIN_URL ?>/assets/js/jquery.op.sociallocker.030503.min.js"></script>  
         <link rel="stylesheet" type="text/css" href="<?php echo ONP_SL_PLUGIN_URL ?>/assets/css/jquery.op.sociallocker.030503.min.css">  
         <?php 
 ?>
         
         <?php do_action('onp_sl_preview_print_scripts', !empty( $_GET ) ? $_GET : null); ?>  

         <script>
             (function($){  
                var callback = '<?php echo ( isset( $_POST['callback'] ) ? $_POST['callback'] : '' ) ?>';
                
                window.setOptions = function( options ) {
   
                    $(".sociallocker-next").remove();
                    var $clone = $(".content-to-lock");
                    $("#wrap").html("");                                     
                    
                    options.demo = true;
                    $clone.appendTo("#wrap");
                    $clone.sociallocker(options);
                    window.alertFrameSize();
                };
                
                window.alertFrameSize = function() {
                    if ( !parent || !callback ) return;
                    var height = jQuery("#wrap").height(); height += 50;
                    if (parent[callback]) parent[callback]( height );
                };
                
                window.dencodeOptions  = function( options ) {
                    for( var optionName in options ) {
                        if ( typeof options[optionName] === 'object' ) {
                            options[optionName] = dencodeOptions( options[optionName] );
                        } else {
                            if ( options[optionName] ) {
                                options[optionName] = decodeURI( options[optionName] );
                            }
                        }
                    }
                    return options;
                };

                window.defaultOptions = {
                    demo: true,
                    text: {},
                    buttons: {},
                    effects: {},
                    locker: {},
                    facebook: {
                        like: {},
                        share: {}
                    },
                    twitter: {
                        tweet: {},
                        follow: {}
                    },
                    google: {
                        plus: {},
                        share: {}
                    },
                    linkedin: {
                        share: {}
                    },
                    vk: {   
                        like: {},                
                        subscribe: {}               
                    },
                    ok: {
                        class: {}
                    },
                    events: {
                       ready: function() { alertFrameSize(); },             
                       unlock: function() { alertFrameSize(); },
                       unlockByTimer: function() { alertFrameSize(); },
                       unlockByClose: function() { alertFrameSize(); }            
                    }        
                };      

                $(function(){
                   setTimeout(function(){ alertFrameSize(true); }, 2000);   
                });

                var postOptions = dencodeOptions( JSON.parse('<?php echo $_POST['options'] ?>') );
                var options = $.extend(window.defaultOptions, postOptions);
                
                $(function(){
                    $(".content-to-lock").sociallocker(options);            
                });
                
                jQuery(document).click(function(){
                    if( parent && window.removeProfilerSelector ) window.removeProfilerSelector();
                });
             })(jQuery);
         </script>
    </head>
    <body>
        <div id="wrap">
            <div class="content-to-lock">
                <p>
                    Nulla mi odio, posuere <a href="#">commodo elementum varius</a>, sodales in justo. 
                    Nunc convallis rhoncus odio, in cursus massa eleifend sit amet. 
                    Morbi sed erat tortor. Maecenas turpis neque, sollicitudin eget auctor in, 
                    porta non justo.
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </body>
</html>

<?php
exit;
}
