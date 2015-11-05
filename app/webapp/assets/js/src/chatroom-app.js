"use strict"

function ChatroomApp() {
    
    this.ini = function(chatroom) {
        var self = this;
        
        var chatroom = new Chatroom(chatroom);
        chatroom.iniChatroom();
        toka.currentChatroom = chatroom; 
        toka.chatrooms[chatroom.chatroomId] = chatroom;
        
        toka.tokabot.options['focused'] = true;
        
        $(window).focus(function() {
            toka.tokabot.options['focused'] = true; 
        }).blur(function() {
            toka.tokabot.options['focused'] = false;
        });
        
        $("#update-chatroom-btn").off("click").on("click", function() {
            var chatroom = toka.currentChatroom;
            chatroom.chatroomName = $("#update-chatroom-title").val().trim();
            chatroom.categoryName = $("#update-chatroom-category").val();
            chatroom.info = $("#update-chatroom-info").val();
            
            try {
                chatroom.tags = $("#update-chatroom-tags-input input").val().replace(/[\s,]+/g, ',').split(",");
                
                // flatten tags to lowercase
                for (var i = 0; i < chatroom.tags; i++) {
                    chatroom.tags[i] = chatroom.tags[i].toLowerCase(); 
                }
            } catch (err) {
                chatroom.tags = [];
            }
            
            if (self.validateUpdateChatroom(chatroom)) {
                chatroom.update();
            }
        });
        
        $("#update-chatroom-tags-input input").tagsinput({
            tagClass: "chatroom-tag label label-info"
        });
        
        $("#chatroom-title-update-chatroom div[data-toggle='tooltip']").tooltip({
            placement : 'bottom'
        });
       
        $(".upload-img-btn").on("click", function() {
            $("input[data-cloudinary-field='upload-img']:file").trigger('click'); 
        });
        
        $('.cloudinary-fileupload').bind('cloudinarydone', function(e, data) {
            var username = toka.getCookie("username");
            if (username === "") {
                toka.promptLogin();
                return;
            }
            var message = new Message(toka.currentChatroom.chatroomId, username, '/image ' + data.result.public_id+"."+data.result.format, timestamp());
            toka.tokabot.sendMessage(message);
            return true;
        });
        
//        $('.cloudinary-fileupload').bind('fileuploadprogress', function(e, data) {
//            $('.progress_bar').css('width', Math.round((data.loaded * 100.0) / data.total) + '%');
//        });
        
        try {
            toka.socket = io.connect(toka.chata, {secure: true});    
            
            // Connection with chat server established
            toka.socket.on("connect", function() {
                console.log('Connection opened.');
                $(".chatroom .input-msg").attr("placeholder", "Connected. Retrieving history...");
                toka.socket.emit("join", {
                    "chatroomId" : toka.currentChatroom.chatroomId,
                    "username" : toka.getCookie("username")
                });
                
                toka.socket.emit("users", toka.currentChatroom.chatroomId);
            });
            
            // Retreive list of users for active chatrooms
            toka.socket.on("activeViewerCount", function(activeViewerCount) {
                $(".chatroom-heading .users span").text(activeViewerCount[toka.currentChatroom.chatroomId]);
            });
            
            // Retreive list of users for active chatrooms
            toka.socket.on("users", function(users) {
                var chatroomId = toka.currentChatroom.chatroomId;
                if (users.hasOwnProperty(chatroomId)) {
                    for (var i = 0; i < users[chatroomId].length; i++) {
                        toka.tokabot.registerNewUserTheme(users[chatroomId][i], i);
                    }
                    
                    $(".chatroom .user-list ul").empty();
                    for (var i = 0; i < users[toka.currentChatroom.chatroomId].length; i++) {
                        $(".chatroom .user-list ul").append($("<li></li>", {
                            "text" : users[toka.currentChatroom.chatroomId][i]
                        }));
                    }
                }
            });
            
            // Retrieve chat history for active chatrooms
            toka.socket.on("history", function(history) {
                $(".chatroom .input-msg").attr("placeholder", "Type here to chat. Use / for commands.");
                // Find the chatroom the history belongs to and populate the chat window
                if (toka.chatrooms.hasOwnProperty(history.chatroomId)) {
                    $(toka.chatrooms[history.chatroomId].selectChatroomList).empty();
                    toka.chatrooms[history.chatroomId].loadHistory(history);
                }
            });
            
            // Retreives messages for active chatrooms
            toka.socket.on("receiveMessage", function(message) {            
                if (toka.chatrooms.hasOwnProperty(message.chatroomId)) {
                    toka.chatrooms[message.chatroomId].receiveMessage(message);
                }        
                
                // If user is active in the chat text box, then they won't an alert for that chatroom
                if (!$(toka.currentChatroom.selectChatroomInputMsg).is(":focus")) {
                    toka.newMessages++;
                    toka.setTitle("(1+) Toka");
                }
            });
            
            // Connect to chat server closed (Server could be offline or an error occurred or client really disconncted)
            toka.socket.on("disconnect", function() {
                console.log('Connection closed.');
                $(".chatroom .input-msg").attr("placeholder", "Disconnected. Reconnecting...");
            });
        }
        catch (err) {
            console.log('Could not connect to chata!');
        }
    }
    
    this.alertUpdateChatroom = function(alertMsg) {
        var $alert = $("<div></div>", {
            "id" : "update-chatroom-alert-text",
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
        
        $("#update-chatroom-alert").empty().append($alert);
    };
    
    this.validateUpdateChatroom = function(chatroom) {
        if (chatroom.chatroomName === "") {
            this.alertUpdateChatroom("Please provide a chatroom title.");
            return false;
        } if (chatroom.chatroomName.trim().length > 100) {
            this.alertUpdateChatroom("Please keep chatroom titles limited to 100 characters.");
            return false;
        } else if (chatroom.categoryName === "0") {
            this.alertUpdateChatroom("Please select a category.");
            return false;
        } else if (chatroom.tags.length > 5) {
            this.alertUpdateChatroom("Please limit tags to 5.");
            return false;
        }
        
        return true;
    }
}

/**
 * Chatroom
 * @desc: Stores chatroom attributes 
 */
function Chatroom(prop) {
    this.newMessages = 0; // This will be used later for multiple chats in one page
    this.lastSender = "";
    this.autoScroll = true;
    
    for (var key in prop) {
        this[key] = prop[key];
    }

    // Extra attributes to add to database
    this.groupMessageFlag = "n";
    this.commandsHelpActive = false;
    
    this.selectChatroomItem = ".chatroom-item[data-chatroom-id='"+this.chatroomId+"']";
    this.selectChatroomItemTopContainer = this.selectChatroomItem + " .chatroom-item-top";
    this.selectChatroomItemUserCount = this.selectChatroomItem + " .chatroom-item-bottom .chatroom-item-users-count";
        
    this.selectChatroom = ".chatroom[data-chatroom-id='" + this.chatroomId + "']";
    this.selectChatroomList = this.selectChatroom + " .messages";
    this.selectChatroomBody = this.selectChatroom + " .messages-container";
    this.selectChatroomChatBox = this.selectChatroom + " .chatbox";
    this.selectChatroomInfoBox = this.selectChatroom + " .infobox";
    this.selectChatroomInputMsg = this.selectChatroom + " .inputbox .input-msg";
    this.selectChatroomTitleMenuUser = this.selectChatroom + " .title-menu .users";
    this.selectChatroomUserList = this.selectChatroom + " .user-list";
    
    this.commandHelp = new CommandHelp($(this.selectChatroomChatBox), $(this.selectChatroomInputMsg));
    //this.autocomplete = new Autocomplete($(this.selectChatroom), $(this.selectChatroomInputMsg));
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
    
    $(self.selectChatroomInputMsg).on('keydown', function(e) {
        if (e.which == 9 || (e.which == 13 && !e.shiftKey)) 
            e.preventDefault();
    })
    
    self.commandHelp.ini();
    //self.autocomplete.ini();    
    
    $(self.selectChatroomInputMsg).on('keyup', function(e) {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
        
        if (self.commandHelp.sendReady() && e.which === 13) {
            if (!e.shiftKey) {                
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
};
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
                chatroomApp.alertUpdateChatroom("Server Error: " + statusMessage);
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