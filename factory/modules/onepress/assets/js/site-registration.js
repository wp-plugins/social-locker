var registrationManager = {};

(function($){
    
    registrationManager = {
        
        init: function() {
            this.makeRequest();
        },
        
        makeRequest: function() {
            var self = this;
            
            $.ajax(licenseServer, {
                data: licenseGateData,
                type: 'post',
                dataType: 'jsonp'
            })
            .success(function( data ){ 
                
                if ( data.ErrorCode ) {
                    self.showRejectedResponse( data );
                    return;
                } 
                
                self.saveSecret(data.secret);
            })
            .error(function(){
                self.showFailedResponse();
            });
        },
        
        saveSecret: function( secret ) {
            
            $.ajax(licenseAdminUrl, {
                type: 'post',
                data: {
                    secret: secret,
                    action: 'save_site_secret'
                }
            })
            .success(function( data ){ 
                if ( data != 'ok' ) {
                    alert(data);
                    return;
                }
                
                if ( !licenseRedirect || licenseRedirect == '' ) {
                    window.location.reload();
                } else {
                    window.location.href = licenseRedirect;
                }
            })
            .error(function(){
                alert('unknown error');
            });
        },
        
        showRejectedResponse: function( data ) {
            var wrap = $("#license-registration-rejected");
            var messageContainer = wrap.find(".error-container");
            messageContainer.html( data.ErrorText );
            
            this.hideLoader();
            wrap.fadeIn();
        },
        
        showFailedResponse: function() {
            this.hideLoader();
            $("#license-registration-failed").fadeIn();  
        },
        
        hideLoader: function() {
            $(".license-global-loader").hide(); 
        }
    }
    
    $(function(){
        registrationManager.init();
    });
    
})(jQuery)

