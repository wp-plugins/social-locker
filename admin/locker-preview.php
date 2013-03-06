<?php
    function printJsVars( $vars ) {
        
        foreach($vars as $var) {
            
            $indep = substr($var, 0, 1) == '_';
            if ($indep) {
                $var = substr($var, 1, strlen($var));
            }           
             
            $postName = str_replace('.', '_', $var);
            $value = isset( $_POST[$postName] ) ? $_POST[$postName] : null;
            
            if ( $value === '' || $value === null ) continue;

            $strValue = $value;
            if ($value === 'false') $strValue = false;
            if ($value === 'true') $strValue = true;
            if (preg_match('/^\d+$/', $value)) $strValue = intval($value);
            
            if ($indep) {
                echo 'var ' . $var . ' = ' . jsValue( $strValue ) . ';';       
            } else {
                echo 'options.' . $var . ' = ' . jsValue( $strValue ) . ';';
            }
        }
    }
    
    function jsValue( $value ) {
        
        $type = gettype($value);
        
        switch ($type) {
            case 'boolean':
                return ($value ? 'true' : 'false');
                break;
            case 'integer':
                return $value;
                break;
            default:
                return '"' . $value . '"';
                break;
        }
    }
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
             #wrap > .ui-locker-facebook {
                 margin: 0px !important;
             }
             #content-to-lock a {
                 color: #3185AB;
             }
             #content-to-lock {
                 text-shadow: 1px 1px 1px #fff;
                 padding: 20px;
             }
         </style>
                  
         <script type="text/javascript" src="./../../../../wp-includes/js/jquery/jquery.js"></script>
         <script type="text/javascript" src="./../../../../wp-includes/js/jquery/ui/jquery.ui.core.min.js"></script>     
         <script type="text/javascript" src="./../../../../wp-includes/js/jquery/ui/jquery.ui.effect.min.js"></script>
         <script type="text/javascript" src="./../../../../wp-includes/js/jquery/ui/jquery.ui.effect-highlight.min.js"></script>
         <script type="text/javascript" src="./../assets/js/jquery.op.sociallocker.min.js"></script>
         <link rel="stylesheet" type="text/css" href="./../assets/css/jquery.op.sociallocker.css">  
         
         <script>
             function alertSize() {
                 var height = jQuery("#wrap").height();
                 height += 50;
                 if (parent && parent.updateFrameSize) parent.updateFrameSize(height);
             }
             
             var options = {
                 text: {},
                 buttons: {},
                 effects: {},
                 locker: {},
                 facebook: {},
                 twitter: {},
                 google: {},
                 events: {
                    ready: function() { alertSize(); },             
                    unlock: function() { alertSize(); },
                    unlockByTimer: function() { alertSize(); },
                    unlockByClose: function() { alertSize(); }            
                 }        
             };      
             
             jQuery(function(){
                setTimeout(function(){
                    alertSize(true);
                }, 2000);   
             })

             <?php
                printJsVars(array(
                    
                    'text.header',
                    'text.message',
                    
                    'buttons.order',
                    
                    'style',
                    'effects.highlight',

                    'locker.timer',
                    'locker.close',
                    'locker.mobile',
                    
                    'facebook.url',
                    'facebook.appid',
                    'facebook.lang',
                    
                    'twitter.url',
                    'twitter.lang',
                    'twitter.content', 
                    
                    'google.url',
                    'google.lang'
                ));
            ?>
            
            options.text.header = options.text.header ? unescape( options.text.header ) : null;
            options.text.message = options.text.message ? unescape( options.text.message ) : null;
            
            if (!options.text.header && options.text.message) {
                options.text = options.text.message;
            }
            
            if (options.buttons.order) {
                options.buttons.order = options.buttons.order.split(',');
            }           

            options.demo = true;
               
            jQuery(function(){
                jQuery("#content-to-lock").socialLock(options);            
            })
         </script>
    </head>
    <body>
        <div id="wrap">
        <div id="content-to-lock">
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