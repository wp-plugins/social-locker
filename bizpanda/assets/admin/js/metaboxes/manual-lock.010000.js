(function($){
    
    /**
     * Inits the metabox Manual Locking
     */
    window.metaboxManualLock = {
        
        /**
         * Inits the bulk locking metabox.
         */
        init: function() {
            
            $(".onp-sl-shortcode").click(function(){
                $(this).select();
            });
        },
    };
    
    $(function(){
        window.metaboxManualLock.init();
    });
    
})(jQuery);