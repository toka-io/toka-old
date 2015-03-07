/* TokaBot 2.0
 * @desc: Toka's #1 Bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot() {
    
    // Set unchanging variables for messages
    this.emoteReS = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\_\+\<\>\|\?\*]+$/i;
    this.emoteReE = /^[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\_\+\<\>\|\?\*]+/i;
    this.nameReS = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*]+$/i;
    this.nameReE = /^[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\+\<\>\|\?\*]+/i;
    
    // Emote list, {'NAME': 'FILE'}
    this.emotesList = {'Kappa' : 'Kappa.png', 'OpieOP': 'pie.png', 'o/': 'Toka.png', 'O/': 'Toka.png', '<3': 'Heart.png'};
    this.commands = {'me' : true};
    this.mute = [];
    this.ban = [];
    this.mod = [];
    this.own = [];
    this.creator = '';
}

TokaBot.prototype.checkLink = function(word, $message) {
    var self = this;
    var run = false;
    var linkRe = /^[\h\t\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ \?\=\#\_\+\-\&]+[\ \.\][a-z0-9\/\ \?\=\#\_\+\-\&]+$/i; 
    var link = [];
    
    // Links check
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
                        $message.append($('<a></a>', {'href': wordLink, 'target': '_blank'}).text(' '+word+' '));
                        break;
                    }
                }
            }
        }
    } catch(err) {
    }
    if (run == false) {
        return self.checkEmote(word, $message);
    } else {
        return $message;
    }
}

TokaBot.prototype.checkEmote = function(word, $message) {
    var self = this;
    var run = false;
    var x = 0;
    
    // Emotes check
    try {
        var wordClear = word;
        while (x <= 1) {
            if (self.emotesList.hasOwnProperty(wordClear)) {
                run = true;
                if (wordClear == word) {
                    $message.append($('<img>', {'title': word, 'alt': word, 'src': "http://toka.io/assets/images/emotes/"+self.emotesList[word], 'height': "26px"}));
                    break;
                } else {
                    var wordStart = word.replace(self.emoteReS,'').replace(wordClear, '');
                    var wordEnd = word.replace(self.emoteReE, '').replace(wordClear, '');
                    if (wordStart != '') {
                        $message.append($('<span></span>').text(wordStart));
                    }
                    $message.append($('<img>', {'title': wordClear, 'alt': wordClear, 'src': "http://toka.io/assets/images/emotes/"+self.emotesList[wordClear], 'height': "26px"}));
                    if (wordEnd != '') {
                        $message.append($('<span></span>').text(wordEnd));
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
        return self.checkHighlight(word, $message);
    } else {
        return $message;
    }
}

TokaBot.prototype.checkHighlight = function(word, $message) {
    var self = this;
    var run = false;
    
    // Highlights check
    try {    
        if (word.search("@") == 0) {
            run = true;
            if (word.replace(self.nameReS,'').replace(self.nameReE, '').toString().toLowerCase() == '@'+getCookie('username').toString().toLowerCase()) {
                $message.append($('<span></span>', {'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(word));
            } else {
                $message.append($('<span></span>', {'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(word));
            }
        }
    } catch(err) {
    }
    if (run == false) {
        // Normal Text
        return $message.append($('<span></span>').text(' '+word+' '));
    } else {
        return $message;
    }
}

//////////////
/* Commands */
//////////////
TokaBot.prototype.doMute = function(name) {
    // Mute Function
    var self = this;
    var free = true;
    
    // appand a name to mute
    try {
        self.mute.forEach(function(names) {
            if (names == name) {
                free = false;
            }
        });
        if (free = true)
            self.mute.push(name)
            // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doUnMute = function(name) {
    // Unmute Function
    var self = this;
    
    // remove a name from mute
    try {
        self.mute.pop(self.mute.indexOf(name));
        // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doBan = function(name) {
    // Ban Function
    var self = this;
    var free = true;
    
    // appand a name to ban
    try {
        self.ban.forEach(function(names) {
            if (names == name) {
                free = false;
            }
        });
        if (free = true)
            self.ban.push(name)
            // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doUnBan = function(name) {
    // Unban Function
    var self = this;
    
    // remove a name from ban
    try {
        self.ban.pop(self.ban.indexOf(name));
        // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doMod = function(name) {
    // Mod Funciton
    var self = this;
    var free = true;
    
    // appand a name to mods
    try {
        self.mod.forEach(function(names) {
            if (names == name) {
                free = false;
            }
        });
        if (free = true)
            self.mod.push(name)
            // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doUnMod = function(name) {
    // Unmod Function
    var self = this;
    
    // remove a name from mods
    try {
        self.mod.pop(self.mod.indexOf(name));
        // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doOwn = function(name) {
    // Mod Funciton
    var self = this;
    var free = true;
    
    // appand a name to mods
    try {
        self.mod.forEach(function(names) {
            if (names == name) {
                free = false;
            }
        });
        if (free = true)
            self.own.push(name)
            // Update Chata
    } catch(err) {
    }
    return;
}

TokaBot.prototype.doUnOwn = function(name) {
    // Unmod Function
    var self = this;
    
    // remove a name from mods
    try {
        self.own.pop(self.own.indexOf(name));
        // Update Chata
    } catch(err) {
    }
    return;
}


TokaBot.prototype.getTheme = function(theme, message, $message) {
    var self = this;
    
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    if (theme === 'spoiler') { // Spoiler Message Theme
        var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
        var $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
        var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        var $message = $("<button></button>", {"text": "Spoiler", "class": "btn btn-primary", "onclick": "$('[id^="+message.timestamp+message.username+"]')"}).append($message);
        var $msg = $("<div></div>", {"id": message.timestamp+message.username, "class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "style": "display: none", "html" : $message.html()}).appendTo($msgContainer);
    } else {
        if (theme === "tokabot") { // Bot Message theme
                var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
                var $username = $("<span></span>", {"class" : "chatroom-user-name", "style": "color: grey", "text" : "TokaBot"}).appendTo($usernameContainer);
                var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "style": "color: grey", "text" : message.timestamp}).appendTo($usernameContainer);
                $usernameContainer.appendTo($msgContainer);
                var $msg = $("<div></div>", {"class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "style": "color: grey", "html" : $message.html()}).appendTo($msgContainer);
        } else {
            if (theme === "me") { //"me" theme
                var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
                var $username = $("<span></span>").appendTo($usernameContainer);
                var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
                $usernameContainer.appendTo($msgContainer);
                var $msg = $("<div></div>", {"class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
            } else { // Default Theme
                var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
                var $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
                var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : message.timestamp}).appendTo($usernameContainer);
                $usernameContainer.appendTo($msgContainer);
                var $msg = $("<div></div>", {"class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "html" : $message.html()}).appendTo($msgContainer);
            }
        }
    }
    
    return $msgContainer;
}

TokaBot.prototype.parseMessage = function(message) {
    
    var self = this;
    var first = false;
    var kill = false;
    // Make new lines visible to the parser
    message.text = message.text.replace(/\n/g, ' <br> ');
    
    // Set up basic variables for later
    var $message = ($('<div></div>', {"class": "chatroom-user-msg"})).append($('<span></span>'))
    var theme = "default";
    
    // Check if it is a command (possibly shorten this?)
    if (message.text.substr(0,1) == '/') {
        // "me" command
        if (message.text.substr(0,4).toLowerCase() === "/me ") {
            theme = "me";
            first = true;
        }
        // "Spoilers" command
        if (message.text.substr(0,9).toLowerCase() === "/spoiler ") {
            theme = "spoiler";
            first = true;
        }
        // "mute" command
        if (message.text.substr(0,6).toLowerCase() === "/mute ") {
            if (message.username == getCookie('username')) {
                if (message.text.split(' ')[1] == message.username) {
                    theme = 'tokabot';
                    message.text = 'You cannot mute yourself!';
                } else {
                    self.doMute(message.text.split(' ')[1]);
                    theme = 'tokabot';
                    message.text = 'You muted '+message.text.split(' ')[1];
                }
            } else {
                return;
            }
        }
        // "unmute" command
        if (message.text.substr(0,8).toLowerCase() === "/unmute ") {
            if (message.username == getCookie('username')) {
                if (message.text.split(' ')[1] == message.username) {
                    return;
                } else {
                    self.doUnMute(message.text.split(' ')[1]);
                    theme = 'tokabot';
                    message.text = 'You unmuted '+message.text.split(' ')[1];
                }
            } else {
                return;
            }
        }
        // "ban" command --Only commenting this one, since the rest are the same, or close to
        if (message.text.substr(0,5).toLowerCase() === "/ban ") {
            // Check to make sure YOU sent it
            if (message.username == getCookie('username')) {
                // Check for channel mod
                if (self.mod.indexOf(message.username) != -1) {
                    // Check to see if you attempted to ban yourself....
                    if (message.text.split(' ')[1] == message.username) {
                        theme = 'tokabot';
                        message.text = 'You cannot ban yourself!';
                    } else {
                        // Yay, you banned someone! GG!
                        self.doBan(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You banned '+message.text.split(' ')[1];
                    }
                } else {
                    // If you aren't a mod, perhaps you are a owner?
                    if (self.own.indexOf(message.username) != -1) {
                        // Check to see if you attempted to ban yourself....
                        if (message.text.split(' ')[1] == message.username) {
                            theme = 'tokabot';
                            message.text = 'You cannot ban yourself!';
                        } else {
                            // Yay, you banned someone! GG!
                            self.doBan(message.text.split(' ')[1]);
                            theme = 'tokabot';
                            message.text = 'You banned '+message.text.split(' ')[1];
                        }
                    } else {
                        // The.... Creator of the room....?
                        if (self.creator == message.username) {
                            // Check to see if you attempted to ban yourself....
                            if (message.text.split(' ')[1] == message.username) {
                                theme = 'tokabot';
                                message.text = 'You cannot ban yourself!';
                            } else {
                                // Yay, you banned someone! GG!
                                self.doBan(message.text.split(' ')[1]);
                                theme = 'tokabot';
                                message.text = 'You banned '+message.text.split(' ')[1];
                            }
                        } else {
                            // WELL THANKS FOR TAKING UP MY TIME DansGame
                            return;
                        }
                    }
                }
            } else {
                return;
            }
        }
        // "unban" command
        if (message.text.substr(0,7).toLowerCase() === "/unban ") {
            if (message.username == getCookie('username')) {
                if (self.mod.indexOf(message.username) != -1) {
                    if (message.text.split(' ')[1] == message.username) {
                        return;
                    } else {
                        self.doUnBan(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You unbanned '+message.text.split(' ')[1];
                    }
                } else {
                    if (self.own.indexOf(message.username) != -1) {
                        if (message.text.split(' ')[1] == message.username) {
                            return;
                        } else {
                            self.doUnBan(message.text.split(' ')[1]);
                            theme = 'tokabot';
                            message.text = 'You unbanned '+message.text.split(' ')[1];
                        }
                    } else {
                        if (self.creator == message.username) {
                            if (message.text.split(' ')[1] == message.username) {
                                return;
                            } else {
                                self.doUnBan(message.text.split(' ')[1]);
                                theme = 'tokabot';
                                message.text = 'You unbanned '+message.text.split(' ')[1];
                            }
                        } else {
                            return;
                        }
                    }
                }
            } else {
                return;
            }
        }
        // "mod" command
        if (message.text.substr(0,5).toLowerCase() === "/mod ") {
            if (message.username == getCookie('username')) {
                if (self.own.indexOf(message.username) != -1) {
                    if (message.text.split(' ')[1] == message.username) {
                        theme = 'tokabot';
                        message.text = 'You are a channel owner!';
                    } else {
                        self.doMod(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You modded '+message.text.split(' ')[1];
                    }
                } else {
                    if (self.creator == message.username) {
                        if (message.text.split(' ')[1] == message.username) {
                            theme = 'tokabot';
                            message.text = 'You are a channel owner!';
                        } else {
                            self.doMod(message.text.split(' ')[1]);
                            theme = 'tokabot';
                            message.text = 'You modded '+message.text.split(' ')[1];
                        }
                    } else {
                        return;
                    }
                }
            } else {
                return;
            }
        }
        // "unmod" command
        if (message.text.substr(0,7).toLowerCase() === "/unmod ") {
            if (message.username == getCookie('username')) {
                if (self.own.indexOf(message.username) != -1) {
                    if (message.text.split(' ')[1] == message.username) {
                        theme = 'tokabot';
                        message.text = 'You are a channel owner!';
                    } else {
                        self.doUnMod(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You unmodded '+message.text.split(' ')[1];
                    }
                } else {
                    if (self.creator == message.username) {
                        if (message.text.split(' ')[1] == message.username) {
                            theme = 'tokabot';
                            message.text = 'You are a channel owner!';
                        } else {
                            self.doUnMod(message.text.split(' ')[1]);
                            theme = 'tokabot';
                            message.text = 'You unmodded '+message.text.split(' ')[1];
                        }
                    } else {
                        return;
                    }
                }
            } else {
                return;
            }
        }
        // "own" command
        if (message.text.substr(0,5).toLowerCase() === "/own ") {
            if (message.username == getCookie('username')) {
                if (self.creator == message.username) {
                    if (message.text.split(' ')[1] == message.username) {
                        theme = 'tokabot';
                        message.text = 'You are a channel owner!';
                    } else {
                        self.doOwn(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You owned '+message.text.split(' ')[1];
                    }
                } else {
                    return;
                }
            } else {
                return;
            }
        }
        // "unown" command
        if (message.text.substr(0,7).toLowerCase() === "/unown ") {
            if (message.username == getCookie('username')) {
                if (self.creator == message.username) {
                    if (message.text.split(' ')[1] == message.username) {
                        theme = 'tokabot';
                        message.text = 'You cannot unown yourself!';
                    } else {
                        self.doUnOwn(message.text.split(' ')[1]);
                        theme = 'tokabot';
                        message.text = 'You unowned '+message.text.split(' ')[1];
                    }
                } else {
                    return;
                }
            } else {
                return;
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
        if (kill) {
            return;
        } else {
            // Read each word in chat seperatly and put it in $msgContainer
            message.text.split(' ').forEach(function(word) {
                if (first) {
                    first = false;
                    word = getCookie('username');
                }
                // If it is a break, make it a real one
                if (word == '<br>') {
                    $message.append($('<br />'));
                } else {
                    // Calculate for links, emotes, and highlights, then if everything fails print as normal text
                    $message = self.checkLink(word, $message);
                }
            });
        }
    } catch(err) {
        $message.append($('<span></span>'));
    }

    return self.getTheme(theme, message, $message);
}
