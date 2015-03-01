/* TokaBot 1.2
 * @desc: Toka's #1 bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */
"use strict"

function TokaBot() {
    this.emoteReS = /[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\_\+\<\>\|\?]+$/i;
    this.emoteReE = /^[\.\/\'\,\;\:\-\=\!\(\)\"\~\`\\\[\]\{\}\_\+\<\>\|\?]+/i;
    //Emote list, {'NAME': 'FILE'}
    this.emotes = {'Kappa' : 'Kappa.png', 'OpieOP': 'pie.png'};
};

TokaBot.prototype.parseMessage = function(text) {
    try {
        var self = this;
        var textSplit = text.split(' ');
        var $domElement = $('<span></span>');
        
        textSplit.forEach(function(Word) {
            var linkre = /^[\h\t\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ \?\=\#\_\+\-]+[\ \.\][a-z0-9\/\ \?\=\#\_\+\-]+$/i; 
            var link = [];
            var run = false;
            var WordClear = Word.replace(self.emoteReS,'').replace(self.emoteReE, '');
            
            //emotes that are grabbed from the emote list
            if (self.emotes.hasOwnProperty(WordClear)) {
                var WordStart = Word.replace(self.emoteReS,'').replace(WordClear, '');
                var WordEnd = Word.replace(self.emoteReE, '').replace(WordClear, '');
                run = true;
                if (WordStart != '') {
                    $domElement.append($('<span></span>').text(WordStart))
                };
                $domElement.append($('<img>', {'title': WordClear, 'alt': WordClear, 'src': "http://toka.io/assets/images/emotes/"+self.emotes[WordClear], 'height': "26px"}));
                if (WordEnd != '') {
                    $domElement.append($('<span></span>').text(WordEnd))
                };
            };
            
            //Link logic
            while ((link = linkre.exec(Word)) != null) {
                if (link.index === linkre.lastIndex) {
                    linkre.lastIndex++;
                    if (link[0] == Word) {
                        var Pass = false;
                        if (Word.search('http://') == 0) {
                            Pass = true;
                            var WordLink = Word;
                        };
                        if (Word.search('https://') == 0) {
                            Pass = true;
                            var WordLink = Word;
                        };
                        if (Pass == false) {
                            var WordLink = 'http://'+Word;
                        };
                        run = true;
                        $domElement.append($('<a></a>', {'href': WordLink, 'target': '_blank'}).text(' '+Word+' '));
                        break;
                    };
                };
            };
            
            //Highlight's the user's name if they are @ed
            if (Word == '@'+getCookie("username")) {
                run = true;
                $domElement.append($('<span></span>', {'style': 'background-color: rgba(11,15,18,0.8); color: white; border-radius: 4px; padding: 2px;'}).text(' '+Word+' '));
            };
            
            //If it's just plain text
            if (run == false) {
                $domElement.append($('<span></span>').text(' '+Word+' '));
            };
        });
        return $('<div></div>').append($domElement);
        } catch {
            return false
        }
};

TokaBot.prototype.isMe = function(text) {
    if (text.indexOf('/me ') == 0) {
        return true;
    } else {
        return false;
    };
};
