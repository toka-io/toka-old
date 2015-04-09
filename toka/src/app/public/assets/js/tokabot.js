/* TokaBot 2.2
 * @desc: Toka's #1 Bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot() {
    
    // Set unchanging variables for messages
    this.emoteReS = /^[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*\&\^\%\$\#\@\_]+/i;
    this.emoteReE = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*\&\^\%\$\#\@\_]+$/i;
    this.nameReS = /^@[\w]+/i;
    this.nameReE = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*\&\^\%\$\#\@]\w+$/i;
    
    // Emote list, {'NAME': 'FILE'}
    this.emotesList = {
            'o/': 'toka.png', 
            'O/': 'toka.png', 
            '<3': 'heart.png',
            '-_-' : 'standard/cat/CAT-_-.png',
            '>:(' : 'standard/cat/CAT-angry.png',
            ':3' : 'standard/cat/CAT-cat.png',
            'T_T' : 'standard/cat/CAT-cry.png',
            '>:)' : 'standard/cat/CAT-evilsmile.png',
            'catpa' : 'standard/cat/CAT-kappa.png',
            'catGasm' : 'standard/cat/CAT-o.png',
            ':P' : 'standard/cat/CAT-tongue.png',
            ':/' : 'standard/cat/CAT-slash.png',
            ':)' : 'standard/cat/CAT-smile.png',
            ':D' : 'standard/cat/CAT-Dsmile.png',
            '8)' : 'standard/cat/CAT-cool.png',
            ':(' : 'standard/cat/CAT-frown2.png',
            ';)' : 'standard/cat/CAT-wink.png'
    };
    this.commands = {'me' : true};
    
    // Set Default Theme
    this.mainTheme = 'default';
}

TokaBot.prototype.checkLink = function(word, line) {
    var self = this;
    var run = false;
    var linkRe = /^[\h\t\s\:\][a-z0-9\/\.\-]+\.[a-z0-9\/\ \?\=\#\_\+\-\&\:\$\%\,]+[\ \.\][a-z0-9\/\ \?\=\#\_\+\-\&\:\$\%\,]+$/i; 
    var emailRe = '';
    var link = [];
    var $line;
    
    // Links check
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
        if (run == false) {
            return self.checkEmote(word, line);
        } else {
            return ['', $line];
        }
    } else {
    }
}

TokaBot.prototype.checkEmote = function(word, line) {
    var self = this;
    var run = false;
    var x = 0;
    var $line;
    
    // Emotes check
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
    if (run == false) {
        return self.checkHighlight(word, line);
    } else {
        $line.append($('<span></span>').text(' '));
        return ['', $line];
    }
}

TokaBot.prototype.checkHighlight = function(word, line) {
    var self = this;
    var run = false;
    var $line;
    
    // Highlights check
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
    }
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
                    //toka.user.muteList[message.chatroomID].pop(toka.user.muteList[message.chatroomID].indexOf(subject));
                    //toka.user.muteList[message.chatroomID].push(subject);
                    //toka.unmuteUser(subject);
                    //toka.muteUser(subject);
                    return "You muted "+subject;
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
        console.log(err);
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
                            //toka.user.muteList[message.chatroomID].pop(toka.user.muteList[message.chatroomID].indexOf(name));
                            //toka.unmuteUser(subject);
                            return name+" has been unmuted";
                        } catch(err) {
                            return '';
                        }
                    }
                });
            } else {
                return "Use '/unmute username' to unmute someone";
            }
        }
    } catch(err) {
        console.log(err);
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
                if (toka.currentChatroom.banned.indexOf(subject) != -1) {
                    //toka.currentChatroom.banned.pop(toka.currentChatroom.banned.indexOf(subject));
                    //toka.currentChatroom.banned.push(subject);
                    //toka.currentChatroom.unbanUser(subject);
                    //toka.currentChatroom.banUser(subject);
                    return 'Banned '+subject;
                } else {
                    //toka.currentChatroom.banned.push(subject);
                    //toka.currentChatroom.banUser(subject);
                    return 'Banned '+subject;
                }
            } else {
                return "Use '/ban username' to ban someone, and '/unban username' to unban them";
            }
        }
    } catch(err) {
        console.log(err);
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
                    //toka.currentChatroom.banned.pop(toka.currentChatroom.banned.indexOf(subject));
                    //toka.currentChatroom.unbanUser(subject);
                    return subject+" has been unbanned";
                }
            } else {
                return "Use '/unban username' to unban someone";
            }
        }
    } catch(err) {
        console.log(err);
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
                if (toka.currentChatroom.mods.indexOf(subject) != -1) {
                    //toka.currentChatroom.mods.pop(toka.currentChatroom.banned.indexOf(subject));
                    //toka.currentChatroom.mods.push(subject);
                    //toka.currentChatroom.unmodUser(subject);
                    //toka.currentChatroom.modUser(subject);
                    return 'Modded '+subject;
                } else {
                    //toka.currentChatroom.mod.push(subject);
                    //toka.currentChatroom.modUser(subject);
                    return 'Modded '+subject;
                }
            } else {
                return "Use '/mod username' to mod someone, and '/unmod username' to unmod them";
            }
        }
    } catch(err) {
        console.log(err);
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
                    //toka.currentChatroom.mods.pop(toka.currentChatroom.banned.indexOf(subject));
                    //toka.currentChatroom.unmodUser(subject);
                    return subject+" has been unmodded";
                }
            } else {
                return "Use '/unmod username' to unmod someone";
            }
        }
    } catch(err) {
        console.log(err);
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
            return mods.substr(0, (mods.length-2));
        }
    } catch(err) {
        return "Error retrieving mod list";
    }
}


TokaBot.prototype.getTheme = function(subTheme, message, $message) {
    var self = this;
    var $msgContainer = '';
    
    // Standardized theme names
    var mainTheme = self.mainTheme.toLowerCase();
    subTheme = subTheme.toLowerCase();
    
    // Default chat grouped
    if (mainTheme === 'default-group') {
        $msgContainer = self.themeDefaultGroup(subTheme, message, $message);
        // IM-styled grouped chat
    } else if (mainTheme === 'im-group') {
        $msgContainer = self.themeIMGroup(subTheme, message, $message);
        // IM-styled chat
    } else if (mainTheme === 'im') {
        $msgContainer = self.themeIM(subTheme, message, $message);
        // Default chat
    } else {
        $msgContainer = self.themeDefault(subTheme, message, $message);
    }
    return $msgContainer;
}

TokaBot.prototype.parseMessage = function(message, type) {
    
    var self = this;
    var first = false;
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
            first = true;
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
                }
                // "Mute" Command
                if (message.text.split(' ')[0].toLowerCase() === "/mute") {
                    theme = "tokabot";
                    command = true;
                    message.text = self.doMute(message);
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
                    }
                    // "Unban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnBan(message);
                    }
                }
                // Owner Commands
                if (toka.currentChatroom.owner == message.username) {
                    // "Ban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/ban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doBan(message);
                    }
                    // "Unban" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unban") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnBan(message);
                    }
                    // "Mod" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/mod") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doMod(message);
                    }
                    // "Unmod" Command
                    if (message.text.split(' ')[0].toLowerCase() === "/unmod") {
                        theme = "tokabot";
                        command = true;
                        message.text = self.doUnMod(message);
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
                        if (first) {
                            first = false;
                            word = message.username;
                        }
                        // If it is a break, make it a real one
                        if (word.toLowerCase() == '<br>') {
                            $message.append($('<span></span>').text(line));
                            line = '';
                            $message.append($('<br />'));
                        } else {
                            // Calculate for links, emotes, and highlights, then if everything fails print as normal text
                            var check = self.checkLink(word, line);
                            if (check[0] == 'text') {
                                line = line+check[1]+' ';
                            } else {
                                $message.append(check[1]);
                                line = '';
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
    var $chat = $(toka.currentChatroom.selectChatroomList);
    self.getTheme(theme, message, $message).appendTo($chat);
    
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

TokaBot.prototype.receiveMessage = function(message) {
    var self = this;
    self.parseMessage(message, 'receive');
}

TokaBot.prototype.sendMessage = function(message) {
    var self = this;
    self.parseMessage(message, 'send');
}

// The Normal Theme
TokaBot.prototype.themeDefault =function(subTheme, message, $message) {
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
    return $msgContainer;
}

// The Normal Theme -Grouped-
TokaBot.prototype.themeDefaultGroup =function(subTheme, message, $message) {
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
        $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": ""}).append($message);
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
TokaBot.prototype.themeIM =function(subTheme, message, $message) {
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
            var $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "this.html("+$("<div></div>", {"class" : "chatroom-user-msg", "html" : $message.html()})}).append($message);
            var $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'tokabot') {
            var $username = $("<span></span>", {"class" : "chatroom-user-name", "style": "color: grey", "text" : message.username}).appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "style": "color: grey", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "style": "color: grey", "html" : $message.html()}).appendTo($msgContainer);
        } else if (subTheme === 'me') {
            var $username = $("<span></span>").appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            var $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
            var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
            $usernameContainer.appendTo($msgContainer);
            var $msg = $("<div></div>", {"class" : "chatroom-user-other-msg", "html" : $message.html()}).appendTo($msgContainer);
        }
    }
    return $msgContainer;
}

// The IM Styled Theme -Grouped-
TokaBot.prototype.themeIMGroup =function(subTheme, message, $message) {
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
            $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "this.html("+$("<div></div>", {"class" : "chatroom-user-msg", "html" : $message.html()})}).append($message);
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
