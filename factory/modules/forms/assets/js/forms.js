var factoryForms = {};

(function($){
    
    factoryForms = {
        
        collapsedGroups: function( $target ) {
            if ( !$target ) $target = $("body");
            
            $target.find(".fy-collapsed-show").click(function(){
                $( $(this).attr('href') ).fadeIn();
                $(this).hide();
                return false;
            });
            
            $target.find(".fy-collapsed-hide").click(function(){
                var content = $( $(this).attr('href') );
                content.fadeOut(300, function(){
                    content.prev().show();
                });
                return false;
            }); 
        }
    }
    
    $(function(){
        
        factoryForms.collapsedGroups();
    });
    
})(jQuery)

