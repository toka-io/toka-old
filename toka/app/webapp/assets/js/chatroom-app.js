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
                if (users.hasOwnProperty(toka.currentChatroom.chatroomId)) {
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
                $(".chatroom .input-msg").attr("placeholder", "Type your message...");
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