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
        console.log("Links check failed: " + err);
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
        console.log("Emotes check failed: " + err);
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
                $message.append($('<span></span>', {'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(' '+word+' '));
            } else {
                $message.append($('<span></span>', {'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(' '+word+' '));
            }
        }
    } catch(err) {
        console.log("Highlights check failed: " + err);
    }
    
    if (run == false) {
        // Normal Text
        return $message.append($('<span></span>').text(' '+word+' '));
    } else {
        return $message;
    }
}

TokaBot.prototype.getTheme = function(theme, message, $message) {
    var self = this;
    
    // Logged in user
    var username = getCookie('username');
    // Chat message top most container
    var $msgContainer = $("<li></li>", {"class" : "chatroom-msg chatroom-user"});
    
    if (theme === "me") { //"/me" theme
        var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
        var $username = $("<span></span>").appendTo($usernameContainer);
        var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : timeStamp()}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        var $msg = $("<div></div>", {"class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "text": message.username, "style": "font-weight: bold", "html" : $message.html()}).appendTo($msgContainer);
    } else { // Default Theme
        var $usernameContainer = $("<div></div>", {"class" : "chatroom-user-container"});
        var $username = $("<span></span>", {"class" : "chatroom-user-name", "text" : message.username}).appendTo($usernameContainer);
        var $timestamp = $("<span></span>", {"class" : "chatroom-user-timestamp", "text" : timeStamp()}).appendTo($usernameContainer);
        $usernameContainer.appendTo($msgContainer);
        var $msg = $("<div></div>", {"class" : (message.username === username) ? "chatroom-user-msg" : "chatroom-user-other-msg", "html" : $message.html()}).appendTo($msgContainer);
    }
    
    return $msgContainer;
}

TokaBot.prototype.parseMessage = function(message) {
    var self = this;
    
    // Set up basic variables for later
    var $message = ($('<div></div>', {"class": "chatroom-user-msg"})).append($('<span></span>'))
    var theme = "default";
    
    if (message.text.substr(0,3) === "/me")
        theme = "me";
    
    try {
        // Read each word in chat seperatly and put it in $msgContainer
        message.text.split(' ').forEach(function(word) {
            // console.log(word);
            // Calculate for links, emotes, and highlights, then if everything fails print as normal text
            $message = self.checkLink(word, $message);
        });
    } catch(err) {
        $message.append($('<span></span>'));
    }

    return self.getTheme(theme, message, $message);
}
