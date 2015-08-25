(function($){
    
    /**
     * Visability Options.
     */
    window.visibilityOptions = {
        
        init: function() {

            this.initSwitcher();
            this.initSimpleOptions();
            this.initAdvancedOptions();
        },
        
        initSwitcher: function() {
            var $buttons = $(".bp-options-switcher .btn");
            
            var selectOptions = function( value ) {
                if ( !value ) value = $("#opanda_visibility_mode").val();
                
                $buttons.removeClass('active');   

                if ( 'simple' === value ) {
                    $(".bp-options-switcher .btn-btn-simple").addClass('active');
                    $("#bp-advanced-visibility-options").hide();
                    $("#bp-simple-visibility-options").fadeIn(300);
                } else {
                    $(".bp-options-switcher .btn-btn-advanced").addClass('active');
                    $("#bp-simple-visibility-options").hide();
                    $("#bp-advanced-visibility-options").fadeIn(300);
                }
                
                $("#opanda_visibility_mode").val(value);
            };
            
            $buttons = $(".bp-options-switcher .btn").click(function(){
                var value = $(this).data('value');
                selectOptions(value);
                return false;
            });
            
            selectOptions();
        },
        
        initSimpleOptions: function() {
            var self = this;
            
            $("#opanda_relock").change(function(){
                if ( $(this).is(":checked") ) {
                    $("#onp-sl-relock-options").hide().removeClass('hide');
                    $("#onp-sl-relock-options").fadeIn();
                } else {
                    $("#onp-sl-relock-options").hide();
                }
            });
        },
        
        initAdvancedOptions: function() {
            var self = this;
            
            var $btnShow = $("#bp-show-more-visability-options");
            var $btnSave = $("#bp-advanced-visability-options .bp-save");
            
            var $modal = $("#bp-advanced-visability-options");
            var $hidden = $("#opanda_visibility_filters");
            
            var $editor = $("#bp-advanced-visability-options");
            
            // creating an editor
            
            $editor.bpConditionEditor({
                filters: $.parseJSON( $hidden.val() )
            });  
            
            // shows an editor on clicking the button Show
            
            $btnShow.click(function(){
                $modal.factoryBootstrap329_modal("show");
            });  

            // saves conditions on clicking the button Save
            
            $btnSave.click(function(){
                
                var data = $editor.bpConditionEditor("getData");
                console.log(data);
                
                var json = JSON.stringify(data);
                $hidden.val(json);
                
                $modal.factoryBootstrap329_modal("hide");
            });
        }
    };

    $(function(){
        window.visibilityOptions.init();
    });
    
})(jQuery);