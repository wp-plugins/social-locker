/*!
 * Notices
 *
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2015, OnePress Ltd
 * 
 * @package factory-notices 
 * @since 1.0.0
 */

(function($){
    
    var achievementPopups = {

        init: function() {
            var self = this;
        
            $("#onp-sl-review .onp-sl-button-link, #onp-sl-review .onp-sl-button-primary").click(function(){
                var $popup = $("#onp-sl-review");

                $(".factory-popup-overlay").fadeOut(300);
                $popup.fadeOut(300);

                var data = {
                    achievementType: 'review',
                    achievementValue: $(this).data('achievement-value'),
                    action: "onp_sl_hide_achievement"
                };

                $.ajax({
                    url: ajaxurl,
                    type: "post",
                    data: data
                });

                self.setCookie("onp_sl_review_closed", 1, 1000);

                if ( $(this).is(".onp-sl-button-primary") ) return true;
                else return false;
            }); 
        },
        
        setCookie: function (name, value, days) {
            var expires;

            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            } else {
                expires = "";
            }
            
            document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
        },

        getCookie: function(name) {
        
            var nameEQ = encodeURIComponent(name) + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }
    };
    
    $(function(){
        achievementPopups.init();
    });

})(jQuery);