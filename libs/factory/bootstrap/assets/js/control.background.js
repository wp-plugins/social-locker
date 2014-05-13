/**
* jQuery Backgrounds Control
*/ 
;(function ( $, window, document, undefined ) {
    "use strict"; // jshint ;_;
    
    $.fn.factoryBootstrap309_background = function(){         
        return this.each(function () {             

            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
             
            $('.factory-select-preview-button-upload', $(this)).on('click', function(){ 
                    var parent = $(this).parents('.factory-background-select');
                    var button = $(this);
                    
                    _custom_media = true;
                    wp.media.editor.send.attachment = function(props, attachment){                                    
                        if ( _custom_media ) {                                    
                          parent.find('.factory-select-preview-image').css({background:'url('+attachment.url+') repeat', border:'0', fontSize:'0'});
                          parent.find('.factory-select-preview-image').next()
                                  .attr('value', attachment.url).trigger('change');                          
                        } else {
                          return _orig_send_attachment.apply( this, [props, attachment] );
                        };                            
                    }                    
                    wp.media.editor.open(button);                    
                    $('.add_media').on('click', function(){
                      _custom_media = false;
                    });
                return false;
            }); 
            

           $('.factory-select-preview-image', $(this)).on('click', function(){
                var parent = $(this).parents('.factory-background-select');
                
                if(!$(this).hasClass('on')) {
                     $(this).addClass('on');
                     $('.factory-bgimage-pack', parent).show();
                 } else {
                      $('.factory-bgimage-pack', parent).hide();
                      $(this).removeClass('on');
                 }                 
                
                $('.factory-bgimage-pack-item').on('click', function(){
                    var parent = $(this).parents('.factory-background-select');
                    if( !$(this).hasClass('not-pattern') ) {
                        var puthImage = $(this).data('pattern');  
                        parent.find('.factory-select-preview-image').removeClass('reset');   
                        parent.find('.factory-select-preview-image').css({background:'url('+puthImage+') repeat', border:'0', fontSize:'0'});
                        parent.find('.factory-select-preview-image').next()
                                .attr('value', puthImage).trigger('change');
                    } else {                        
                        parent.find('.factory-select-preview-image').addClass('reset');                        
                        parent.find('.factory-select-preview-image').next()
                                .attr('value', '').trigger('change');
                    }   
                });
              return false;
           }); 
        });      
  }  
  
  $(function(){
    $('.factory-bootstrap-309 .factory-background-select').factoryBootstrap309_background();
  });
  
})( jQuery, window, document );