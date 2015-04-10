/* TokaBot 2.3
 * @desc: Toka's #1 Bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot() {
    
    // Set Regular Expressions
    this.emoteReS = /^[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*\&\^\%\$\#\@\_]+/i;
    this.emoteReE = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*\&\^\%\$\#\@\_]+$/i;
    this.nameReS = /^@[\w]+/i;
    
    // Theme
    try {
        this.mainTheme = toka.user.chatTheme;
    } catch(err) {
        this.mainTheme = 'normal'; //normal, im
    }
    
    // Emote Set
    try {
        this.emoteSet = user.emoteSet;
    } catch(err) {
        this.emoteSet = 'standard/cat';
    }
    
    // Emote list, {'NAME': 'FILE'}
    this.emotesList = {
        'o/': 'toka.png',
        'O/': 'toka.png',
        '<3': 'heart.png',
        '-_-' : this.emoteSet+'/CAT-_-.png',
        '>:(' : this.emoteSet+'/CAT-angry.png',
        ':3' : this.emoteSet+'/CAT-cat.png',
        'T_T' : this.emoteSet+'/CAT-cry.png',
        '>:)' : this.emoteSet+'/CAT-evilsmile.png',
        'catpa' : this.emoteSet+'/CAT-kappa.png',
        'catGasm' : this.emoteSet+'/CAT-o.png',
        ':P' : this.emoteSet+'/CAT-tongue.png',
        ':/' : this.emoteSet+'/CAT-slash.png',
        ':\\' : this.emoteSet+'/CAT-slash.png',
        ':)' : this.emoteSet+'/CAT-smile.png',
        ':D' : this.emoteSet+'/CAT-Dsmile.png',
        '8)' : this.emoteSet+'/CAT-cool.png',
        ':(' : this.emoteSet+'/CAT-frown2.png',
        ';)' : this.emoteSet+'/CAT-wink.png'
    }
}

// Add Email Check at some point
TokaBot.prototype.checkLink = function(word, line, options) {
    
    // Reset variables
    var self = this;
    var run = false;
    var linkRe = /^[\h\t\s\:\][a-z0-9\/\.\-]+\.[a-z0-9\/\ \?\=\#\_\+\-\&\:\$\%\,]+[\ \.\][a-z0-9\/\ \?\=\#\_\+\-\&\:\$\%\,]+$/i; 
    var link = [];
    var $line;
    
    // Link check
    if (word != '') {
        try {
            var domain = word.split('.')[1];
            try {
                domain = domain.split('/')[0];
            } catch(err) {
            }
            while ((link = linkRe.exec(word)) != null) {
                if (link.index === linkRe.lastIndex) {
                    linkRe.lastIndex++;
                    if (link[0] == word) {
                        if (domain.length >= 2) {
                            var pass = false;
                            if (word.search('http://') == 0) {
                                pass = true;
                                var wordLink = word;
                            }
                            if (word.search('https://') == 0) {
                                pass = true;
                                var wordLink = word;
                            }
                            if (pass == false) {
                                var wordLink = 'http://'+word;
                            }
                            
                            run = true;
                            $line = $('<span></span>').text(line);
                            $line.append($('<a></a>', {'href': wordLink, 'target': '_blank'}).text(word+' '));
                            break;
                        } else {
                            break;
                        }
                    }
                }
            }
        } catch(err) {
        }
        
        // Either return the formatted link or check for emotes
        if (run == false) {
            return self.checkEmote(word, line, options);
        } else {
            return ['', $line];
        }
    } else {
    }
}

TokaBot.prototype.checkEmote = function(word, line, options) {
    
    // Reset variables
    var self = this;
    var run = false;
    var x = 0;
    var $line;
    
    // Emote check
    try {
        var wordClear = word;
        while (x <= 1) {
            if (self.emotesList.hasOwnProperty(wordClear)) {
                run = true;
                $line = $('<span></span>').text(line);
                if (wordClear == word) {
                    $line.append($('<img>', {'title': word, 'alt': word, 'src': "https://toka.io/assets/images/emotes/"+self.emotesList[word], 'class': "emote"}));
                    break;
                } else {
                    var wordStart = word.replace(self.emoteReE,'').replace(wordClear, '');
                    var wordEnd = word.replace(self.emoteReS, '').replace(wordClear, '');
                    if (wordStart != '') {
                        $line.append($('<span></span>').text(wordStart));
                    }
                    $line.append($('<img>', {'title': wordClear, 'alt': wordClear, 'src': "https://toka.io/assets/images/emotes/"+self.emotesList[wordClear], 'class': "emote"}));
                    if (wordEnd != '') {
                        $line.append($('<span></span>').text(wordEnd+' '));
                    }
                    break;
                }
            } else {
                x++;
                wordClear = word.replace(self.emoteReS,'').replace(self.emoteReE, '');
            }
        }
    } catch(err) {
    }
    
    // Either return formmated emote or check for highlighting
    if (run == false) {
        return self.checkHighlight(word, line, options);
    } else {
        $line.append($('<span></span>').text(' '));
        return ['', $line];
    }
}

TokaBot.prototype.checkHighlight = function(word, line, options) {
    
    // Reset variables
    var self = this;
    var run = false;
    var $line;
    
    // Highlight check
    try {
        if (word.search("@") == 0) {
            var wordEnd = word.replace(self.nameReS, '');
            if (wordEnd == '@') {
                var wordClear = word.substring(0, word.length-1);
            } else {
                var wordClear = word.replace(wordEnd, '');
            }
            
            if (wordClear.length >= 4) {
                if (wordClear.length <= 17) {
                    run = true;
                    $line = $('<span></span>').text(line);
                    if (wordClear.toLowerCase() == '@'+getCookie('username').toLowerCase()) {
                        if (options != 'history') {
                            $line.append($('<audio></audio>', {'autoplay': 'autoplay', 'style': 'display:none;', 'controls': 'controls'}).append($('<source></source>', {'src': 'http://www.bobco.moe/toka/asu_no_yoichi_sms.mp3'})));
                        }
                        $line.append($('<span></span>', {'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(wordClear));
                        $line.append($('<span></span>').text(wordEnd+' '));
                    } else {
                        if (wordClear == '') {
                            $line.append($('<span></span>').text(wordEnd+' '));
                        } else {
                            $line.append($('<span></span>', {'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(wordClear));
                            $line.append($('<span></span>').text(wordEnd+' '));
                        }
                    }
                }
            }
        }
    } catch(err) {
        run = false;
    }
    
    // Either return formatted highlight or formatted Text
    if (run == false) {
        // Normal Text
        return ['text', word];
    } else {
        return ['', $line];
    }
}

//////////////
/* Commands */
//////////////
TokaBot.prototype.doMute = function(message) {
    // Mute Function
    var free = true;
    
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return "You cannot mute yourself";
        } else {
            if (subject == 'undefined') {
                if (toka.user.muteList[message.chatroomID].indexOf(subject) != -1) {
                    // Append a name to the database
                    try {
                        toka.user.muteList[message.chatroomID].pop(toka.user.muteList[message.chatroomID].indexOf(subject));
                        toka.user.muteList[message.chatroomID].push(subject);
                        toka.unmuteUser(subject);
                        toka.muteUser(subject);
                        return "You muted "+subject;
                    } catch(err) {
                        return "There was an issue muting that person, try again later";
                    }
                } else {
                    // Append a name to the database
                    //toka.user.muteList[message.chatroomID].push(subject);
                    //toka.muteUser(subject);
                    return "You muted "+subject;
                }
            } else {
                return "Use '/mute username' to mute someone, and '/unmute username' to unmute them";
            }
        }
    } catch(err) {
        return "Use '/mute username' to mute someone, and '/unmute username' to unmute them";
    }
}

TokaBot.prototype.doUnMute = function(message) {
    // Unmute Function
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return '';
        } else {
            if (subject == 'undefined') {
                toka.user.muteList[message.chatroomID].forEach(function(name) {
                    if (name === subject) {
                        // remove a name from the database
                        try {
                            toka.user.muteList[message.chatroomID].pop(toka.user.muteList[message.chatroomID].indexOf(name));
                            toka.unmuteUser(subject);
                            return name+" has been unmuted";
                        } catch(err) {
                            return "There was an issue unmuting that person, try again later";
                        }
                    }
                });
            } else {
                return "Use '/unmute username' to unmute someone";
            }
        }
    } catch(err) {
        return "Use '/unmute username' to unmute someone";
    }
}

// toka.currentChatroom.unmodUser("username")

TokaBot.prototype.doBan = function(message) {
    // Ban Function
    
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return '';
        } else {
            if (subject == 'undefined') {
                try {
                    if (toka.currentChatroom.banned.indexOf(subject) != -1) {
                        toka.currentChatroom.banned.pop(toka.currentChatroom.banned.indexOf(subject));
                        toka.currentChatroom.banned.push(subject);
                        toka.currentChatroom.unbanUser(subject);
                        toka.currentChatroom.banUser(subject);
                        return 'Banned '+subject;
                    } else {
                        toka.currentChatroom.banned.push(subject);
                        toka.currentChatroom.banUser(subject);
                        return 'Banned '+subject;
                    }
                } catch(err) {
                    return "There was an issue banning that person, try again later"
                }
            } else {
                return "Use '/ban username' to ban someone, and '/unban username' to unban them";
            }
        }
    } catch(err) {
        return "Use '/ban username' to ban someone, and '/unban username' to unban them";
    }
}

TokaBot.prototype.doUnBan = function(message) {
    // Unban Function
    
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return '';
        } else {
            if (subject == 'undefined') {
                if (toka.currentChatroom.banned.indexOf(subject) != -1) {
                    try {
                        toka.currentChatroom.banned.pop(toka.currentChatroom.banned.indexOf(subject));
                        toka.currentChatroom.unbanUser(subject);
                        return subject+" has been unbanned";
                    } catch(err) {
                        return "There was an issue unbanning that person, try again later"
                    }
                }
            } else {
                return "Use '/unban username' to unban someone";
            }
        }
    } catch(err) {
        return "Use '/unban username' to unban someone";
    }
}

TokaBot.prototype.doMod = function(message) {
    // Mod Funciton
    
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return '';
        } else {
            if (subject == 'undefined') {
                try {
                    if (toka.currentChatroom.mods.indexOf(subject) != -1) {
                        toka.currentChatroom.mods.pop(toka.currentChatroom.banned.indexOf(subject));
                        toka.currentChatroom.mods.push(subject);
                        toka.currentChatroom.unmodUser(subject);
                        toka.currentChatroom.modUser(subject);
                        return 'Modded '+subject;
                    } else {
                        toka.currentChatroom.mod.push(subject);
                        toka.currentChatroom.modUser(subject);
                        return 'Modded '+subject;
                    }
                } catch(err) {
                    return "There was an issue modding that person, try again later"
                }
            } else {
                return "Use '/mod username' to mod someone, and '/unmod username' to unmod them";
            }
        }
    } catch(err) {
        return "Use '/mod username' to mod someone, and '/unmod username' to unmod them";
    }
}

TokaBot.prototype.doUnMod = function(message) {
    // Unmod Function
    
    try {
        var subject = message.text.split(' ')[1];
        if (subject == message.username) {
            return '';
        } else {
            if (subject == 'undefined') {
                if (toka.currentChatroom.mods.indexOf(subject) != -1) {
                    try {
                        toka.currentChatroom.mods.pop(toka.currentChatroom.banned.indexOf(subject));
                        toka.currentChatroom.unmodUser(subject);
                        return subject+" has been unmodded";
                    } catch(err) {
                        return "There was an issue unmodding that person, try again later"
                    }
                }
            } else {
                return "Use '/unmod username' to unmod someone";
            }
        }
    } catch(err) {
        return "Use '/unmod username' to unmod someone";
    }
}

TokaBot.prototype.doGetMods = function() {
    // Retrive the Mod list and return it
    
    try {
        var mods = 'The mods for this room are: '+toka.currentChatroom.owner+', ';
        toka.currentChatroom.mods.forEach(function(name) {
            mods += name+', ';
        });
        if (mods == 'The mods for this room are: ') {
            return 'This room has no mods';
        } else {
            if (mods == 'The mods for this room are: , ') {
                return 'This room has no mods';
            } else {
                return mods.substr(0, (mods.length-2));
            }
        }
    } catch(err) {
        return "Error retrieving mod list";
    }
}

TokaBot.prototype.getTheme = function(subTheme, message, $message, options) {
    var self = this;
    var $msgContainer = '';
    
    // Standardized theme names
    var mainTheme = self.mainTheme.toLowerCase();
    subTheme = subTheme.toLowerCase();
    
    // Default Chat
    if(mainTheme == 'normal') {
        $msgContainer = self.themeDefault(subTheme, message, $message, options);
        // Default chat grouped
    } else if (mainTheme == 'normal-group') {
        $msgContainer = self.themeDefaultGroup(subTheme, message, $message, options);
        // IM-styled grouped chat
    } else if (mainTheme == 'im-group') {
        $msgContainer = self.themeIMGroup(subTheme, message, $message, options);
        // IM-styled chat
    } else if (mainTheme == 'im') {
        $msgContainer = self.themeIM(subTheme, message, $message, options);
        // Default chat
    } else {
        $msgContainer = self.themeDefault(subTheme, message, $message, options);
    }
    return $msgContainer;
}

TokaBot.prototype.parseMessage = function(message, type, options) {
    
    // Reset variables
    var self = this;
    var first = false;
    var name = false;
    var kill = false;
    var command = false;
    var theme = self.mainTheme;
    
    // Make new lines visible to the parser
    message.text = message.text.replace(/\n/g, ' <br> ');
    // Set up basic variables for later
    var $message = ($('<div></div>', {"class": "chatroom-user-msg"})).append($('<span></span>'))
    
    // Check if it is a command (possibly shorten this?)
    if (message.text.substr(0,1) == '/') {
        // "Me" Command
        if (message.text.split(' ')[0].toLowerCase() === "/me") {
            theme = "me";
            name = true;
        }
        // "Spoiler" Command
        if (message.text.split(' ')[0].toLowerCase() === "/spoiler") {
            theme = "spoiler";
            first = true;
        }
        // These will only run if they are on YOUR end, includes all mod commands
        if (type == 'send') {
            if (message.username == getCookie('username')) {
                // "Mods" Command
                if (message.text.split(' ')[0].toLowerCase() === "/mods") {
                    theme = "tokabot";
                    message.text = self.doGetMods();
                    message.username = 'TokaBot'
                }
                // "Mute" Command
                if (message.text.split(' ')[0].toLowerCase() === "/mute") {
                    theme = "tokabot";
                    command = true;
                    message.text = self.doMute(message);
                    message.username = 'TokaBot'
                }
                // "Unmute" Command
                if (message.text.split(' ')[0].toLowerCase() === "/unmute") {
                    theme = "tokabot";
                    command = true;
                    message.text = self.doUnMute(message);
                    message.username = 'TokaBot'
                }
                // Mod Commands
                if (toka.currentChatroom.mods.indexOf(message.username) != -1) {
                    // "Ban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/ban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doBan(message);
                        message.username = 'TokaBot'
                    }
                    // "Unban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnBan(message);
                        message.username = 'TokaBot'
                    }
                }
                // Owner Commands
                if (toka.currentChatroom.owner == message.username) {
                    // "Ban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/ban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doBan(message);
                        message.username = 'TokaBot'
                    }
                    // "Unban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnBan(message);
                        message.username = 'TokaBot'
                    }
                    // "Mod" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/mod") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doMod(message);
                        message.username = 'TokaBot'
                    }
                    // "Unmod" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unmod") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnMod(message);
                        message.username = 'TokaBot'
                    }
                }
            }
        }
    }
    
    try {
        // If the name is muted, don't show the message
        self.mute.forEach(function(name) {
            if (name.toLowerCase() == message.username.toLowerCase()) {
                // Needed here, doesn't like to stop the function if return is here :/
                kill = true;
            }
        });
        // If the name is banned, don't show the message
        self.ban.forEach(function(name) {
            if (name.toLowerCase() == message.username.toLowerCase()) {
                // Needed here, doesn't like to stop the function if return is here :/
                kill = true;
            }
        });
    } catch(err) {
    }
    
    try {
        var $message = $('<div></div>');
        if (kill) {
            return;
        } else {
            if (message.text != '') {
                var line = '';
                // Read each word in chat seperatly and put it in $msgContainer
                message.text.split(' ').forEach(function(word) {
                    if (word != '') {
                        
                        // Remove first word + add name
                        if (name) {
                            name = false;
                            word = message.username;
                        }
                        
                        // Remove first word
                        if (first) {
                            first = false;
                            word = '';
                        }
                        
                        if (word != '') {
                            // If it is a break, make it a real one
                            if (word.toLowerCase() == '<br>') {
                                $message.append($('<span></span>').text(line));
                                line = '';
                                $message.append($('<br />'));
                            } else {
                                // Calculate for links, emotes, and highlights, then if everything fails print as normal text
                                var check = self.checkLink(word, line, options);
                                if (check[0] == 'text') {
                                    line = line+check[1]+' ';
                                } else {
                                    $message.append(check[1]);
                                    line = '';
                                }
                            }
                        }
                    } else {
                        line = line+' ';
                    }
                });
                $message.append($('<span></span>').text(line));
                line = '';
            } else {
                return;
            }
        }
    } catch(err) {
        $message.append($('<span></span>'));
    }
    
    // Chatroom Type
    try {
        if(toka.currentChatroom.chatroomID == 'dualchatroom') {
            try {
                if (toka.currentChatroom.mods.indexOf(message.username) != -1) {
                    var $chat = $(".chatroom-chat-member");
                } else if (toka.currentChatroom.owner == message.username) {
                    var $chat = $(".chatroom-chat-member");
                //} else if (toka.currentChatroom.members.indexOf(message.username) != -1) {
                //    var $chat = $(".chatroom-chat-member");
                } else {
                    var $chat = $(".chatroom-chat-visitor");
                }
            } catch(err) {
                var $chat = $(".chatroom-chat-visitor");
            }
        } else {
            var $chat = $(toka.currentChatroom.selectChatroomList);
        }
    } catch(err) {
        var $chat = $(toka.currentChatroom.selectChatroomList);
    }
    
    self.getTheme(theme, message, $message, options).appendTo($chat);
    
    if (type == 'send') {
        if (theme != 'tokabot') {
            if (message.text != '') {
                try {
                    toka.socket.emit("sendMessage", message);
                }
                catch (err) {
                    toka.errSocket(err);
                }
            }
        }
    }
}


// Command to recive both history and normal chats
TokaBot.prototype.receiveMessage = function(message, options) {
    var self = this;
    if (options) {
        self.parseMessage(message, 'receive', options);
    } else {
        self.parseMessage(message, 'receive', 'history');
    }
}

// Command to send a message from this client
TokaBot.prototype.sendMessage = function(message) {
    var self = this;
    self.parseMessage(message, 'send', 'history');
}

// The Normal Theme
TokaBot.prototype.themeDefault =function(subTheme, message, $message, options) {
    var self = this;
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    
    var $usernameContainer  = $("<div></div>", {"class" : "chatroom-user-container"});
    var $username;
    var $timestamp;
    var $msg;
    
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    //if (chat == 'normal') {
        if (subTheme === 'spoiler') {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg" : "tokabot-normal-other-msg"});
            var $spoiler = $("<button></button>", {"style" : "white-space: pre-wrap; text-align: inherit; font-size: inherit", "class" : "spoiler-msg", "type" : "button", "text" : "Spoiler"}).data("show", false);
            $spoiler.on("click", function() {
                if (!$(this).data("show")) {
                    $(this).html($message.html());
                    $(this).data("show", true);
                }
                else if ($(this).data("show")) {
                    $(this).text("Spoiler");
                    $(this).data("show", false);
                }
            });
            $msg.append($spoiler);
            $msg.appendTo($msgContainer);
        } else if (subTheme === 'tokabot') {
            $username = $("<span></span>", {"class" : "chatroom-user-name tokabot-tokabot-msg", "text" : 'TokaBot'}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp tokabot-tokabot-msg tokabot-spoiler", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg tokabot-tokabot-msg" : "tokabot-normal-other-msg tokabot-tokabot-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            $username = $("<span></span>").appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg tokabot-me-msg" : "tokabot-normal-other-msg tokabot-me-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg" : "tokabot-normal-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    //} else {
    return $msgContainer;
}

// The Normal Theme -Grouped-
TokaBot.prototype.themeDefaultGroup =function(subTheme, message, $message, options) {
    var self = this;
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    
    var $usernameContainer  = $("<div></div>", {"class" : "chatroom-user-container"});
    var $username;
    var $timestamp;
    var $msg;
    
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    if (subTheme === 'spoiler') {
        // Name
        $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
        // Time
        $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
        // Append Top bit onto the message to send
        $usernameContainer.appendTo($msgContainer);
        // Attach a Spoiler Button into message
        $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "this.html("+$("<div></div>", {"class" : "chatroom-user-msg", "html" : $()})}).append($message);
        // Append message into Chat
        $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-group-msg tokabot-spoiler-msg" : "tokabot-normal-other-group-msg tokabot-spoiler", "html" : $message.html()}).appendTo($msgContainer);
    } else if (subTheme === 'tokabot') {
        $username = $("<span></span>", {"class" : "chatroom-user-name tokabot-tokabot-msg", "text" : message.username}).appendTo($usernameContainer);
        $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp tokabot-tokabot-msg", "text" : message.timestamp}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-group-msg tokabot-tokabot-msg" : "tokabot-normal-other-group-msg tokabot-tokabot-msg", "html" : $message.html()}).appendTo($msgContainer);
    } else if (subTheme === 'me') {
        $username = $("<span></span>").appendTo($usernameContainer);
        $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-group-msg tokabot-me-msg" : "tokabot-normal-other-group-msg tokabot-me-msg", "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
    } else {
        $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
        $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-group-msg" : "tokabot-normal-other-group-msg", "html" : $message.html()}).appendTo($msgContainer);
    }
    return $msgContainer;
}

// The IM Styled Theme
TokaBot.prototype.themeIM =function(subTheme, message, $message, options) {
    var self = this;
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    var $usernameContainer  = $("<div></div>", {"class" : "chatroom-user-container"});
    var $username;
    var $timestamp;
    var $msg;
    
    if (message.username == username) {
        if (subTheme === 'spoiler') {
            var $username = $("<span></span>", {"class" : "tokabot-im-name", "text" : message.username}).appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : " toka-im-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "", "html" : $message.html()}).append($message);
            var $msg = $("<div></div>", {"class" : "tokabot-im-msg tokabot-spoiler-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            var $username = $("<span></span>").appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "toka-im-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $msg = $("<div></div>", {"class" : "tokabot-im-msg tokabot-me-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            var $username = $("<span></span>", {"class" : "tokabot-im-name", "text" : message.username}).appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "toka-im-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $msg = $("<div></div>", {"class" : "tokabot-im-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    } else {
        if (subTheme === 'spoiler') {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": ""}).append($message);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg" : "tokabot-normal-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'tokabot') {
            $username = $("<span></span>", {"class" : "chatroom-user-name tokabot-tokabot-msg", "text" : 'TokaBot'}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp tokabot-tokabot-msg tokabot-spoiler", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg tokabot-tokabot-msg" : "tokabot-normal-other-msg tokabot-tokabot-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            $username = $("<span></span>").appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg tokabot-me-msg" : "tokabot-normal-other-msg tokabot-me-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : (message.username === username) ? "tokabot-normal-msg" : "tokabot-normal-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    }
    return $msgContainer;
}

// The IM Styled Theme -Grouped-
TokaBot.prototype.themeIMGroup =function(subTheme, message, $message, options) {
    var self = this;
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    var $usernameContainer  = $("<div></div>", {"class" : "chatroom-user-container"});
    var $username;
    var $timestamp;
    var $msg;
    
    if (message.username == username) {
        if (subTheme === 'spoiler') {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-default", "onclick": "this.html("+$("<div></div>", {"class" : "chatroom-user-msg", "html" : $message.html()})}).append($message);
            $msg = $("<div></div>", {"class" : "chatroom-user-msg", "style": "display: none", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            $username = $("<span></span>").appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : "chatroom-user-msg", "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : "chatroom-user-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    } else {
        if (subTheme === 'spoiler') {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username, "style" : "float: right; margin-right: 25px;"}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp, "style" : "float: left; margin-left: -10px;"}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "this.html("+$("<div></div>", {"class" : "chatroom-user-msg", "html" : $message.html()})}).append($message);
            $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "style": "display: none", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'tokabot') {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "style": "color: grey;float: right; margin-right: 25px;", "text" : message.username}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "style": "color: grey;float: left; margin-left: -10px;", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "style": "color: grey", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            $username = $("<span></span>").appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp, "style" : "float: left; margin-left: -10px;"}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username, "style" : "float: right; margin-right: 25px;"}).appendTo($usernameContainer);
            $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp, "style" : "float: left; margin-left: -10px;"}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    }
    return $msgContainer;
}
