 /* DO NOT REMOVE */
"use strict"

/**
 * Return a timestamp with the format "m/d/yy h:MM:ss TT"
 * @type {Date}
 */
function timestamp(time) {
    if (typeof time === "undefined")
        return moment().format('MMM D, YYYY h:mma');
    else {
        time = moment.utc(time, 'MMM D, YYYY h:mma');

        return moment(time.toDate()).format('MMM D, YYYY h:mma');
    }
}

function timediff() {
    time = moment.utc(time, 'MMM D, YYYY h:mma');
    var endTime = moment.utc(moment().utc().format('MMM D, YYYY h:mm a'), 'MMM D, YYYY h:mma');

    var hourDuration = moment.duration(endTime.diff(time)).asHours();
    var minDuration = moment.duration(endTime.diff(time)).asMinutes();
    var secDuration = moment.duration(endTime.diff(time)).asSeconds();

    if (hourDuration > 6) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma');
    } else if (hourDuration > 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(hourDuration, 10) + " hours ago";
    } else if (hourDuration == 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(hourDuration, 10) + " hour ago";
    } else if (minDuration > 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(minDuration, 10) + " minutes ago";
    } else if (minDuration == 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(minDuration, 10) + " minute ago";
    } else {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(secDuration, 10) + " seconds ago";
    }
}


/* Global Variables */
var toka = {};

/** 
 * Toka App
 * @desc: This handles the application's JS session-wide events 
 */
function Toka() {
    this.chata = "https://toka.io:1337";
    //chata.toka.io:1234
    //this.chata = "https://dev.toka.io:1234";
    this.socket;
    this.categoryList = [];
    this.chatrooms = {};
    this.currentChatroom = {};
    this.newMessages = 0;
    
    // Sorted chatroom list flag
    this.sortedChatroomList = false;
    
    // TokaBot
    this.tokabot;
    
    this.ini = function() {
        var self = this; 
        var leftNavApp = new LeftNavApp();
        var topNavApp = new TopNavApp();
        
        self.adjustSiteContentHeight();
        $(window).off("resize").on("resize", function() {
            self.adjustSiteContentHeight();
        });

        $("#search-page").off().on("click", function() {
            self.alert("Search is not available yet.");
        });
        
        leftNavApp.ini();
        topNavApp.ini();
    };
    
    this.iniSockets = function() {
        var self = this;
        
        try {
            self.socket = io.connect(toka.chata, {secure: true});    
            
            // Connection with chat server established
            self.socket.on("connect", function() {
                console.log('Connection opened.');
            }); 
            
            // Connect to chat server closed (Server could be offline or an error occurred or client really disconncted)
            self.socket.on("disconnect", function() {
                console.log('Connection closed.');
            });
        }
        catch (err) {
            console.log('Could not connect to chata!');
        }
    }
    
    this.adjustSiteContentHeight = function() {
        $("#site-content").css("min-height", $("#site").height() - $("#site-menu").height());
        $("#site-left-nav").css("min-height", $("#site").height() - $("#site-menu").height());
    };
    
    this.getCookie = function(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(";");
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") 
                c = c.substring(1);
            if (c.indexOf(name) == 0) 
                return c.substring(name.length,c.length);
        }
        return "";
    }
    
    this.promptLogin = function() {
        $("#login-form").off().on('shown.bs.modal', function() {
            $("#toka-login-username").focus();
        });
        $("#login-form").modal('show');
    }
    
    this.resetTitle = function() {
        var $title = $("title");
        $title.text("Toka");
    };
    
    this.setChatrooms = function(chatrooms) {
        var self = this;
        
        for (var chatroomId in chatrooms) {
            self.chatrooms[chatroomId] = new Chatroom(chatrooms[chatroomId]);
        }
    }
    
    this.setTitle = function(title) {
        var $title = $("title");
        $title.text(title);
    };
}

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

/* Data Sets */
// Banned word list
var banned_list={"bitch":1,"dick":1,"fuck":1,"motherfucker":1,"penis":1,"shit":1,"vagina":1,"wanker":1, "god":1, "jesus":1, "christ":1, "satan":1};
var reserved_list={"google":1,"facebook":1,"linkedin":1,"microsoft":1,"twitter":1,"support":1,"tokaadmin":1,"toka_admin":1,"tokahelp":1,"toka_help":1,"tokasupport":1,"toka_support":1,"tokabot":1,"toka_bot":1};