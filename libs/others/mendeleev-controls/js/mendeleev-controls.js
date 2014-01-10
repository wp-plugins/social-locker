(function($){
    
    /**
     * Mendeleev Checkbox
     */
    
    $.fn.mendeleev300_checkbox = function( param1, param2 ){
        this.each(function(){
            var $element = $(this);
            $element.hide();
            
            $element.change(function( e, internal ){
                if ( internal ) return;
                
                if ( $element.is(":checked") ) {
                    $off.removeClass('active');
                    $on.addClass('active');
                } else {
                    $on.removeClass('active');
                    $off.addClass('active');
                }
            });
            
            // creating markup
            
            var $wrap = $('<div class="btn-group mendeleev-checkbox" data-toggle="buttons-radio">');
            var $on = $('<button type="button" class="btn btn-default btn-sm true" data-value="true">On</button>')
            var $off = $('<button type="button" class="btn btn-default btn-sm false" data-value="false">Off</button>');
            
            $wrap.append($on);
            $wrap.append($off);
            
            $element.after($wrap);
            
            // selecting initial value
            
            if ( $element.is(":checked") ) {
                $on.addClass('active');
            } else {
                $off.addClass('active');
            }
            
            // processing click on the buttons
            
            $on.click(function(){
                $off.removeClass('active');
                $on.addClass('active');
                $element.attr('checked', 'checked');
                $element.trigger('change', [true]);
                return false;
            });
            
            $off.click(function(){
                $on.removeClass('active');
                $off.addClass('active');
                $element.removeAttr('checked', 'checked');
                $element.trigger('change', [true]);
                return false;
            }); 
        });
    }
    
    $(function(){
        $(".mendeleev-300 input[type=checkbox]").mendeleev300_checkbox(); 
    });
    
})(jQuery);