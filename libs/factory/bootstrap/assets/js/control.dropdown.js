( function( $ ){
    
    // DROPDOWN CONTROL CLASS DEFINITION
    // ================================
    
    var DropdownControl = function (element) {
        var self = this;
        
        this.$element = $(element);
        this.$result = this.$element.find(".factory-result");
        this.$hints = this.$element.find(".factory-hints");        
        this.$buttons = this.$element.find(".btn");    

        this.$buttons.click(function(){
            var value = $(this).data('value');
   
            self.$buttons.removeClass('active');
            $(this).addClass('active');
            
            self.$hints.find(".factory-hint").hide();
            self.$hints.find(".factory-hint-" + value).fadeIn();
            
            self.$result.val(value);
            self.$result.trigger('change');
            return false;
        });
    };
    
    // DROPDOWN CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap322_dropdownControl = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.dropdown.control');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.dropdown.control');
                if (!data) $this.data('factory.dropdown.control', (data = new DropdownControl(this)));
            });
        }
    };

    $.fn.factoryBootstrap322_dropdownControl.Constructor = DropdownControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-322 .factory-dropdown.factory-buttons-way").factoryBootstrap322_dropdownControl();
    });
    
}( jQuery ) );