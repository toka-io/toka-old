/* TokaBot
 * @desc: Toka's #1 Bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot(options) {
    
    // Theme
    this.mainTheme = (toka.user) ? toka.user.chatTheme : 'normal';

    // Emote Set
    this.emoteSet = (toka.user) ? toka.user.emoteSet : 'standard/cat';
    
    this.options = options;    

    this.messageAttributes; // Stores attributes of the message for possible evaluation
    
    // Emote list, {'NAME': 'FILE'}
    this.emotes = {
        'o/': 'toka.png',
        'O/': 'toka.png',
        '<3': 'heart.png',
        '-_-' : this.emoteSet + '/0.png',
        '>:(' : this.emoteSet + '/1.png',
        ':3' : this.emoteSet + '/2.png',
        '8)' : this.emoteSet + '/3.png',
        'B)' : this.emoteSet + '/3.png',
        'T_T' : this.emoteSet + '/4.png',
        '>:)' : this.emoteSet + '/5.png',
        'catpa' : this.emoteSet + '/6.png',
        'catGasm' : this.emoteSet + '/7.png',
        ':P' : this.emoteSet + '/8.png',
        ':/' : this.emoteSet + '/9.png',
        ':\\' : this.emoteSet + '/9.png',
        ':)' : this.emoteSet + '/10.png',
        ':D' : this.emoteSet + '/11.png',        
        ':(' : this.emoteSet + '/12.png',
        ';)' : this.emoteSet + '/13.png'
    };
    
    this.addMessage = function(message) {
        var $chat = $(toka.currentChatroom.selectChatroomList);
        var $message;
        var leadingWord = message.text.substr(0, (message.text.indexOf(" ") != -1) ? message.text.indexOf(" ") : message.text.length);
        
        switch (leadingWord) {
            case "/me":
                $message = this.createMeMessage(message);
                break;
            case "/spoiler":
                $message = this.createSpoilerMessage(message);
                break;
            default:
                $message = this.createUserMessage(message);
                break;
        }        
        
        $chat.append($message);        
    }
    
    this.createMeMessage = function(message) {
        var $message = $("<li></li>", {"class" : "chatroom-message full-width"});
        
        var $info  = $("<div></div>", {"class" : "info", "html" : "&nbsp;"});
        var $timestamp = $("<span></span>", {"class" : "timestamp", "text" : message.timestamp})
        
        $timestamp.appendTo($info);
        $info.appendTo($message);
        
        var $messageText = $("<div></div>", {"class" : "me"});
        var index = (message.text.indexOf(" ") != -1) ? message.text.indexOf(" ") + 1 : message.text.length;
        $messageText.text(message.username + ' ' + message.text.substr(index));
        $messageText.appendTo($message);
        
        return $message;
    }
    
    this.createSpoilerMessage = function(message) {
        var $message = this.createUserMessage(message, true);
        
        var $messageText = $message.children(".text");
        var index = (message.text.indexOf(" ") != -1) ? message.text.indexOf(" ") + 1 : message.text.length;
        
        var $spoiler = $("<div></div>", {"style" : "cursor:pointer;", "class" : "spoiler", "type" : "button", "text" : "Spoiler"}).data("show", false);        
        var $parsedMessage = this.parseMessage(message, message.text.substr(index));
        
        $spoiler.on("click", function() {
            if (!$(this).data("show")) {
                $(this).attr("style", "cursor: text;");
                $(this).empty().append($parsedMessage);
                $(this).data("show", true);
            }
        });
        
        $messageText.text("");
        $messageText.append($spoiler);
        
        return $message;
    }
    
    this.createUserMessage = function(message, blank) {
        var isSender = message.username === toka.getCookie('username');
        
        var $message = $("<li></li>", {"class" : "chatroom-message"});        
        var $info  = $("<div></div>", {"class" : "info"});        
        var $username = $("<span></span>", {"class" : "username", "text" : message.username})
        var $timestamp = $("<span></span>", {"class" : "timestamp", "text" : message.timestamp})        
        
        $username.appendTo($info);
        $timestamp.appendTo($info);
        $info.appendTo($message);       
        
        var $messageText = $("<div></div>", {"class" : (isSender) ? "sender text" : "other text"});
        
        if (!blank) {
            var $parsedMessage = this.parseMessage(message, message.text);
            $messageText.append($parsedMessage);
        }
        
        $messageText.appendTo($message);
        
        return $message;
    }
    
    this.isEmote = function(word) {
        return this.emotes.hasOwnProperty(word);
    }
    
    this.isHashtag = function(word) {
        var hashtagRegex = /^#[a-zA-Z0-9]+$/i;
        return word.match(hashtagRegex);
    }
    
    this.isUrl = function(word) {
        var urlRegex = /^(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?:\w+:\w+@)?((?:(?:[-\w\d{1-3}]+\.)+(?:com|org|net|gov|mil|biz|info|moe|mobi|name|aero|jobs|edu|co\.uk|ac\.uk|it|fr|tv|museum|asia|local|travel|[a-z]{2}))|((\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)(\.(\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)){3}))(?::[\d]{1,5})?(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?:#(?:[-\w~!$ |\/.,*:;=]|%[a-f\d]{2})*)?$/i;
        //var urlRegex = /^[\h\t\s\:\][a-z0-9\/\.\-]+\.[a-z0-9\/\ \?\=\#\_\+\-\&\:\$\%\,]+[\ \.\/][a-z0-9\~\!\@\#\$\%\^\&\*\(\)\_\+\`\-\=\[\{\}\]\:\"\?\/\.\>\,\<\\]+$/i;

        return word.match(urlRegex);
    }
    
    this.isUsername = function(message, word) {
        var self = this;
        var usernameRegex = /^@[a-zA-Z][a-zA-Z0-9_]{2,15}$/i;
        
        if (word.match(usernameRegex) && message.type == 'send') {
            $.ajax({
                url: "/user/"+word.substr(1)+"/available",
                type: "get",
                success: function(response) {
                    console.log(response);
                    if (response == 0) {
                        message.chatroomId = word.substr(1);
                        toka.socket.emit("sendMessage", message);
                    }
                }
            });
            return true;
        } 
        else if (word.match(usernameRegex)) {
            return true;
        } 
        else {
            return false;
        }
    }
    
    this.parseMessage = function(message, text) {
        var $parsedMessage = $("<div></div>");
        
        var words = text.split(" ");
        
        for (var i = 0; i < words.length; i++) {
            var word = words[i];
            var $parsedWord = this.parseWord(message, word); 
            
            $parsedMessage.append($parsedWord);
        }
        
        return $parsedMessage;
    }
    
    this.parseWord = function(message, word) {        
        var $parsedWord = $("<div></div>");
        
        if (this.isEmote(word)) {
            // This is an emote!
            return $("<div>").append($("<img />", {
                'class': 'emote',
                'src': '/assets/images/emotes/' + this.emotes[word]
            })).append($("<span></span>", {
                'text': ' '
            })).children();
        }
        else if (this.isUsername(message, word)) {
            // This is a username!
            var $username = $("<div></div>");
            
            if (word.substr(1) != toka.getCookie('username'))
                $username.append($('<span></span>', {
                    'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold',
                    'text': word
                })).append($("<span></span>").text(' '));
            else
                $username.append($('<span></span>', {
                    'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold',
                    'text': word
                 })).append($("<span></span>").text(' '));
            
            return $username.children();
        }
        else if (this.isHashtag(word)) {
            // This is an hashtag!
            var $hashtag;
            var url;
            
            if (this.options.embed) {
                url = (this.options.target == "_blank") ? '/chatroom/' + word.substr(1) : '/chatroom/' + word.substr(1) + '?embed=1';
                
                $hashtag = $("<a></a>", {
                    'href': url,
                    'text': word + ' ',
                    'target': this.options.target 
                });
            }
            else {
                url = '/chatroom/' + word.substr(1) + '?embed=1';
                
                $hashtag = $("<a></a>", {
                    'href': '#',
                    'text': word + ' '
                });
                $hashtag.on("click", function() {
                    $("#chatroom-popup").modal('show');
                    var src = $("#chatroom-popup iframe").attr('src');
                    
                    if (src != url)
                        $("#chatroom-popup iframe").attr('src', url);
                 });
            }
            
            return $hashtag;
        }
        else if (this.isUrl(word)) {
            // This is an url!
            if (word.indexOf("youtube.com") > -1)
                this.messageAttributes['contains']['youtubeUrl'] = true;
            
            this.messageAttributes['contains']['link'] = true;
            
            return $("<a></a>", {
                'href': word,
                'text': word + ' ',
                'target': '_blank'
             });
        }
        else {
            // This is neither an emote, hashtag, or url..!            
            return $("<span></span>", {
               'text': word + ' '
            });
        }    
    }
    
    this.receiveMessage = function(message) {
        message['type'] = 'receive';
        this.messageAttributes = {'contains': {}};
        
        this.addMessage(message);
    }
    
    this.sendMessage = function(message) {
        message['type'] = 'send';
        this.messageAttributes = {'contains': {}}; // Resets message attributes
        
        this.addMessage(message);
        
        toka.socket.emit("sendMessage", message);
        
        if (this.messageAttributes['contains']['youtubeUrl']) {
            message.chatroomId = "youtube";
            toka.socket.emit("sendMessage", message);
        }
        
        if (this.messageAttributes['contains']['link']) {
            message.chatroomId = "link";
            toka.socket.emit("sendMessage", message);
        }
    }
}