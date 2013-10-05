var licenseManager = {};

(function($){
    
    licenseManager = {
        
        init: function() {
            
            this.initLicenseForm();
            this.initManualActivationForm();
        },
        
        initLicenseForm: function() {
            var button = $("#license-submit");
            var form = button.parents('form');
            
            button.click(function(){
                form.submit();
                return false;
            });
            
            // faq
            
            $("#faq-block .faq-header").click(function(){
                var next = $(this).next();
                if ( next.is(":visible") ) next.fadeOut();
                else next.fadeIn();
                return false;
            });
            
            $("#open-faq").click(function(){
                $("#how-to-find-the-key").click();
                window.location.href = "#how-to-find-the-key";
                return false;
            });
        },
        
        initManualActivationForm: function() {
            var button = $("#manual-trial-submit");
            var form = button.parents('form');
            
            button.click(function(){
                form.submit();
                return false;
            });            
        }
    }
    
    $(function(){
        licenseManager.init();
    });
    
})(jQuery)

