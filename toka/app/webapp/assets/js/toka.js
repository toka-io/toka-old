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
        
        self.adjustSiteContentHeight();
        $(window).off("resize").on("resize", function() {
            self.adjustSiteContentHeight();
        });

        $("#search-page").off().on("click", function() {
            self.alert("Search is not available yet.");
        });
        
        leftNavApp.ini();
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
                $('#profile-img').attr('src', '/assets/images/icons/add.svg');
            } else {
                $('#profile-menu').slideDown(500);
                $('#profile-menu').removeClass('closed').addClass('open');
                $('#profile-img').attr('src', '/assets/images/icons/minus.svg');
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
    this.commandsHelpActive = false;
    
    this.selectChatroomItem = ".chatroom-item[data-chatroom-id='"+this.chatroomId+"']";
    this.selectChatroomItemTopContainer = this.selectChatroomItem + " .chatroom-item-top";
    this.selectChatroomItemUserCount = this.selectChatroomItem + " .chatroom-item-bottom .chatroom-item-details .chatroom-item-users .chatroom-item-users-count";
        
    this.selectChatroom = ".chatroom[data-chatroom-id='" + this.chatroomId + "']";
    this.selectChatroomList = this.selectChatroom + " .messages";
    this.selectChatroomBody = this.selectChatroom + " .messages-container";
    this.selectChatroomChatBox = this.selectChatroom + " .chatbox";
    this.selectChatroomInfoBox = this.selectChatroom + " .infobox";
    this.selectChatroomInputMsg = this.selectChatroom + " .inputbox .input-msg";
    this.selectChatroomTitleMenuUser = this.selectChatroom + " .title-menu .users";
    this.selectChatroomUserList = this.selectChatroom + " .user-list";
    
    this.commandHelpApp = new CommandHelpApp($(this.selectChatroomChatBox), $(this.selectChatroomInputMsg));
    this.autocompleteApp = new AutocompleteApp($(this.selectChatroom), $(this.selectChatroomInputMsg));
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
    
    $(self.selectChatroomInputMsg).off('keydown').on('keydown', function(e) {
        toka.newMessages = 0;
        toka.setTitle(self.chatroomName + " - Toka");
        
        if (!self.commandHelpApp.active && e.which === 13) {
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
    
    // Add any additional keyboard bindings here
    self.commandHelpApp.ini();
    //self.autocompleteApp.ini();
    
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

function AutocompleteApp(container, input) {
    this.container = container;
    this.input = input;
    this.app;
    this.active = false;
    this.startIndex = -1;
}
AutocompleteApp.prototype.ini = function() {
    var self = this;
    
    self.input.on('keyup', function(e) {
        var inputText = self.input.val();
        var recentChar = inputText.substr(inputText.length-1, inputText.length);   
        console.log(recentChar);
        
        if (recentChar === "@") {
            self.active = true;
        }
        else if (self.active) {
            if (self.startIndex == -1)
                self.startIndex = inputText.length-1;
            
            var username = inputText.substr(self.startIndex);
            console.log('username: ' + username);
            self.getUsernameMatches(username);
        }
    });
}
AutocompleteApp.prototype.getUsernameMatches = function(username) {
    var self = this;
    
    self.app = $("<div></div>", {
        'class': 'autocomplete-username',
        'style': 'display: none;'
    }).append($("<div></div>", {
        'class': 'title',
        'html': '<span>usernames matching:</span><span class="text"></span>'
    })).append($("<ul></ul>", {
        'class': 'ac-username-list'
    }))
    
    self.container.append(self.app);
    
    var loadingOptions = {};
    
    $.ajax({
        url: "/rs/user/search?u="+username,
        type: "GET",
        dataType: "json",
        beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
        complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
        success: function(response) {
            if (response["status"] !== 200) {
                console.log("Error!!");
            }
            else {
                self.app.show();
            }
        }
    });
}

function CommandHelpApp(container, input) {
    this.container = container;
    this.input = input;
    this.app;
    this.active;
    this.commands = {
            '/define': { 
                options: "[word]",
                desc: "Define a word"
            },
            '/me': {
                options: "[text]",
                desc: "Perform an action"
            },
            '/spoiler': {
                options: "[text]",
                desc: "Hide text in a spoiler block"
            },
            '/urban': {
                options: "[word]",
                desc: "Find the urban dictionary definition of a word"
            },
            '/view': {
                options: "[room]",
                desc: "Peek into a room by providing a chatroom id"
            }
    };
};
CommandHelpApp.prototype.ini = function() {
    var self = this;
    
    self.app = $("<div></div>", {
        'class': 'commands-help',
        'style': 'display: none;'
    }).append($("<div></div>", {
        'class': 'commands-top',
        'html': '<span>Commands</span>'
    })).append($("<ul></ul>", {
        'class': 'commands-list'
    }))
    
    self.container.append(self.app);
    
    for (var key in self.commands) {
        if (self.commands.hasOwnProperty(key)) {
            self.app.find('.commands-list').append($("<li></li>", {
                'html': '<span class="command">' + key + ' </span><span class="command-options">' + self.commands[key].options + '</span><span class="command-desc">' + self.commands[key].desc +  '</span>'
            }))
        }
    }
    self.app.find(".commands-list li:first").addClass("selected");

    self.app.find("li").on('click', function() {
        self.loadCommand($(this));
    });
    
    self.input.on('keydown', function(e) {
        var command = self.input.val();   
        
        if (e.which == 191) {
            self.app.show();
            self.active = true;
            $(self.container).find(".input-msg").addClass("commandActive");
            $(self.app).css("bottom", $(self.input).outerHeight());
        }
        
        if (self.active && e.which == 38) {
            var $selected  = self.app.find("li.selected");
            
            if ($selected.prev().length) {
                $selected.removeClass("selected");
                $selected.prev().addClass("selected");
            }
        }
        else if (self.active && e.which == 40) {
            var $selected  = self.app.find("li.selected");
            
            if ($selected.next().length) {
                $selected.removeClass("selected");
                $selected.next().addClass("selected");
            }
        }
        else if (self.active && e.which == 13) {
            e.preventDefault();
            var $selected  = self.app.find("li.selected");
            self.loadCommand($selected);
        }
    });
    
    self.input.on('keyup', function(e) {
        var command = self.input.val(); 
        
        // Do not evaluate on arrow keys
        if (e.which == 40 || e.which == 38)
            return;
            
        if (command == "")
            self.hide();
        
        if (self.active) {
            self.app.find("li").filter(function () {
                $(this).show();
                $(this).addClass("visible");
                $(this).removeClass("selected");
                
                var commandListItem = $(this).text();
                if (command != commandListItem.substr(0, command.length)) {
                    $(this).hide();
                    $(this).removeClass("visible");
                }
                
            });
            self.app.find("li.visible:first").addClass("selected");
        }
    });
}
CommandHelpApp.prototype.hide = function() {
    var self = this;
    $(self.app).hide();
    self.active = false;
    $(self.container).find(".input-msg").removeClass("commandActive");
}
CommandHelpApp.prototype.loadCommand = function($command) {
    var self = this;
    var command = $command.find(".command").text();        
    self.input.val(command);
    self.hide();;
}


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