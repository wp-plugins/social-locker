/*!
 * Factory Notices
 *
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-notices 
 * @since 1.0.0
 */

(function($){
    
    if ( window.factory_notices_323_hide_notice ) return;
    
    $(function(){
        
        // notices
        
        $(".factory-notice .factory-close").click(function(){
            var id = $(this).parents(".factory-notice").attr('id');
            factory_notices_323_hide_notice(id, false);
            return false;
        });
        
        // popups
        
        var $popups = $(".factory-popup").appendTo( $('body') );
        var oneShown = false;
        $popups.each(function(){
            var $popup = $(this);
                    
            // shows only one popup every time
            if ( oneShown ) {
                $(this).remove();
                return true;
            }
            
            oneShown = true;
            
            var width = $(this).innerWidth();
            var height = $(this).innerHeight();
            
            $(this).css({
                'marginTop': -parseInt( ( height / 2 ) ) + 'px',
                'marginLeft': -parseInt( ( width / 2 ) ) + 'px',
                'visibility': 'visible'
            });
            
            var $overlay = $("<div class='factory-popup-overlay'></div>").appendTo( $('body') );
            
            $popup.find('.factory-corner-close').click(function(){
                $overlay.fadeOut(300);
                $popup.fadeOut(300);
            });   
            
            $popup.find('.factory-close').click(function(){
                var href = $(this).attr('href');
                var closeType = $(this).data('close');
                
                if ( 'quick-hide' === closeType ) {
                    $overlay.hide();
                    $popup.fadeOut(300);
                } else {
                    var id = $(this).parents(".factory-notice-item").attr('id');
                    factory_notices_323_hide_notice(id, false);
                }

                return ( '#' === href ) ? false: true;
            });
        });
    });
    
    window.factory_notices_323_hide_notice = function( id, never ) {
        var item = $("#" + id).fadeOut(300, function(){
            item.remove();
            $.ajax({
                url: ajaxurl,
                type: "post",
                data: {
                    id: id,
                    action: "factory_notices_323_hide",
                    never: never ? true : false
                }
            });
        });
    }
})(jQuery);