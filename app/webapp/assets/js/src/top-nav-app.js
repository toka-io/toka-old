"use strict"

/**
 * TopNavApp
 * @desc: Control left navigation bar expansion
 */
function TopNavApp() {
    this.ini = function() {
        $('#toka-left-nav-toggle').on('click', function() {
            if ($('#site-left-nav').hasClass('closed')) {
                var contentWidth = Number($('#site-content').css('width').replace('px', ''))-220;
                $('#site-left-nav').toggle('slide', 'left', 800);
                $('#site-content').effect('size', {to: {'margin-left': '220px', 'width': contentWidth+'px'}}, 800);
                $('#site-left-nav').removeClass('closed').addClass('open');
            } else {
                var contentWidth = Number($('#site-content').css('width').replace('px', ''))+220;
                $('#site-left-nav').toggle('slide', 'right', 800);
                $('#site-content').effect('size', {to: {'margin-left': '0px', 'width': contentWidth+'px'}}, 800);
                $('#site-left-nav').removeClass('open').addClass('closed');
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