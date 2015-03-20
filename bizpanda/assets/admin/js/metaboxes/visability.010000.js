(function($){
    
    /**
     * Inits the visability metabox.
     */
    window.metaboxVisability = {
        
        init: function() {

            $("#opanda_lock_delay").change(function(){
                if ( $(this).is(":checked") ) {
                    $("#onp-sl-lock-delay-options").hide().removeClass('hide');
                    $("#onp-sl-lock-delay-options").fadeIn();
                } else {
                    $("#onp-sl-lock-delay-options").hide();
                }
            });

            $("#opanda_relock").change(function(){
                if ( $(this).is(":checked") ) {
                    $("#onp-sl-relock-options").hide().removeClass('hide');
                    $("#onp-sl-relock-options").fadeIn();
                } else {
                    $("#onp-sl-relock-options").hide();
                }
            });    
        }
    };
    
    $(function(){
        window.metaboxVisability.init();
    });
    
})(jQuery);