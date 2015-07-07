if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.statistics ) window.bizpanda.leads = {};

(function($){
    
    window.bizpanda.leads = {
        
        init: function() {
            
            if ( $("#opanda-leads-page").length ) this.list.init();
            if ( $("#opanda-lead-details-page").length ) this.details.init();            
            if ( $("#opanda-export-page").length ) this.export.init();
        },
        
        list: {
            init: function(){}
        },
        
        details: {

            init: function() {
                
                $('.detail').on('click', function(){

                        var _this = $(this).addClass('active'),
                                _ul = _this.find('.click-to-edit'),
                                _first = _ul.find('> li').first(),
                                _last = _ul.find('> li').last();

                        if(!_first.is(':hidden')){
                                _first.hide();
                                _last.show().find('input').first().focus().select();
                        }

                });
            }
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
