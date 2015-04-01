( function( $ ){
    
    // CHECKBOX CONTROL CLASS DEFINITION
    // ================================
    
    var CheckboxControl = function (element) {
        var self = this;
        this.$element = $(element);
        
        this.$result = this.$element.find(".factory-result");
        this.$on = this.$element.find(".factory-on");
        this.$off = this.$element.find(".factory-off");
        
        var isTumbler = this.$element.is(".factory-tumbler");
        var hasTumblerHint = this.$element.is(".factory-has-tumbler-hint");
        var tumblerFunction = this.$element.data('tumbler-function');
        
        var tumblerDelay = this.$element.data('tumbler-delay');
        if ( !tumblerDelay ) tumblerDelay = 3000;
        
        this.callByPath = function( functionName, args ) {
            var parts = functionName.split(".");
            var obj = window;
            
            for( var i = 0; i < parts.length; i++ ) {
                obj = obj[parts[i]];
            }
            
            obj.apply( obj, args );
        }
        
        this.$on.click(function(){
            
            self.$off.removeClass('active');
            self.$on.addClass('active');
            
            if ( !isTumbler ) {
                self.$result.attr('checked', 'checked');
                self.$result.trigger('change');
            } else {
                
                setTimeout(function(){
                    self.$on.removeClass('active');
                    self.$off.addClass('active');
                    
                    var $hint = hasTumblerHint ? self.$element.next() : null;
                    
                    if ( tumblerFunction ) {
                        self.callByPath( tumblerFunction, [ self.$element, $hint] );
                    } else {
                        if ( hasTumblerHint ) {
                            self.$element.next().fadeIn(300);
                            setTimeout(function(){ self.$element.next().fadeOut( 500 ); }, tumblerDelay);
                        }  
                    }

                }, 300);
            }

            return false;
        });

        this.$off.click(function(){
            
            self.$on.removeClass('active');
            self.$off.addClass('active');
            
            if ( !isTumbler ) {
                self.$result.removeAttr('checked');
                self.$result.trigger('change');
            } else {
                
                setTimeout(function(){
                    self.$off.removeClass('active');
                    self.$on.addClass('active');
                    
                    var $hint = hasTumblerHint ? self.$element.next() : null;
                       
                    if ( tumblerFunction ) {
                        self.callByPath( tumblerFunction, [ self.$element, $hint] );
                    } else {
                        if ( hasTumblerHint ) {
                            self.$element.next().fadeIn(300);
                            setTimeout(function(){ self.$element.next().fadeOut( 500 ); }, tumblerDelay);
                        }  
                    }
                    
                }, 300);
            }

            return false;
        }); 
    };
    
    // CHECKBOX CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap329_checkboxControl = function (option) {
        
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

    $.fn.factoryBootstrap329_checkboxControl.Constructor = CheckboxControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-329 .factory-checkbox.factory-buttons-way").factoryBootstrap329_checkboxControl();
    });
    
}( jQuery ) );