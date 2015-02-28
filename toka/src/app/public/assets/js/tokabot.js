/* TokaBot 1.2
* @desc: Toka's #1 bot
* @author: Bob620
* @revisedBy: ArcTheFallen
*/
function TokaBot() {
    
}

TokaBot.prototype.parseMessage = function(text) {
    textSplit = text.split(' ');
    $domElement = $('<span></span>');
    
    //Emote list, {'NAME': 'FILE'}
    emotes = {'Kappa': 'Kappa.png', 'OpieOP': 'pie.png'};
    
    textSplit.forEach(function(Word) {
    re = /^[\h\t\p\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ ]+[\ \.\][a-z0-9\/\ ]+$/i;
    var link = [];
    run = false
    
    //emotes that are grabbed from the emote list
    if (emotes[Word]) {
        run = true;
        $domElement.append($('<img>', {'title': Word, 'alt': Word, 'src': "http://toka.io/assets/images/emotes/"+emotes[Word], 'height': "26px"}));
    };
    
    //Link logic
        while ((link = re.exec(Word)) != null) {
            if (link.index === re.lastIndex) {
                re.lastIndex++;
                if (link[0] == Word) {
                    Pass = false;
                    if (Word.search('http://') == 0) {
                        Pass = true;
                        WordLink = Word;
                    };
                    if (Word.search('https://') == 0) {
                        Pass = true;
                        WordLink = Word;
                    };
                    if (Pass == false) {
                        WordLink = 'http://'+Word;
                    };
                    run = true;
                    $domElement.append($('<a></a>', {'href': WordLink, 'target': '_blank'}).text(' '+Word+' '));
                    break
                };
            };
        };
        
        //Highlight's the user's name if they are @ed
        if (Word == '@'+getCookie("username")) {
            run = true;
            $domElement.append($('<span></span>', {'style': 'background: #D8D8D8'}).text(' '+Word+' '));
        };
        //If it's just plain text
        if (run == false) {
            $domElement.append($('<span></span>').text(' '+Word+' '));
        };
    });
    return $('<div></div>').append($domElement);
};
