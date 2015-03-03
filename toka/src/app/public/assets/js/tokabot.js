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
    // Emote list, {'NAME': 'FILE'}
    this.emotesList = {'Kappa' : 'Kappa.png', 'OpieOP': 'pie.png', 'o/': 'Toka.png', 'O/': 'Toka.png', '<3': 'Heart.png'};
    try {
        this.name = 'bob620';//getCookie("username");
    } catch(err) {
        this.name = 'TokaBot';
    }
}

TokaBot.prototype.parseMessage = function(text) {
    
    // Set up basic variables for later
    var self = this;
    var $msgContainer = $('<li></li>', {"class": "chatroom-msg chatroom-user"});
    var $usernameContainer = $("<div></div>", {"class": "chatroom-user-container"})
    var $username = $('<span></span>', {"class": "chatroom-user-name", "text": self.name}).appendTo($usernameContainer);
    var $timestamp = $('<span></span>', {"class": "chatroom-user-timestamp", "text": timeStamp}).appendTo($usernameContainer);
    $usernameContainer.appendTo($msgContainer);
    var $message = ($('<div></div>', {"class": "chatroom-user-msg"})).append($('<span></span>'))
    
    // Read each word in chat seperatly and put it in $msgContainer
    text.split(' ').forEach(function(word) {
        var run = false;
        // Calculate for links, emotes, and highlights, then if everything fails print as normal text
        // First off: Links
        try {
            var linkRe = /^[\h\t\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ \?\=\#\_\+\-\&]+[\ \.\][a-z0-9\/\ \?\=\#\_\+\-\&]+$/i; 
            var link = [];
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
            console.log(err);
        }
        
        // Second: Emotes
        try {
            var x = 0;
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
                            $message.append($('<span></span>').text(wordStart))
                        }
                        $message.append($('<img>', {'title': wordClear, 'alt': wordClear, 'src': "http://toka.io/assets/images/emotes/"+self.emotesList[wordClear], 'height': "26px"}));
                        if (wordEnd != '') {
                            $message.append($('<span></span>').text(wordEnd))
                        }
                        break;
                    }
                } else {
                    x++;
                    wordClear = word.replace(self.emoteReS,'').replace(self.emoteReE, '');
                }
            }
        } catch(err) {
            console.log(err);
        }
        
        // Third: Highlights
        if (word.search("@") == 0) {
            run = true;
            if (word == '@'+self.name) {
                $message.append($('<span></span>', {'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(' '+word+' '));
            } else {
                $message.append($('<span></span>', {'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold'}).text(' '+word+' '));
            }
        }
        
        // Last, but not least, Normal Text
        if (run == false) {
            $message.append($('<span></span>').text(' '+word+' '));
        }
    })
    return $msgContainer.append($message);
}

TokaBot.prototype.isMe = function(text) {
    if (text.indexOf('/me ') == 0) {
        return true;
    } else {
        return false;
    }
}
