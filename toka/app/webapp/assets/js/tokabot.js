/* TokaBot
 * @desc: Toka's #1 Bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot(options) {
    
    // TokaBot Regex
    this.commandRegex = /^(\/[a-z]+)([\s]*.*)/;
    this.hashtagRegex = /^#([a-zA-Z0-9]+)/;
    this.urlRegex = /^(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?:\w+:\w+@)?((?:(?:[-\w\d{1-3}]+\.)+(?:com|org|net|gov|mil|biz|info|moe|mobi|name|aero|jobs|edu|co\.uk|ac\.uk|it|fr|tv|museum|asia|local|travel|[a-z]{2}))|((\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)(\.(\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)){3}))(?::[\d]{1,5})?(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?:#(?:[-\w~!$ |\/.,*:;=]|%[a-f\d]{2})*)?/i;
    this.usernameRegex = /^@([a-zA-Z0-9_]{3,25})/;
    
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
        
        var command = this.getCommand(message.text);
        command = (command == null) ? "" : command[1];
        
        switch (command) {
            case "/me":
                $message = this.createMeMessage(message);
                break;
            case "/image":
                $message = this.createImageMessage(message);
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
    
    this.commandCommands = function(message) {
        message.text = "The commands available are: " +
        		"\n /define [word] " +
        		"\n /me [text] " +
        		"\n /spoiler [text] " +
        		"\n /urban [word]" +
        		"\n /view [room]";
        this.createTokabotMessage(message);
    }
    
    this.commandDefine = function(message) {
        var self = this;
        var word = message.text.substr(7).trim();
        
        if (word == "") {
            message.text = 'Command Error: "/define [word]" \nAdvice: Please provide a word for the command!';  
            self.createTokabotMessage(message);
            return;
        }            
        
        $.ajax({
            url: "https://montanaflynn-dictionary.p.mashape.com/define?word="+word,
            headers: {
                "X-Mashape-Key": "sg6Dd6Ff8Fmshpfw0y54clebF90dp1ENrq8jsnfWhLmR7wG7eX",
                "Accept": "application/json" 
            },
            success: function(response) {
                console.log(response);
                if (response.definitions.length == 0)
                    message. text = word + " - " + "No definition available :("
                else
                    message.text = word + " - " + response.definitions[0].text;
                self.createTokabotMessage(message);
            }
        });
    }
    
    this.commandUrban = function(message) {
        var self = this;
        var word = message.text.substr(6).trim();
        
        if (word == "") {
            message.text = 'Command Error: "/urban [word]" \nAdvice: Please provide a word for the command!';  
            self.createTokabotMessage(message);
            return;
        }   
        
        $.ajax({
            url: "https://mashape-community-urban-dictionary.p.mashape.com/define?term="+word,
            headers: {
                "X-Mashape-Key": "sg6Dd6Ff8Fmshpfw0y54clebF90dp1ENrq8jsnfWhLmR7wG7eX",
                "Accept": "text/plain" 
            },
            success: function(response) {
                if (response.result_type == "no_results")
                    message.text = word + " - No definition available on urban :(";
                else
                    message.text = word + " - " + response.list[0].definition;
                self.createTokabotMessage(message);
            }
        });
    }
    
    this.commandView = function(message) {
        var self = this;
        var word = message.text.substr(5).trim();        
        
        if (word == "") {
            message.text = 'Command Error: "/view [room]" \nAdvice: Please provide a room for the command!';  
            self.createTokabotMessage(message);
            return;
        }           
        
        var url = '/chatroom/' + word + '?embed=1';
        
        $("#chatroom-popup").modal('show');                    
        var src = $("#chatroom-popup iframe").get(0).contentWindow.location.href;
        
        if (src != window.location.origin+url)
            $("#chatroom-popup iframe").attr('src', url);
    }
    
    this.createMeMessage = function(message) {
        var text = message.text.substr(3);
        
        if (text.trim() == "") {
            message.text = 'Command Error: "/me [text]" \nAdvice: Please provide text for the command!';            
            this.createTokabotMessage(message);
            this.messageAttributes['error'] = true;
            return;
        }  
        
        var $message = $("<li></li>", {"class" : "chatroom-message full-width"});
        
        var $info  = $("<div></div>", {"class" : "info", "html" : "&nbsp;"});
        var $timestamp = $("<span></span>", {"class" : "timestamp", "text" : message.timestamp})
        
        $timestamp.appendTo($info);
        $info.appendTo($message);
        
        var $messageText = $("<div></div>", {"class" : "me"});
        
        $messageText.text(message.username + text);
        $messageText.appendTo($message);
        
        return $message;
    }
    
    this.createImageMessage = function(message) {
        var text = message.text.substr(6);
        
        if (text.trim() == "") {
            message.text = 'Command Error: "/image [id]" \nAdvice: Please provide an id for the command!';            
            this.createTokabotMessage(message);
            this.messageAttributes['error'] = true;
            return;
        }  
        
        var $message = $("<li></li>", {"class" : "chatroom-message"});        
        var $info  = $("<div></div>", {"class" : "info"});        
        var $username = $("<span></span>", {"class" : "username", "text" : message.username})
        var $timestamp = $("<span></span>", {"class" : "timestamp", "text" : message.timestamp})        
        
        $username.appendTo($info);
        $timestamp.appendTo($info);
        $info.appendTo($message);       
        
        var $messageText = $("<div></div>", {"class" : "image text"});
        
        var $image = $.cloudinary.image(text.trim(), { 
            height: 150, 
            crop: 'fill', 
            radius: 5,
            default_image: 'notfound_pmscxe.png'
        });        
        var $originalImage = $.cloudinary.image(text.trim(), {default_image: 'notfound_pmscxe.png'});
        
        var $imageLink = $("<a></a>", {
            'href': $originalImage.attr('src'), 
            'data-lightbox': text.trim(),
            'data-title': 'Toka - ' + text.trim(),
        }).on("click", function (e) {
            e.preventDefault();
        });
        
        $messageText.append($imageLink.append($image));
        $messageText.appendTo($message);
        
        return $message;
    }
    
    this.createSpoilerMessage = function(message) {
        var text = message.text.substr(9);
        
        if (text.trim() == "") {
            message.text = 'Command Error: "/spoiler [text]" \nAdvice: Please provide text for the command!';            
            this.createTokabotMessage(message);
            this.messageAttributes['error'] = true;
            return;
        }  
        
        var $message = this.createUserMessage(message, true);
        
        var $messageText = $message.children(".text");
        
        var $spoiler = $("<div></div>", {"style" : "cursor:pointer;", "class" : "spoiler", "type" : "button", "text" : "Spoiler"}).data("show", false);        
        var $parsedMessage = this.parseMessage(message, text);
        
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
    
    this.createTokabotMessage = function(message) {
        var isSender = message.username === toka.getCookie('username');
        
        var $message = $("<li></li>", {"class" : "chatroom-message"});        
        var $info  = $("<div></div>", {"class" : "info"});        
        var $username = $("<span></span>", {"class" : "username", "text" : "tokabot"})
        var $timestamp = $("<span></span>", {"class" : "timestamp", "text" : message.timestamp})        
        
        $username.appendTo($info);
        $timestamp.appendTo($info);
        $info.appendTo($message);
        
        var $messageText = $("<div></div>", {"class" : "tokabot text"});
        
        var $parsedMessage = $("<span></span>").text(message.text);
        $messageText.append($parsedMessage);
        
        $messageText.appendTo($message);
        
        var $chat = $(toka.currentChatroom.selectChatroomList);
        $chat.append($message);
        
        toka.currentChatroom.scrollChatToBottom();
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

    this.getCommand = function(text) {        
        return this.commandRegex.exec(text);
    }
    
    this.loadHistory = function(history) {        
        for (var i=0; i < history.data.length; i++) {
            this.messageAttributes = {'contains': {}};
            var message = history.data[i];
            this.addMessage(message);
            
            toka.currentChatroom.lastSender = message.username;  
        }
             
        toka.currentChatroom.scrollChatToBottom();        
    }
    
    this.isEmote = function(word) {
        return this.emotes.hasOwnProperty(word);
    }
    
    this.isHashtag = function(word) {        
        return word.match(this.hashtagRegex);
    }
    
    this.isUrl = function(word) {
        return word.match(this.urlRegex);
    }
    
    this.isUsername = function(message, word) {
        var self = this;        
        var usernameMatch = this.usernameRegex.exec(word);
        
        if (usernameMatch && message.type == 'send') {
            $.ajax({
                url: "/user/"+usernameMatch[1]+"/available",
                type: "get",
                success: function(response) {
                    if (response == 0) {
                        // Send message to user's chatfeed
                        message.chatroomId = usernameMatch[1];
                        toka.socket.emit("sendMessage", message);
                        
                        // Send a receipt message to sender's chatfeed
                        if (message.chatroomId != toka.getCookie('username') && !self.messageAttributes['senderReceipt']) {
                            message.chatroomId = toka.getCookie('username');
                            toka.socket.emit("sendMessage", message);
                            self.messageAttributes['senderReceipt'] = true;
                        }
                    }
                }
            });
            return true;
        } 
        else if (usernameMatch) {
            return true;
        } 
        else {
            return false;
        }
    }
    
    this.parseMessage = function(message, text) {
        var $parsedMessage = $("<div></div>");
        
        var words = text.split(/(\s+)/);
        
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
            var usernameMatch = this.usernameRegex.exec(word);
            var usernameText = word.substr(0, usernameMatch[1].length+1);
            var remainderText = word.substr(usernameMatch[1].length+1);
            
            if (usernameMatch[1] != toka.getCookie('username'))
                $username.append($('<span></span>', {
                    'style': 'background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold',
                    'text': usernameText
                })).append($("<span></span>").text(remainderText));
            else
                $username.append($('<span></span>', {
                    'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px; font-weight: bold',
                    'text': usernameText
                 })).append($("<span></span>").text(remainderText));
            
            return $username.children();
        }
        else if (this.isHashtag(word)) {
            // This is an hashtag!
            var url;
            var $hashtag = $("<div></div>");
            var hashtagMatch = this.hashtagRegex.exec(word);
            var hashtagText = word.substr(0, hashtagMatch[1].length+1);
            var remainderText = word.substr(hashtagMatch[1].length+1);            
            
            if (this.options.embed) {
                url = (this.options.target == "_blank") ? '/chatroom/' + hashtagMatch[1] : '/chatroom/' + hashtagMatch[1] + '?embed=1';
                
                $hashtag.append($("<a></a>", {
                    'href': url,
                    'text': hashtagText,
                    'target': this.options.target 
                })).append($("<span></span>").text(remainderText));
            }
            else {
                url = '/chatroom/' + hashtagMatch[1] + '?embed=1';
                
                $hashtag.append($("<a></a>", {
                    'href': '#',
                    'text': hashtagText
                }).on("click", function() {
                    $("#chatroom-popup").modal('show');                    
                    var src = $("#chatroom-popup iframe").get(0).contentWindow.location.href;
                    
                    if (src != window.location.origin+url)
                        $("#chatroom-popup iframe").attr('src', url);
                 })).append($("<span></span>").text(remainderText));
            }
            
            return $hashtag.children();
        }
        else if (this.isUrl(word)) {
            // This is an url!
            if (word.indexOf("youtube.com") > -1)
                this.messageAttributes['contains']['youtubeUrl'] = true;
            
            this.messageAttributes['contains']['link'] = true;
            
            var $href = $("<div></div>");
            var urlMatch = this.urlRegex.exec(word);
            var urlText = urlMatch[0];
            var href = urlMatch[0];            
            var remainderText = word.substr(urlText.length);
            
            if (!href.match(/^http(s)?:\/\//))
                href = "http://" + href;
            
            
            $href.append($("<a></a>", {
                'href': href,
                'text': urlText,
                'target': '_blank'
            })).append($("<span></span>").text(remainderText));
            
            return $href.children();
        }
        else {
            // This is neither an emote, hashtag, or url..!
            return $("<span></span>", {
               'text': (word == "") ? '' : word
            });
        }    
    }
    
    this.receiveMessage = function(message) {
        message['type'] = 'receive';
        this.messageAttributes = {'contains': {}};
        
        this.addMessage(message);
        
        if (toka.currentChatroom.autoScroll) {        
            toka.currentChatroom.scrollChatToBottom();
        }
        
        toka.currentChatroom.lastSender = message.username;
    }
    
    this.sendMessage = function(message) {
        message['type'] = 'send';
        this.messageAttributes = {'contains': {}, 'senderReceipt': false, 'error': false}; // Resets message attributes
        
        var send = false;       
        var command = this.getCommand(message.text);
        command = (command == null) ? "" : command[1];
        
        switch (command) {
            case "/command":
                this.commandCommands(message);
                break;
            case "/commands":
                this.commandCommands(message);
                break;
            case "/define":
                this.commandDefine(message);
                break;
            case "/urban":
                this.commandUrban(message);
                break;
            case "/view":
                this.commandView(message);
                break;
            default:
                this.addMessage(message);
                send = true;
                break;
        } 
        
        if (send && !this.messageAttributes['error']) {
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
        
        toka.currentChatroom.scrollChatToBottom();
    }
}