if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.statistics ) window.bizpanda.leads = {};

(function($){
    
    window.bizpanda.leads = {
        
        init: function() {
            
            if ( $("#opanda-leads-page").length ) this.list.init();
            if ( $("#opanda-export-page").length ) this.export.init();
        },
        
        list: {
            init: function(){}
        },
        
        export: {
            init: function(){
                
                $("#factory-checklist-opanda_lockers-all").change( function(){
                    var $checkboxes = $(".factory-control-lockers input");
                    
                    if ( $(this).is(":checked") ) {
                        $checkboxes.attr('checked', 'checked');
                    } else {
                        $checkboxes.removeAttr('checked', 'checked');
                    }
                });
            }
        }
    };

    $(function(){
        window.bizpanda.leads.init();       
    });
    
})(jQuery)
