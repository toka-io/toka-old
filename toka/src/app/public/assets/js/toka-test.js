 /* DO NOT REMOVE */
"use strict"


/* Global Variables */
var toka = {};


/* General Functions */

/**
 * Retrieves cookie
 * @type {String}
 */
function getCookie(cname) {
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

/**
 * Return a timestamp with the format "m/d/yy h:MM:ss TT"
 * @type {Date}
 */
function timestamp(time) {
    if (typeof time === "undefined")
        return moment().format('MMM D, YYYY h:mm a');
    else {
        time = moment.utc(time, 'MMM D, YYYY h:mma').toDate();
        return moment(time).format('MMM D, YYYY h:mma');
    }
}

/** 
 * Toka App
 * @desc: This handles the application's JS session-wide events 
 */
function Toka() {
    this.socket;
	this.categories = {};
	this.categoryList = [];
	this.chatrooms = {};
	this.chatroomList = [];
	this.currentChatroom = {};
	this.newMessages = 0;
	
	// TokaBot
	this.tokabot = new TokaBot();
}
Toka.prototype.ini = function() {
    var self = this; 
    
    /* Official Event Bindings */
    
    $("#toka-login-password").off().on("keydown", function(e) {
        // On [Enter] key
        if (e.which == "13") {
            if (!e.shiftKey) {
                self.login();
            }
        }
    });
    
    $("#toka-login-username").off().on("keydown", function(e) {
        // On [Enter] key
        if (e.which == "13") {
            if (!e.shiftKey) {
                self.login();
            }
        }
    });
    
    /* Pending Bindings */
    
    $("#profile-page").off().on("click", function() {
        self.alert("User profile is not available yet.");
    });
    
    $("#settings-page").off().on("click", function() {
        self.alert("User settings are not available yet.");
    });
    
    $("#help-page").off().on("click", function() {
        self.alert("The help page is currently not available. If you have any questions, please email andytlim@gmail.com");
    });

    $("#search-page").off().on("click", function() {
        self.alert("Search is not available yet.");
    });
};
Toka.prototype.iniChatroomList = function(chatrooms) {
    var self = this;
    
    /* Bind Chatroom List Specific Events */
    $("#create-chatroom-tags-input input").tagsinput({
        tagClass: "chatroom-tag label label-info"
    });
    
    $("#chatroom-list-add div[data-toggle='tooltip']").tooltip({
        placement : 'bottom'
    });
    
    $("#create-chatroom-btn").off("click").on("click", function() {
        var chatroom = new Chatroom({});
        chatroom.chatroomName = $("#create-chatroom-title").val().trim();
        chatroom.categoryName = $("#create-chatroom-category").val();
        try {
            chatroom.tags = $("#create-chatroom-tags-input input").val().replace(/[\s,]+/g, ',').split(",");
        } catch (err) {
            chatroom.tags = [];
        }
        
        if (self.validateCreateChatroom(chatroom)) {
            self.createChatroom(chatroom);
        }
    });
    
    /* Load Toka Chatroom List */
    self.setChatrooms(chatrooms);
    
    try {
        self.socket = io.connect("http://toka.io:620");    
        
        // Connection with chat server established
        self.socket.on("connect", function() {
            console.log('Connection opened.');
            toka.socket.emit("activeViewerCount");
        }); 
        
        // Retreive list of users for active chatrooms
        self.socket.on("activeViewerCount", function(activeViewerCount) {
            for (var chatroomID in activeViewerCount) {
                if (self.chatrooms.hasOwnProperty(chatroomID))
                    self.chatrooms[chatroomID].updateChatroomItemUsers(activeViewerCount[chatroomID]);
            }    
        });
        
        // Connect to chat server closed (Server could be offline or an error occurred or client really disconncted)
        self.socket.on("disconnect", function() {
            console.log('Connection closed.');
        });
    }
    catch (err) {
        self.errSocket(err);
    }
};
Toka.prototype.iniChatroom = function(chatroom) {
    var self = this;
    
    var chatroom = new Chatroom(chatroom);
    chatroom.iniChatroom();
    self.currentChatroom = chatroom; 
    self.chatrooms[chatroom.chatroomID] = chatroom;
    
    try {
        self.socket = io.connect("http://toka.io:620");    
        
        // Connection with chat server established
        self.socket.on("connect", function() {
            console.log('Connection opened.');
            self.socket.emit("join", {
                "chatroomID" : self.currentChatroom.chatroomID,
                "username" : getCookie("username")
            });
            
            self.socket.emit("users", self.currentChatroom.chatroomID);
        });
        
        // Retreive list of users for active chatrooms
        self.socket.on("activeViewerCount", function(activeViewerCount) {
            $("#chatroom-title-users span").text(activeViewerCount[self.currentChatroom.chatroomID]);
        });
        
        // Retreive list of users for active chatrooms
        self.socket.on("users", function(users) {
            if (users.hasOwnProperty(self.currentChatroom.chatroomID)) {
                $("#chatroom-user-list ul").empty();
                for (var i = 0; i < users[self.currentChatroom.chatroomID].length; i++) {
                    $("#chatroom-user-list ul").append($("<li></li>", {
                        "text" : users[self.currentChatroom.chatroomID][i]
                    }));
                }
            }
        });
        
        // Retrieve chat history for active chatrooms
        self.socket.on("history", function(history) {        
            // Find the chatroom the history belongs to and populate the chat window
            if (self.chatrooms.hasOwnProperty(history.chatroomID)) {
                for (var i=0; i < history.data.length; i++) {
                    var message = new Message(history.data[i].chatroomID, history.data[i].username, history.data[i].text, timestamp(history.data[i].timestamp));
                    self.chatrooms[history.chatroomID].receiveMessage(message);                
                }
            }
        });
        
        // Retreives messages for active chatrooms
        self.socket.on("receiveMessage", function(message) {        
            // Convert message to toka js object (as opposed to the Node JS obj...maybe we want to sync them?
            message = new Message(message.chatroomID, message.data.username, message.data.text, timestamp(message.data.timestamp));
            console.log(self.socket);
            if (self.chatrooms.hasOwnProperty(message.chatroomID)) {
                self.chatrooms[message.chatroomID].receiveMessage(message);
            }        
            
            // If user is active in the chat text box, then they won't an alert for that chatroom
            if (!$(self.selectChatroomInputMsg).is(":focus")) {
                toka.newMessages++;
                toka.setTitle("(1+) Toka");
            }
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
Toka.prototype.iniSockets = function() {
    var self = this;
    
    /* Handle socket events here!! 
     * Socket events can be sent in other classes, but al events "received" should be handled here
     */
    
    try {
        self.socket = io.connect("http://toka.io:620");    
        
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
Toka.prototype.service = function(service, action, method, data) {
    var self = this;
    
    $.ajax({
        url: "/service/" + service + "/" + action,
        type: method,
        data: data,
        dataType: "json",
        success: function(response) {
            self.responseHandler(service, action, method, data, response);
        }
    });
};
Toka.prototype.responseHandler = function(service, action, method, data, response) {
    var self = this;
    
    if (service === "category" && action === "all") {
        var categoryList = response["data"];
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
Toka.prototype.addContent = function($content) {
    $("#site-content").append($content);
};
Toka.prototype.adjustSiteContentHeight = function() {
    // Need to fix
    $("#site-content").css("min-height", $(window).height());
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
    var $alert =$("<div></div>", {
        "id" : "create-chatroom-alert-text",
        "class" : "alert alert-warning",
        "text" : alertMsg
    });
    
    $("#create-chatroom-alert").empty().append($alert);
};
Toka.prototype.alertLogin = function(alertMsg) {
    var $alert =$("<div></div>", {
        "id" : "login-alert-text",
        "class" : "alert alert-warning",
        "text" : alertMsg
    });
    
    $("#login-alert").empty().append($alert);
};
Toka.prototype.alertSignup = function(alertMsg) {
    var $alert =$("<div></div>", {
        "id" : "signup-alert-text",
        "class" : "alert alert-warning",
        "text" : alertMsg
    });
    
    $("#signup-alert").empty().append($alert);
};
Toka.prototype.clearContent = function() {
    $("#site-subtitle").empty();
    $("#site-alert").empty();
    $("#site-content").empty();
};
Toka.prototype.createChatroom = function(chatroom) {
    var self = this;

    var username = getCookie("username");
    
    if (username === "") {
        toka.alert("Cannot create chatroom! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["categoryName"] = chatroom.categoryName;
    data["chatroomName"] = chatroom.chatroomName;
    data["tags"] = chatroom.tags;
    
    self.service("chatroom", "create", "POST", data);
};
Toka.prototype.deactivateUser = function() {
    var self = this;
    
    var username = getCookie("username");
        
    if (username === "") {
        toka.alert("Cannot deactivate this account! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["username"] = username;
    
    self.service("user", "deactivate", "POST", data);
};
Toka.prototype.domChatroomList = function(categoryName) {
    var self = this;
    
    toka.clearContent();

    var $chatroomListContainer = $("<div></div>", {
        "id" : "chatroom-list"
    });    
    
    var $chatroomListTitle = $("<div></div>", {
        "id" : "chatroom-list-title",
       "text" : categoryName
    });
    
    self.addContent($chatroomListContainer);
    self.setSubtitle($chatroomListTitle);
    
    for (var i = 0; i < self.chatroomList.length; i++) {
        self.chatroomList[i].domChatroomItem();
    }
};
Toka.prototype.errSocket = function(err) {
    console.log("Websockets!!! *shakes fist at sky* ---> " + err);
}
Toka.prototype.getAllCategories = function() {
    var self = this;
    
    var data = {};
    
    self.service("category", "all", "GET", data);
};
Toka.prototype.isLoggedIn = function() {
    var self = this;
    
    return true;
};
/*
 * @note: Provide users a message when their cookie is missing before logging out
 */
Toka.prototype.logout = function() {
    var self = this;
    
    var username = getCookie("username");
    
    if (username === "") {
        toka.alert("You are already logged out."); // Make this a better pop up
        return;
    }
    
    var data = {};
    
    self.service("logout", "", "POST", data);
};
Toka.prototype.resetTitle = function() {
    var $title = $("title");
    $title.text("Toka");
};
Toka.prototype.setChatrooms = function (chatrooms) {
    var self = this;
    
    for (var chatroomID in chatrooms) {
        self.chatrooms[chatroomID] = new Chatroom(chatrooms[chatroomID]);
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
Toka.prototype.validateLogin = function() {
    var self = this;
    
    var password = $("#toka-login-password").val().trim();
    var username = $("#toka-login-username").val().trim();
    
    if (username === "") {
        toka.alertLogin("Please provide a username.");
        return false;
    } else if (password === "") {
        toka.alertLogin("Please provide a password.");
        return false;
    }
    
    return true;
};
Toka.prototype.validateSignup = function() {
    var self = this;
    
    var email = $("#toka-signup-email").val().trim();
    var password = $("#toka-signup-password").val().trim();
    var passwordRepeat = $("#toka-signup-password-again").val().trim();
    var username = $("#toka-signup-username").val().trim();
    
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    if (username === "") {
        toka.alertSignup("Please provide a username.");
        return false;
    }
    else if (!/^[a-zA-Z0-9_]{3,25}$/.test(username)) {
        toka.alertSignup("Username must be 3-25 characters in length and can contain only alphanumeric characters with the exception of '_'.");
        return false;
    }
    else if (email === "") {
        toka.alertSignup("Please provide an email address.");
        return false;
    } else if (!emailRegex.test(email)) {
        toka.alertSignup("Please provide a valid email address (i.e. email@address.com).");
        return false;
    } else if (password === "") {
        toka.alertSignup("Please provide a password.");
        return false;
    } else if (password !== passwordRepeat) {
        toka.alertSignup("Passwords do not match.");
        return false;
    }
    
    return true;
};

/** 
 * Category object
 * @desc: Stores category attributes
 */
function Category(prop) {
    this.categoryID = prop["categoryID"];
    this.categoryName = prop["categoryName"];
    this.categoryImageURL = prop["categoryImageUrl"];
}
Category.prototype.ini = function() {
    var self = this;
};
Category.prototype.service = function(service, action, method, data) {
    var self = this;
    
    // Sub services do not extend services at the moment, so service is not used    
    $.ajax({
        url: "/service/category/" + action,
        type: method,
        data: data,
        dataType: "json",
        success: function(response) {
            self.responseHandler(service, action, method, data, response);
        }
    });
};
Category.prototype.responseHandler = function(service, action, method, data, response) {
    var self = this;
};
Category.prototype.getChatrooms = function() {
    var self = this;
    
    var data = {};
    data["categoryName"] = self.categoryName;
    
    self.service("category", "chatrooms", "GET", data);
};


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
    this.chatroomID = prop["chatroomID"];
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
    
    this.selectChatroomItem = ".chatroom-item[data-chatroom-id='"+this.chatroomID+"']";
    this.selectChatroomItemTopContainer = this.selectChatroomItem + " .chatroom-item-top";
    this.selectChatroomItemUserCount = this.selectChatroomItem + " .chatroom-item-bottom .chatroom-item-details .chatroom-item-users .chatroom-item-users-count";
        
    this.selectChatroom = ".chatroom[data-chatroom-id='" + this.chatroomID + "']";
    this.selectChatroomMsgContainer = this.selectChatroom + " .panel-body";
    this.selectChatroomInputMsg = this.selectChatroom + " .panel-footer div .chatroom-input-msg";
}
Chatroom.prototype.iniChatroom = function() {
    var self = this;   
    
    $(self.selectChatroomMsgContainer).height($(window).height()-250);
    $(window).off("resize").on("resize", function() {
        $(self.selectChatroomMsgContainer).height($(window).height()-250);
    });
    
    // Reset title
    $(self.selectChatroomInputMsg).off("click").on("click", function() {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
        self.autoScroll = true;
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
            }
        }
    });
    
    // Show chatroom user list on hover
    $("#chatroom-title-users").off().on({
        mouseenter: function() {
            var offset = $(this).offset();
            var width = $("#chatroom-user-list").width();
            $("#chatroom-user-list").width(width);
            $("#chatroom-user-list").show().offset({top: offset.top, left: offset.left - width});
        },
        mouseleave: function () {
            $("#chatroom-user-list").hide();
        }
    });
    
    // Disable autoscroll on scroll
    $(".chatroom > .panel-body").off("mousewheel DOMMouseScroll MozMousePixelScroll").on("mousewheel DOMMouseScroll MozMousePixelScroll", function() {
        self.autoScroll = false;
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            self.autoScroll = true;
        }
    });
    
    $("#chatroom-update").off().on("click", function() {
        self.updateChatroom();
    });
    
    $("#chatroom-user-mod").off().on("click", function() {
        var user = "jihoon";
        self.modUser(user);
    });
    
    $("#chatroom-user-unmod").off().on("click", function() {
        var user = "jihoon";
        self.unmodUser(user);
    });
    
    $("#chatroom-delete").off().on("click", function() {
        
    });
};
Chatroom.prototype.iniChatroomItem = function() {
    var self = this;

    $(self.selectChatroomItemTopContainer).off().on("click", function() {
        toka.setTitle(self.chatroomName + " - Toka");
        toka.clearContent();
        self.domChatroom(); 
        
        toka.chatroomList = [];
        toka.chatrooms = {};
        toka.chatroomList.push(self);
        toka.chatrooms[self.chatroomID] = self;
        
        try {
            toka.socket.emit("join", {
                "chatroomID" : self.chatroomID,
                "username" : getCookie("username")
            });
        }
        catch (err) {
            toka.errSocket(err);
        }
    });
};
Chatroom.prototype.service = function(service, action, method, data) {
    var self = this;
    
    // Sub services do not extend services at the moment, so service is not used
    $.ajax({
        url: "/service/chatroom/" + action,
        type: method,
        data: data,
        dataType: "json",
        success: function() {
            
        }
    });
};
Chatroom.prototype.domChatroom = function() {
    var self = this;
    
    var $chatroomContainer = $("<div></div>", {
       "class" : "chatroom-container" 
    });
    
    // Custom boostrap panel
    var $chatroom = $("<div></div>", {
        "class" :  "panel chatroom",
        "data-chatroom-id" : self.chatroomID
    });
    
    // Panel heading aka chat title
    var $panelHeading = $("<div></div>", {
        "class" : "panel-heading"
    }).append($("<span></span>", {
        "class" : "chatroom-name",
        "text" : self.chatroomName
    }));
    
    $panelHeading.appendTo($chatroom);
    
    // Panel Body aka chat messages
    var $panelBody = $("<div></div>", {
        "class" : "panel-body"
    });
    
    var $chatroomChat = $("<ul></ul>", {
        "class" : "chatroom-chat"
    });
    
    $chatroomChat.appendTo($panelBody);    
    $panelBody.appendTo($chatroom);
    
    // Panel footer aka chat input and button
    var $panelFooter = $("<div></div>", {
        "class" : "panel-footer"
    });
    
    var $inputGroup = $("<div></div>", {
        "class" : ""
    });
    
    var $chatInput = $("<textarea></textarea>", {
        "class" : "form-control input-sm chatroom-input-msg",
        "placeholder" : "Type your message..."
    }).appendTo($inputGroup);
    
    $inputGroup.appendTo($panelFooter);
    $panelFooter.appendTo($chatroom);
    
    $chatroomContainer.append($chatroom);    

    var $chatroomTitle = $("<div></div>", {
        "id" : "chatroom-title",
       "text" : self.chatroomName
    });
    
    toka.addContent($chatroomContainer);
    toka.setSubtitle($chatroomTitle);
    
    self.iniChatroom();
};
Chatroom.prototype.domChatroomItem = function() {
    var self = this;
    
    // 12 columns possible, 4 on desktop, 2 on tablet, 1 on phone
    var $responsiveContainer = $("<div></div>", {
        "class" :  "col-lg-3 col-sm-6 col-xs-12"
    });
    
    var $chatroomItem = $("<div></div>", {
        "data-chatroom-id" : self.chatroomID,
        "class" : "chatroom-item"
    });
    
    // Chatroom Item Top    
    var $chatroomItemTop = $("<a></a>", {
        "class" : "chatroom-item-top",
        "href" : "/chatroom/" + self.chatroomID
    });
    
    var $chatroomItemImage = $("<div></div>", {
        "class" : "chatroom-item-image"
    }).append($("<img />", {
        "src" : "/assets/images/icons/chat.svg",
        "class" : "img-responsive"
    })).appendTo($chatroomItemTop);        
    
    $chatroomItemTop.appendTo($chatroomItem);
    
    // Chatroom Item Bottom    
    var $chatroomItemBottom = $("<div></div>", {
        "class" : "chatroom-item-bottom"
    });
    
    var $chatroomItemName = $("<div></div>", {
        "class" : "chatroom-item-name",
        "html" : "<h4>" + self.chatroomName + "</h4>"
    }).appendTo($chatroomItemBottom);
    
    var $chatroomItemDetails = $("<div></div>", {
        "class" : "chatroom-item-details"
    });
    
    var $chatroomItemUsers = $("<div></div>", {
        "class" : "chatroom-item-users"
    }).append($("<img />", {
        "src" : "/assets/images/icons/user_g.svg",
        "class" : "img-responsive"
    })).append($("<span></span>", {
        "class" : "chatroom-item-users-count",
        "text" : "0"
    })).appendTo($chatroomItemDetails);
    
    var $chatroomItemFollow = $("<div></div>", {
        "class" : "chatroom-item-follow"
    }).append($("<a></a>", {
        "class" : "btn btn-primary",
        "role" : "button",
        "text" : "Follow"
    })).appendTo($chatroomItemDetails);
    
    $chatroomItemDetails.appendTo($chatroomItemBottom);
    
    var $chatroomItemHost = $("<div></div>", {
        "class" : "chatroom-item-host",
        "html" : "Hosted by <span class=\"user-profile-link\">" + self.owner + "</span>"
    }).appendTo($chatroomItemBottom);
    
    $chatroomItemBottom.appendTo($chatroomItem);
    
    $("#chatroom-list").append($responsiveContainer.append($chatroomItem));
    
    self.iniChatroomItem();
};
Chatroom.prototype.modUser = function(userToMod) {
    var self = this;
    
    var username = getCookie("username");

    if (username === "") {
        toka.alert("Cannot mod user! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    data["userToMod"] = userToMod;
    
    self.service("chatroom", "mod", "POST", data);
};
/*
 * @message: Message object
 */
Chatroom.prototype.receiveMessage = function(message) {
    var self = this;
    
    var $chat = $(self.selectChatroom + " .panel-body .chatroom-chat");
    var username = getCookie("username");
    
    // If groupMessageFlag is active,
    // Don't add the username if it is the same as the last person who sent a message
    /*if (self.groupMessagesFlag === "y") {
        if (self.lastSender !== message.username) {
            var $user = $("<div></div>", {
                "class" : "chatroom-user-name",
                "text" : message.username
            }).appendTo($msgContainer);
        }
    }*/
    
    // TokaBot parser
    toka.tokabot.receiveMessage(message);
    
    if (self.autoScroll) {
        // Move the chatroom message view to the bottom of the chat
        var $panelBody = $(self.selectChatroomMsgContainer);
        var scrollHeight = $panelBody.prop("scrollHeight");
        $panelBody.scrollTop(scrollHeight);
    }
    
    self.lastSender = message.username;
};
Chatroom.prototype.sendMessage = function() {
    var self = this;
    
    var username = getCookie("username");

    if (username === "") {
        toka.alert("Cannot send message! Please log in."); // Make this a better pop up
        return;
    }
    
    // Gets input text
    var text = $(self.selectChatroomInputMsg).val();
    var message = new Message(self.chatroomID, username, text, timestamp());
    
    // Prevents users from submitting empty text or just spaces
    if (text.trim() === "") return;
    
    // If msg is valid, clear it
    $(self.selectChatroomInputMsg).val("");
    
    var $chat = $(self.selectChatroom + " .panel-body .chatroom-chat");
    
    // TokaBot parser
    toka.tokabot.sendMessage(message);
    
    //$message.appendTo($chat);
    
    // Move the chatroom message view to the bottom of the chat
    var $panelBody = $(self.selectChatroomMsgContainer)
    var scrollHeight = $panelBody.prop("scrollHeight");
    $panelBody.scrollTop(scrollHeight);    
};
Chatroom.prototype.unmodUser = function(userToUnmod) {
    var self = this;
    
    var username = getCookie("username");

    if (username === "") {
        alert("Cannot unmod user! Please log in."); // Make this a better pop up
        return;
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    data["userToUnmod"] = userToUnmod;
    
    self.service("chatroom", "unmod", "POST", data);
};
Chatroom.prototype.updateChatroom = function() {
    var self = this;
        
    var username = getCookie("username");
    
    if (username === "") {
        alert("Cannot update chatroom! Please log in."); // Make this a better pop up
        return;
    }
    else if (self.chatroomName === "") {
        alert("Chatroom name is required."); // Make this a better pop up
        return;
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    data["chatroomName"] = self.chatroomName;
    data["chatroomType"] = self.chatroomType;
    data["guesting"] = self.guesting;
    data["maxSize"] = self.maxSize;

    self.service("chatroom", "update", "POST", data);
};
Chatroom.prototype.updateChatroomItemUsers = function(userCount) {
    var self = this;
    
    $(self.selectChatroomItemUserCount).text(userCount);
};


/* Message Object */

function Message(chatroomID, username, text, timestamp) {
    this.chatroomID = chatroomID;
    this.username = username;
    this.text = text;
    this.timestamp = timestamp;
}