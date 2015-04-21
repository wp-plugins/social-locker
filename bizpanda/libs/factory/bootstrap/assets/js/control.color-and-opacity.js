( function( $, undef ){
    $(function(){            
              
      $(function(){
          
        $(".factory-bootstrap-329 .factory-color-and-opacity").each(function(){
            var $colorOpacityControl = $(this);
            
            var $colorControl = $colorOpacityControl.find('.factory-color');
            var $integerControl = $colorOpacityControl.find('.factory-integer'); 


            // apply to change opacity of the preview
            $integerControl.change(function(){
                var opacityValue = $integerControl.find(".factory-result").val() / 100;   
                
                
                $colorControl.find('.factory-background').css('opacity', opacityValue );


            });
            
            $integerControl.change();
            
            $integerControl.on("click.color.factory", function(e){
                e.stopPropagation();
            });  
        });
      });
     
    });    
}( jQuery ) );