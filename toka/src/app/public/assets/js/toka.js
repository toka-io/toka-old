 /* DO NOT REMOVE */
"use strict"


/* Global Variables */
var toka = {};

/* General Functions */

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
 
function timeStamp() {
// Create a date object with the current time
  var now = new Date();
 
// Create an array with the current month, day and time
  var date = [ now.getMonth() + 1, now.getDate(), now.getFullYear() ];
 
// Create an array with the current hour, minute and second
  var time = [ now.getHours(), now.getMinutes()];
 
// Determine AM or PM suffix based on the hour
  var suffix = ( time[0] < 12 ) ? "am" : "pm";
 
// Convert hour from military time
  time[0] = ( time[0] < 12 ) ? time[0] : time[0] - 12;
 
// If hour is 0, set it to 12
  time[0] = time[0] || 12;
 
// If seconds and minutes are less than 10, add a zero
  for ( var i = 1; i < 3; i++ ) {
    if ( time[i] < 10 ) {
      time[i] = "0" + time[i];
    }
  }
 
// Return the formatted string
  return date.join("/") + " " + time.join(":") + "" + suffix;
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
    
    // Retrieve all categories
    $("#category-all").off().on("click", function() {
        self.getAllCategories();
    });
    
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
    
    
    /* Test Event Bindings */
    
    // Create chatroom
    $("#chatroom-create").off().on("click", function() {
        self.createChatroom();
    });
    
    // Deactivate user
    $("#user-delete").off().on("click", function() {
        self.deactivateUser();
    });
};
Toka.prototype.iniChatroomList = function() {
    var self = this;
    
    var $chatroomList = $("#chatroom-list div .chatroom-item");
    
    $chatroomList.each(function() {
       var prop = $(this).data("chatroom");
       
       var chatroom = new Chatroom(prop);
       
       self.chatrooms[chatroom.chatroomID] = chatroom.iniChatroomItem();
    });
    
    try {
        self.socket = io.connect("http://toka.io:1337");    
        
        // Connection with chat server established
        self.socket.on("connect", function() {
            console.log('Connection opened.');
            toka.socket.emit("viewers");
        }); 
        
        // Retreive list of users for active chatrooms
        self.socket.on("viewers", function(viewers) {
            for (var chatroomID in viewers) {
                if (self.chatrooms.hasOwnProperty(chatroomID))
                    self.chatrooms[chatroomID].updateChatroomItemUsers(viewers[chatroomID].length);
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
Toka.prototype.iniChatroom = function() {
    var self = this;
    
    var $chatroom = $(".chatroom-container .chatroom");
    
    var prop = $chatroom.data("chatroom");
    
    var chatroom = new Chatroom(prop);
    chatroom.iniChatroom()
    self.currentChatroom = chatroom; 
    self.chatrooms[self.currentChatroom.chatroomID] = self.currentChatroom;
    
    try {
        self.socket = io.connect("http://toka.io:1337");    
        
        // Connection with chat server established
        self.socket.on("connect", function() {
            console.log('Connection opened.');
            self.socket.emit("join", {
                "chatroomID" : self.currentChatroom.chatroomID,
                "username" : getCookie("username")
            });
        }); 
        
        // Retrieve chat history for active chatrooms
        self.socket.on("history", function(history) {        
            // Find the chatroom the history belongs to and populate the chat window
            if (self.chatrooms.hasOwnProperty(history.chatroomID)) {
                for (var i=0; i < history.data.length; i++) {
                    var message = new Message(history.data[i].chatroomID, history.data[i].username, history.data[i].text, history.data[i].timestamp);
                    self.chatrooms[history.chatroomID].receiveMessage(message);                
                }
            }
        });
        
        // Retreives messages for active chatrooms
        self.socket.on("message", function(message) {        
            // Convert message to toka js object (as opposed to the Node JS obj...maybe we want to sync them?
            message = new Message(message.chatroomID, message.data.username, message.data.text, timeStamp());
            
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
        self.socket = io.connect("http://toka.io:1337");    
        
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
Toka.prototype.quickCheck = function(service, action, method, data) {
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
        self.categories = {};
        self.categoryList = [];
        
        if (categoryList.length > 0) {
            for (var i = 0; i < categoryList.length; i++) {
                var category = new Category(categoryList[i]);                
                self.categories[category.categoryID] = category;
                self.categoryList.push(category);
            }
            self.domCategoryList();
        }
        else {
            toka.alert("Categories are unavailable at the moment.");
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
Toka.prototype.createChatroom = function() {
    var self = this;
    
    var categoryName = "Entrepreneurs"; // default to first item
    var chatroomName = "Toka";
    var chatroomType = "public"; // default to public
    var guesting = true; // default to false
    var maxSize = 20; // put a default
    var username = getCookie("username");
    
    if (username === "") {
        toka.alert("Cannot create chatroom! Please log in."); // Make this a better pop up
        return;
    } else if (chatroomName === "") {
        toka.alert("Chatroom name is required."); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["categoryName"] = categoryName;
    data["chatroomName"] = chatroomName;
    data["chatroomType"] = chatroomType;
    data["guesting"] = guesting;
    data["maxSize"] = maxSize;
    
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
Toka.prototype.domCategoryList = function() {
    var self = this;
    
    toka.clearContent();
    
    var $categoryListContainer = $("<div></div>", {
        "id" : "category-list",
    });
    
    var $categoryListTitle = $("<div></div>", {
        "id" : "category-list-title",
       "text" : "Categories"
    });
    
    toka.addContent($categoryListContainer);
    toka.setSubtitle($categoryListTitle);
    
    for (var i = 0; i < self.categoryList.length; i++) {        
        self.categoryList[i].domCategoryItem();
    }
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
Toka.prototype.setSubtitle = function($subtitle) {
    $("#site-subtitle").append($subtitle)
};
Toka.prototype.setTitle = function(title) {
    var $title = $("title");
    $title.text(title);
};
Toka.prototype.signup = function() {
    var self = this;
    
    var email = "andytlim@gmail.com";
    var password = "toka";
    var passwordRepeat = "toka";
    var username = "andytlim";
        
    if (password !== passwordRepeat) {
        toka.alert("Passwords do not match!"); // Make this a better pop up
        return;
    }
    
    var data = {};
    data["email"] = email;
    data["password"] = password;
    data["username"] = username;
    
    self.service("signup", "", "POST", data);
};
Toka.prototype.validateLogin = function() {
    var self = this;
    
    var password = $("#toka-login-password").val();
    var username = $("#toka-login-username").val();
    
    // Make sure to trim!!
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
    
    var email = $("#toka-signup-email").val();
    var password = $("#toka-signup-password").val();
    var passwordRepeat = $("#toka-signup-password-again").val();
    var username = $("#toka-signup-username").val();
    
    // Make sure to trim!!
    if (email === "") {
        toka.alertSignup("Please provide an email address.");
        return false;
    } else if (email === "") {
        toka.alertSignup("Please provide a password.");
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
    this.categoryID = prop["category_id"];
    this.categoryName = prop["category_name"];
    this.categoryImageURL = prop["category_img_url"];
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
    
    if (service === "category" && action === "chatrooms") {
        var chatroomList = response["data"];
        toka.chatrooms = {};
        toka.chatroomList = [];
        
        if (chatroomList.length > 0) {
            for (var i = 0; i < chatroomList.length; i++) {
                var chatroom = new Chatroom(chatroomList[i]);
                toka.chatrooms[chatroom.chatroomID] = chatroom;
                toka.chatroomList.push(chatroom);
            }            
            toka.domChatroomList(response["categoryName"]);
        }
        else {            
            toka.alert(response["categoryName"] + " category is empty!");
        }        

    }
};
Category.prototype.getChatrooms = function() {
    var self = this;
    
    var data = {};
    data["categoryName"] = self.categoryName;
    
    self.service("category", "chatrooms", "GET", data);
};
Category.prototype.domCategoryItem = function() {
    var self = this;
    
    // 12 columns possible, 4 on desktop, 2 on tablet, 1 on phone
    var $responsiveContainer = $("<div></div>", {
        "class" :  "col-lg-3 col-sm-6 col-xs-12"
    });
    
    var $categoryItem = $("<a></a>", {
        "data-category-name" : escape(self.categoryName),
        "href": "/category/" + encodeURI(self.categoryName),
        "class" : "category-item thumbnail"
    });
    
    var $categoryImage = $("<div></div>", {
        "class" : "category-image"
    }).append($("<img />", {
        "src" : (self.categoryImageURL === "") ? "/assets/images/icons/image.svg" : self.categoryImageURL,
        "class" : "img-responsive"
    })).appendTo($categoryItem);
        
    var $categoryCaption = $("<div></div>", {
        "class" : "category-caption",
        "text" : self.categoryName
    }).appendTo($categoryItem);
    
    $("#category-list").append($responsiveContainer.append($categoryItem));
    self.ini();
};


/**
 * Chatroom
 * @desc: Stores chatroom attributes 
 */
function Chatroom(prop) {
    this.newMessages = 0; // This will be used later for multiple chats in one page
    this.lastSender = "";
    
    this.categoryName = prop["category_name"];
    this.chatroomID = prop["chatroom_id"];
    this.chatroomName = prop["chatroom_name"];
    this.chatroomType = prop["chatroom_type"];
    this.guesting = prop["guesting"];
    this.maxSize = prop["max_size"];
    this.mods = prop["mods"];
    this.owner = prop["owner"];
    this.users = prop["users"];
    
    // Extra attributes to add to database
    this.groupMessageFlag = "n";
    
    this.selectChatroomItem = ".chatroom-item[data-chatroom-id='"+this.chatroomID+"']";
    this.selectChatroomItemTopContainer = this.selectChatroomItem + " .chatroom-item-top";
    this.selectChatroomItemUserCount = this.selectChatroomItem + " .chatroom-item-bottom .chatroom-item-details .chatroom-item-users .chatroom-item-users-count";
        
    this.selectChatroom = ".chatroom[data-chatroom-id='" + this.chatroomID + "']";
    this.selectChatroomMsgContainer = this.selectChatroom + " .panel-body";
    this.selectChatroomInputMsg = this.selectChatroom + " .panel-footer div .chatroom-input-msg";
    this.selectChatroomInputBtn = this.selectChatroom + " .panel-footer .input-group .input-group-btn"
}
Chatroom.prototype.iniChatroom = function() {
    var self = this;   
    
    $(self.selectChatroomInputMsg).off("click").on("click", function() {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
    });
    
    $(self.selectChatroomInputMsg).off("keydown").on("keydown", function(e) {
        toka.newMessages = 0;
        toka.setTitle("Toka - " + self.chatroomName);
        // On [Enter] key        
        if (e.which === 13) {
            if (!e.shiftKey) {
                e.preventDefault();
                self.sendMessage();
            }
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
    
    $("#chatroom-enter").off().on("click", function() {
        self.enterChatroom();
    });
    
    $("#chatroom-leave").off().on("click", function() {
        self.leaveChatroom();
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
    
    /*
    var $chatButton = $("<span></span>", {
        "class" : "input-group-btn"
    }).append($("<button></button>", {
        "class" : "btn btn-warning btn-sm chatroom-input-btn",
        "text" : "Send"
    })).appendTo($inputGroup); */
    
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
        "text" : self.users.length
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
Chatroom.prototype.enterChatroom = function() {
    var self = this;
    
    var username = getCookie("username");
    
    if (username === "") {
        toka.alert("Sorry, guesting is not enabled yet. Please log in to enter the chatroom."); // Make this a better pop up
        return;
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    
    self.service("chatroom", "enter", "POST", data);
};
Chatroom.prototype.leaveChatroom = function() {
    var self = this;
  
    var username = getCookie("username");
    
    if (username === "") {
        toka.alert("Quit hacking!"); // Make this a better pop up
        return;
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    
    self.service("chatroom", "leave", "POST", data);
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
    
    var $msgContainer = $("<li></li>", {
       "class" : "chatroom-msg chatroom-user"
    });
    
    // If groupMessageFlag is active,
    // Don't add the username if it is the same as the last person who sent a message
    if (self.groupMessagesFlag === "y") {
        if (self.lastSender !== message.username) {
            var $user = $("<div></div>", {
                "class" : "chatroom-user-name",
                "text" : message.username
            }).appendTo($msgContainer);
        }
    }
    else {
        var $usernameContainer = $("<div></div>", {
            "class" : "chatroom-user-container"
        });
        
        var $username = $("<span></span>", {
            "class" : "chatroom-user-name",
            "text" : message.username
        }).appendTo($usernameContainer);
        
        var $timestamp = $("<span></span>", {
            "class" : "chatroom-user-timestamp",
            "text" : message.timestamp
        }).appendTo($usernameContainer);
        
        $usernameContainer.appendTo($msgContainer);
    }
    
    // TokaBot parser
    var $message = toka.tokabot.parseMessage(message.text);
    
    var $msg = $("<div></div>", {
        "class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg",
        "html" : $message.html()
    }).appendTo($msgContainer);
    
    $msgContainer.appendTo($chat);
    
    // Move the chatroom message view to the bottom of the chat
    var $panelBody = $(self.selectChatroomMsgContainer);
    var scrollHeight = $panelBody.prop("scrollHeight");
    $panelBody.scrollTop(scrollHeight);
    
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
    
    // Prevents users from submitting empty text or just spaces
    if (text.trim() === "") return;
    
    // If msg is valid, clear it
    $(self.selectChatroomInputMsg).val("");
    
    var $chat = $(self.selectChatroom + " .panel-body .chatroom-chat");
    
    var $msgContainer = $("<li></li>", {
       "class" : "chatroom-msg chatroom-user"
    });
    
    var $usernameContainer = $("<div></div>", {
        "class" : "chatroom-user-container"
    });
    
    var $username = $("<span></span>", {
        "class" : "chatroom-user-name",
        "text" : username
    }).appendTo($usernameContainer);
    
    var $timestamp = $("<span></span>", {
        "class" : "chatroom-user-timestamp",
        "text" : timeStamp()
    }).appendTo($usernameContainer);
    
    $usernameContainer.appendTo($msgContainer);
    
    // TokaBot parser
    var $message = toka.tokabot.parseMessage(text);
    
    var $msg = $("<div></div>", {
        "class" : "chatroom-user-msg",
        "html" : $message.html()
    }).appendTo($msgContainer);
    
    $msgContainer.appendTo($chat);
    
    // Move the chatroom message view to the bottom of the chat
    var $panelBody = $(self.selectChatroomMsgContainer)
    var scrollHeight = $panelBody.prop("scrollHeight");
    $panelBody.scrollTop(scrollHeight);
    
    // Can only send messages as utf-8
    var message = new Message(self.chatroomID, username, text);
    
    try {
        // self.connection.send(message);
        toka.socket.emit("message", message);
    }
    catch (err) {
        toka.errSocket(err);
    }
    
    var data = {};    
    data["chatroomID"] = self.chatroomID;
    
    //self.service("chatroom", "unmod", "POST", data);
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