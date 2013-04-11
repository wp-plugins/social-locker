(function($){
    
    if ( window.fy_hide_notice ) return;
    
    $(function(){
        $(".onp-notice-close").click(function(){
            var id = $(this).parents(".onp-notice").attr('id');
            fy_hide_notice(id, false);
            return false;
        });
    });
    
    $(function(){
        $(".onp-alert-close").click(function(){
            var id = $(this).parents(".onp-alert").attr('id');
            fy_hide_notice(id, false);
            return false;
        });
    });
    
    window.fy_hide_notice = function( id, never ) {
        var item = $("#" + id).fadeOut(300, function(){
            item.remove();
            $.ajax({
                url: ajaxurl,
                type: "post",
                data: {
                    id: id,
                    action: "fy_hide_notice",
                    never: never ? true : false
                }
            });
        });
    }
})(jQuery);