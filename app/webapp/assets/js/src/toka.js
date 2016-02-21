 "use strict"

/** 
 * Toka App
 * @desc: This handles the application's JS session-wide events 
 */
var toka = new (function() {
    this.atlas;
    this.chata = "https://toka.io:1337";
    this.socket;
    this.chatrooms = {};
    this.currentChatroom = {};
    this.newMessages = 0;
    
    // Sorted chatroom list flag
    this.sortedChatroomList = false;
    
    // TokaBot
    this.tokabot;
    var leftNavApp = new LeftNavApp();
    var topNavApp = new TopNavApp();
    
    this.ini = function() {
        var self = this; 
        
        self.adjustSiteContentHeight();
        $(window).off("resize").on("resize", function() {
            self.adjustSiteContentHeight();
        });
        
        leftNavApp.ini();
        topNavApp.ini();
    };
    
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
    };
    
    this.hasUserSession = function() {
        return !(this.getCookie("username") === "");
    };
    
    this.promptLogin = function() {
        $("#login-form").off().on('shown.bs.modal', function() {
            $("#toka-login-username").focus();
        });
        $("#login-form").modal('show');
    };
    
    this.resetTitle = function() {
        this.setTitle("Toka");
    };
    
    this.setChatrooms = function(chatrooms) {
        var self = this;
        
        for (var chatroomId in chatrooms) {
            self.chatrooms[chatroomId] = new Chatroom(chatrooms[chatroomId]);
        }
    };
    
    this.setTitle = function(title) {
        $("title").text(title);
    };
})();

/* Data Sets */
// Banned word list
var banned_list={"bitch":1,"dick":1,"fuck":1,"motherfucker":1,"penis":1,"shit":1,"vagina":1,"wanker":1, "god":1, "jesus":1, "christ":1, "satan":1};
var reserved_list={"google":1,"facebook":1,"linkedin":1,"microsoft":1,"twitter":1,"support":1,"tokaadmin":1,"toka_admin":1,"tokahelp":1,"toka_help":1,"tokasupport":1,"toka_support":1,"tokabot":1,"toka_bot":1};