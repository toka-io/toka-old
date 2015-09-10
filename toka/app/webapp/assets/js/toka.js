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
}
Toka.prototype.ini = function() {
    var self = this; 
    var leftNavApp = new LeftNavApp();
    
    self.adjustSiteContentHeight();
    $(window).off("resize").on("resize", function() {
        self.adjustSiteContentHeight();
    });

    $("#search-page").off().on("click", function() {
        self.alert("Search is not available yet.");
    });
    
    leftNavApp.ini();
};
Toka.prototype.iniSockets = function() {
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
        self.errSocket(err);
    }
}
Toka.prototype.service = function(service, action, method, data, loadingOptions) {
    var self = this;
    
    if (typeof loadingOptions === "undefined")
        loadingOptions = {};
    
    $.ajax({
        url: service + "/" + action,
        type: method,
        data: data,
        dataType: "json",
        beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
        complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
        success: function(response) {
            self.responseHandler(service, action, method, data, response);
        }
    });
};
Toka.prototype.responseHandler = function(service, action, method, data, response) {
    var self = this;
    
    if (service === "chatroom" && action === "create") {
        if (response["status"] !== 200) {
            var statusMessage = response["message"];
            statusMessage = statusMessage.charAt(0).toUpperCase() + statusMessage.slice(1);
            self.alertCreateChatroom("Server Error: " + statusMessage);
        }
        else {
            window.location.href = "/chatroom/" + response["chatroomId"];
        }
    }
};
Toka.prototype.form = function(service, action, method, data) {
    var self = this;
    
    var $form = $("<form></form>", {
        "action" : service + "/" + action,
        "method" : "POST"
    }); 
    
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            $("<input />", {
                "type" : "hidden",
                "name" : key,
                "value" : data[key]
            }).appendTo($form);
        }
    }
    
    $form.submit();
};
Toka.prototype.adjustSiteContentHeight = function() {
    // Need to fix
    $("#site-content").css("min-height", $("#site").height() - $("#site-menu").height());
    $("#site-left-nav").css("min-height", $("#site").height() - $("#site-menu").height());
};
Toka.prototype.alert = function(alertMsg) {
    var $alert = $("<div></div>", {
        "id" : "site-alert-text",
        "class" : "alert alert-info alert-dismissible"
    }).append($("<button></button>", {
        "type" : "button",
        "class" : "close",
        "data-dismiss" : "alert",
        "aria-label" : "Close"
    }).append($("<span></span>", {
        "aria-hidden" : "true",
        "html" : "&times;"
    })));
    
    $alert.append($("<span></span>", {        
        "text" : alertMsg
    }));
    
    $("#site-alert").empty().append($alert);
};
Toka.prototype.alertCreateChatroom = function(alertMsg) {
    var $alert = $("<div></div>", {
        "id" : "create-chatroom-alert-text",
        "class" : "alert alert-warning alert-dismissible",
        "text" : alertMsg
    }).append($("<button></button>", {
        "type" : "button",
        "class" : "close",
        "data-dismiss" : "alert",
        "aria-label" : "Close"
    }).append($("<span></span>", {
        "aria-hidden" : "true",
        "html" : "&times;"
    })));
    
    $("#create-chatroom-alert").empty().append($alert);
};
Toka.prototype.createChatroom = function(chatroom) {
    var self = this;

    var username = self.getCookie("username");
    
    if (username === "") {
        toka.alert("Cannot create chatroom! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["categoryName"] = chatroom.categoryName;
    data["chatroomName"] = chatroom.chatroomName;
    data["info"] = chatroom.info;
    data["tags"] = chatroom.tags;
    
    var loadingOptions = {
        "beforeSend" : function() {
            $("#create-chatroom-loader").show();
        },
        "complete" : function() {
            $("#create-chatroom-loader").hide();
        }
    }
    
    self.service("chatroom", "create", "POST", data, loadingOptions);
};
Toka.prototype.errSocket = function(err) {
    console.log("Websockets!!! *shakes fist at sky* ---> " + err);
}
Toka.prototype.promptLogin = function() {
    $("#login-form").off().on('shown.bs.modal', function() {
        $("#toka-login-username").focus();
    });
    $("#login-form").modal('show');
}
Toka.prototype.resetTitle = function() {
    var $title = $("title");
    $title.text("Toka");
};
Toka.prototype.setChatrooms = function (chatrooms) {
    var self = this;
    
    for (var chatroomId in chatrooms) {
        self.chatrooms[chatroomId] = new Chatroom(chatrooms[chatroomId]);
    }
}
Toka.prototype.setSubtitle = function($subtitle) {
    $("#site-subtitle").append($subtitle)
};
Toka.prototype.setTitle = function(title) {
    var $title = $("title");
    $title.text(title);
};
Toka.prototype.validateCreateChatroom = function(chatroom) {
    var self = this;
    
    if (chatroom.chatroomName === "") {
        self.alertCreateChatroom("Please provide a chatroom title.");
        return false;
    } if (chatroom.chatroomName.trim().length > 100) {
        self.alertCreateChatroom("Please keep chatroom titles limited to 100 characters.");
        return false;
    } else if (chatroom.categoryName === "0") {
        self.alertCreateChatroom("Please select a category.");
        return false;
    } else if (chatroom.tags.length > 5) {
        self.alertCreateChatroom("Please limit tags to 5.");
        return false;
    }
    
    return true;
}

function LeftNavApp() {
    this.ini = function() {
        $('#profile').on('click', function() {
            if ($('#profile-menu').hasClass('open')) {
                $('#profile-menu').slideUp(500);
                $('#profile-menu').removeClass('open').addClass('closed');
                $('#profile-img').attr('src', '/assets/images/icons/add.svg');
            } else {
                $('#profile-menu').slideDown(500);
                $('#profile-menu').removeClass('closed').addClass('open');
                $('#profile-img').attr('src', '/assets/images/icons/minus.svg');
            }
        });
        
        $("#create-chatroom-btn").off("click").on("click", function() {
            var chatroom = new Chatroom({});
            chatroom.chatroomName = $("#create-chatroom-title").val().trim();
            chatroom.categoryName = $("#create-chatroom-category").val();
            chatroom.info = $("#create-chatroom-info").val();
            
            try {
                chatroom.tags = $("#create-chatroom-tags-input input").val().replace(/[\s,]+/g, ',').split(",");
                
                // flatten tags to lowercase
                for (var i = 0; i < chatroom.tags; i++) {
                    chatroom.tags[i] = chatroom.tags[i].toLowerCase(); 
                }
            } catch (err) {
                chatroom.tags = [];
            }
            
            if (self.validateCreateChatroom(chatroom)) {
                self.createChatroom(chatroom);
            }
        });
        
        $("#chatfeed-btn").off('click').on('click', function() {
            if ($("#chatfeed iframe").attr('src') == "")
                $("#chatfeed iframe").attr('src', "/chatroom/"+toka.getCookie('username')+"?embed=1&target=_blank");
            $("#chatfeed").modal('show'); 
        });
    }
}



/** 
 * Category object
 * @desc: Stores category attributes
 */
function Category(prop) {
    this.categoryId = prop["categoryId"];
    this.categoryName = prop["categoryName"];
    this.categoryImageURL = prop["categoryImageUrl"];
}


/**
 * Chatroom
 * @desc: Stores chatroom attributes 
 */
function Chatroom(prop) {
    this.newMessages = 0; // This will be used later for multiple chats in one page
    this.lastSender = "";
    this.autoScroll = true;
    
    this.banned = prop["banned"];
    this.categoryName = prop["categoryName"];
    this.chatroomId = prop["chatroomId"];
    this.chatroomName = prop["chatroomName"];
    this.chatroomType = prop["chatroomType"];
    this.coOwner = prop["coOwner"];
    this.guesting = prop["guesting"];
    this.info = prop["info"];
    this.maxSize = prop["maxSize"];
    this.mods = prop["mods"];
    this.owner = prop["owner"];
    this.tags = prop["tags"];
    
    // Extra attributes to add to database
    this.groupMessageFlag = "n";
    
    this.selectChatroomItem = ".chatroom-item[data-chatroom-id='"+this.chatroomId+"']";
    this.selectChatroomItemTopContainer = this.selectChatroomItem + " .chatroom-item-top";
    this.selectChatroomItemUserCount = this.selectChatroomItem + " .chatroom-item-bottom .chatroom-item-details .chatroom-item-users .chatroom-item-users-count";
        
    this.selectChatroom = ".chatroom[data-chatroom-id='" + this.chatroomId + "']";
    this.selectChatroomList = this.selectChatroom + " .messages";
    this.selectChatroomBody = this.selectChatroom + " .messages-container";    
    this.selectChatroomInfoBox = this.selectChatroom + " .infobox";
    this.selectChatroomInputMsg = this.selectChatroom + " .inputbox .input-msg";
    this.selectChatroomTitleMenuUser = this.selectChatroom + " .title-menu .users";
    this.selectChatroomUserList = this.selectChatroom + " .user-list";
}
Chatroom.prototype.iniChatroom = function() {
    var self = this;   
    
    $(self.selectChatroomBody).height(self.getHeight());
    $(window).on("resize", function() {
        $(self.selectChatroomBody).height(self.getHeight());
    });
    
    $(window).on("focus", function() {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
    });
    
    // Reset title
    $(self.selectChatroomInputMsg).off("click").on("click", function() {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
    });
    
    // Send message on enter key
    $(self.selectChatroomInputMsg).off("keydown").on("keydown", function(e) {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
        // On [Enter] key        
        if (e.which === 13) {
            if (!e.shiftKey) {
                e.preventDefault();
                self.sendMessage();
                $(self.selectChatroomInputMsg).attr("rows", 1);
                $(self.selectChatroomBody).height(self.getHeight());
            }
            else {
                var rows = parseInt($(self.selectChatroomInputMsg).attr("rows"));
                if (rows < 4) {
                    $(self.selectChatroomInputMsg).attr("rows", rows+1);
                    $(self.selectChatroomBody).height(self.getHeight());
                }
            }
        }
    });
    
    // Show chatroom user list on hover
    $(self.selectChatroomTitleMenuUser).off().on({
        mouseenter: function() {
            toka.socket.emit("users", toka.currentChatroom.chatroomId);
            
            var offset = $(this).offset();
            var $userList = $(self.selectChatroomUserList);
            $userList.width("auto");
            var width = $userList.width();
            $userList.width(width);
            $userList.show().offset({top: offset.top, left: offset.left - width});
        },
        mouseleave: function () {
            $(self.selectChatroomUserList).hide();
        }
    });
    
    // chatroom scrollbar settings
    $(self.selectChatroomBody).mCustomScrollbar({
        theme: "dark",
        alwaysShowScrollbar: 1,
        mouseWheel:{ scrollAmount: 240, normalizeDelta: true,},
        callbacks: {
            whileScrolling: function() {
                self.autoScroll = false;
                
                if (this.mcs.topPct >= 99.5)
                    self.autoScroll = true;
            }
        }
    });
    
    // chatroom info page scrollbar settings
    $(self.selectChatroomInfoBox).mCustomScrollbar({
        alwaysShowScrollbar: 0,
        mouseWheel:{ scrollAmount: 120 }
    }); 
};
Chatroom.prototype.getHeight = function() {
    return $("#site").height() - $("#site-menu").height() - $(".chatroom-heading").outerHeight(true) - $(".inputbox").outerHeight();
}
Chatroom.prototype.loadHistory = function(history) {
    toka.tokabot.loadHistory(history);
}
/*
 * @message: Message object
 */
Chatroom.prototype.receiveMessage = function(message) {
    var self = this;
    
    var $chat = $(self.selectChatroomList);
    var username = toka.getCookie("username");
    
    message.timestamp = timestamp(message.timestamp);
    
    toka.tokabot.receiveMessage(message);    
};
Chatroom.prototype.scrollChatToBottom = function() {
    var self = this;
    
    $(self.selectChatroomBody).mCustomScrollbar("update");
    $(self.selectChatroomBody).mCustomScrollbar("scrollTo", "bottom", {scrollInertia:0});
}
Chatroom.prototype.sendMessage = function() {
    var self = this;
    
    var username = toka.getCookie("username");

    if (username === "") {
        toka.promptLogin();
        return;
    }
    
    // Gets input text
    var text = $(self.selectChatroomInputMsg).val();
    var message = new Message(self.chatroomId, username, text, timestamp());
    
    // Prevents users from submitting empty text or just spaces
    if (text.trim() === "") return;
    
    // If msg is valid, clear it
    $(self.selectChatroomInputMsg).val("");
    
    // TokaBot parser
    toka.tokabot.sendMessage(message);
};
Chatroom.prototype.update = function() {
    var self = this;

    var username = toka.getCookie("username");
    
    if (username === "") {
        toka.alert("Cannot update chatroom! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["chatroomId"] = self.chatroomId;
    data["categoryName"] = self.categoryName;
    data["chatroomName"] = self.chatroomName;
    data["info"] = self.info;
    data["tags"] = self.tags;
    
    var loadingOptions = {
        "beforeSend" : function() {
            $("#update-chatroom-loader").show();
        },
        "complete" : function() {
            $("#update-chatroom-loader").hide();
        }
    }
    
    $.ajax({
        url: "/chatroom/"+self.chatroomId+"/update",
        type: "POST",
        data: data,
        dataType: "json",
        beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
        complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
        success: function(response) {
            if (response["status"] !== 200) {
                var statusMessage = response["message"];
                statusMessage = statusMessage.charAt(0).toUpperCase() + statusMessage.slice(1);
                self.alertUpdateChatroom("Server Error: " + statusMessage);
            }
            else {
                window.location.href = "/chatroom/" + response["chatroomId"];
            }
        }
    });
};
Chatroom.prototype.updateChatroomItemUsers = function(userCount) {
    var self = this;
    
    $(self.selectChatroomItemUserCount).text(userCount);
};


/* Message Object */

function Message(chatroomId, username, text, timestamp) {
    this.chatroomId = chatroomId;
    this.username = username;
    this.text = text;
    this.timestamp = timestamp;
}

/* Data Sets */
// Banned word list
var banned_list={"bitch":1,"dick":1,"fuck":1,"motherfucker":1,"penis":1,"shit":1,"vagina":1,"wanker":1, "god":1, "jesus":1, "christ":1, "satan":1};
var reserved_list={"google":1,"facebook":1,"linkedin":1,"microsoft":1,"twitter":1,"support":1,"tokaadmin":1,"toka_admin":1,"tokahelp":1,"toka_help":1,"tokasupport":1,"toka_support":1,"tokabot":1,"toka_bot":1};