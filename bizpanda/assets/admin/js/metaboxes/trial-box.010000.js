(function($){
    
    /**
     * Inits the trial box.
     */
    window.metaboxTrialBox = {
        
        /**
         * Inits the bulk locking metabox.
         */
        init: function() {

            $("#OPanda_MoreFeaturesMetaBox ul span").qtip({
                content: {
                    text: function() {
			var className = $(this).data('target');
                        return $("#OPanda_MoreFeaturesMetaBox ." + className).clone();
                    }
                },
                position: {
                    my: 'center right',
                    at: 'center left',
                    adjust: {
                            x: -45
                    }
                },
                style: {
                    classes: 'qtip-bootstrap advanced-function-demo'
                }
            });
        }
    };
    
    $(function(){
        window.metaboxTrialBox.init();
    });
    
})(jQuery);