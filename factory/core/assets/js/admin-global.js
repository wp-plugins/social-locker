jQuery(document).ready(function($){
    
    $(".onp-close-alert").click(function(){
        var cookieName = $(this).data('cookie');
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + 1);
        document.cookie = "fy_" + cookieName + "=1; expires="+exdate.toUTCString();
        $(this).parents('.error').fadeOut();
    });
});