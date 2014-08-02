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
    if ( window.factory_notices_321_hide_notice ) return;
    
    $(function(){
        $(".factory-close").click(function(){
            var id = $(this).parents(".factory-notice").attr('id');
            factory_notices_321_hide_notice(id, false);
            return false;
        });
    });
    
    window.factory_notices_321_hide_notice = function( id, never ) {
        var item = $("#" + id).fadeOut(300, function(){
            item.remove();
            $.ajax({
                url: ajaxurl,
                type: "post",
                data: {
                    id: id,
                    action: "factory_notices_321_hide",
                    never: never ? true : false
                }
            });
        });
    }
})(jQuery);