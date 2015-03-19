(function($){
    
    $(function(){

        $chkDynamiTheme = $("#sociallocker_dynamic_theme").change(function(){
            var isYes = $chkDynamiTheme.is(":checked");
            
            if ( isYes ) {
                $("#onp-dynamic-theme-options").fadeIn();
            } else {
                $("#onp-dynamic-theme-options").hide();  
            }
        });
        
        $chkDynamiTheme.change();
        
    });
    
})(jQuery)