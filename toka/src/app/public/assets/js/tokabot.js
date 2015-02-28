/* TokaBot 1.2
* @desc: Toka's #1 bot
* @author: Bob620
* @revisedBy: ArcTheFallen
*/
"use strict"

function TokaBot() {
    //Emote list, {'NAME': 'FILE'}
    this.emotes = {'Kappa': 'Kappa.png', 'OpieOP': 'pie.png'};
}

TokaBot.prototype.parseMessage = function(text) {
    var self = this
    var textSplit = text.split(' ');
    var $domElement = $('<span></span>');
    
    textSplit.forEach(function(Word) {
        var re = /^[\h\t\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ \?\=\#]+[\ \.\][a-z0-9\/\ \?\=\#]+$/i; 
        var link = [];
        var run = false;
    
        //emotes that are grabbed from the emote list
        if (self.emotes.hasOwnProperty(Word)) {
            run = true;
            $domElement.append($('<img>', {'title': Word, 'alt': Word, 'src': "http://toka.io/assets/images/emotes/"+self.emotes[Word], 'height': "26px"}));
        };
        
        //Link logic
        while ((link = re.exec(Word)) != null) {
            if (link.index === re.lastIndex) {
                re.lastIndex++;
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
};
