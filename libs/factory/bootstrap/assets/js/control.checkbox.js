( function( $ ){
    
    // CHECKBOX CONTROL CLASS DEFINITION
    // ================================
    
    var CheckboxControl = function (element) {
        var self = this;
        this.$element = $(element);
        
        this.$result = this.$element.find(".factory-result");
        this.$on = this.$element.find(".factory-on");
        this.$off = this.$element.find(".factory-off");
        
        this.$on.click(function(){
            self.$off.removeClass('active');
            self.$on.addClass('active');
            self.$result.attr('checked', 'checked');
            self.$result.trigger('change');
            return false;
        });

        this.$off.click(function(){
            self.$on.removeClass('active');
            self.$off.addClass('active');
            self.$result.removeAttr('checked');
            self.$result.trigger('change');
            return false;
        }); 
    };
    
    // CHECKBOX CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap325_checkboxControl = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.checkbox.control');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.checkbox.control');
                if (!data) $this.data('factory.checkbox.control', (data = new CheckboxControl(this)));
            });
        }
    };

    $.fn.factoryBootstrap325_checkboxControl.Constructor = CheckboxControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-325 .factory-checkbox.factory-buttons-way").factoryBootstrap325_checkboxControl();
    });
    
}( jQuery ) );