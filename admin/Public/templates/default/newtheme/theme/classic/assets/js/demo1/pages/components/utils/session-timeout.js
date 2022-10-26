"use strict";

var KTSessionTimeoutDemo = function () {

    var initDemo = function () {
        $.sessionTimeout({
            title: 'Session Timeout Notification',
            message: 'Your session is about to expire.',
            keepAliveUrl: 'https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/session-timeout/keepalive.php',
            redirUrl: '?p=page_user_lock_1',
            logoutUrl: '?p=index.php',
            warnAfter: 0, //warn after 1 seconds
            redirAfter: 3500, //redirect after 10 secons,
            ignoreUserActivity: true,
            countdownMessage: 'Redirecting in {timer} seconds.',
            countdownBar: true
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            initDemo();
        }
    };

}();

jQuery(document).ready(function() {    
    KTSessionTimeoutDemo.init();
});