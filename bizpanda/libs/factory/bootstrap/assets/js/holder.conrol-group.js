/**
*jQuery Control group holder
*/
(function( $ ) {
    $(document).ready(function(){
        $('.factory-control-group-nav-label').on('click', function(){ 
           var parent = $(this).parents('.factory-control-group');
        
           if( !$(this).hasClass('active') ) {
               $('.factory-control-group-nav-label', parent).add('.factory-control-group-item', parent).removeClass('current');
               $('.factory-control-is-active', parent).val(0);
               
               $(this).add($("." + $(this).data('control-id'), parent)).addClass('current');
               parent.children('input[type="hidden"]').val($(this).data('control-name')).trigger('change');               
               $('.factory-control-is-active', $("." + $(this).data('control-id'), parent)).val(1);               
           } 
           return false;
        });
    });    
}(jQuery));