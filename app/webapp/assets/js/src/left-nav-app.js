"use strict"

/**
 * LeftNavApp
 * @desc: Control left navigation bar events/interactions 
 */
function LeftNavApp() {
    this.ini = function() {
        $('#profile').on('click', function() {
            if ($('#profile-menu').hasClass('open')) {
                $('#profile-menu').slideUp(500);
                $('#profile-menu').removeClass('open').addClass('closed');
            } else {
                $('#profile-menu').slideDown(500);
                $('#profile-menu').removeClass('closed').addClass('open');
            }
        });
        
        $("#chatfeed-btn").off('click').on('click', function() {
            var src = $("#chatfeed iframe").attr("src");
            
            if (src == "about:blank")
                $("#chatfeed iframe").attr('src', "/chatroom/"+toka.getCookie('username')+"?embed=1&target=_blank");
            $("#chatfeed").modal('show'); 
        });
    }
}