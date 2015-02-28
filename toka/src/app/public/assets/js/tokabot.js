/* TokaBot
 * @desc: Toka's #1 bot
 * @author: Bob620
 * @revisedBy: ArcTheFallen
 */

function TokaBot() {
    
}

TokaBot.prototype.parseMessage = function(text) {

    textSplit = text.split(' ');
    $domElement = $('<span></span>');
    console.log(textSplit)
    
    textSplit.forEach(function(Word) {
        //Set the regular expresion for links
        re = /^[\h\t\p\s\:\][a-z0-9\/\ ]+\.[a-z0-9\/\ ]+[\ \.\][a-z0-9\/\ ]+$/i; 
        var link = [];
        run = false
        Word = ' '+Word+' ';
        console.log(Word);
        
        //Temp Emote 1
        if (Word == ' Kappa ') {
            console.log('Kappa');
            run = true;
            $domElement.append($('<img>', {'alt': "Kappa", 'src': "/assets/images/emotes/kappa.png", 'height': "26px", 'width': "23px"}));
        };
        
        //Temp Emote 2
        if (Word == ' OpieOP ') {
            console.log('OpieOP');
            run = true;
            $domElement.append($('<img>', {'alt': "OpieOP", 'src': "/assets/images/emotes/pie.png", 'height': "26px", 'width': "26px"}));
        };
        
        //Link Testing via Regular Expresion
        while ((link = re.exec(Word)) != null) {
            if (link.index === re.lastIndex) {
                re.lastIndex++;
                if (link[0] == Word) {
                    Pass = false;
                    if (Word.trim().search('http://') == 0) {
                        Pass = true;
                        WordLink = Word.trim();
                    };
                    if (Word.trim().search('https://') == 0) {
                        Pass = true;
                        WordLink = Word.trim();
                    };
                    if (Pass == false) {
                        WordLink = 'http://'+Word.trim();
                    }
                    console.log('Link');
                    run = true;
                    $domElement.append($('<a></a>', {'href': WordLink}).text(Word));
                    break
                };
            };
        };
        
        //If it's normal text
        if (run == false) {
            console.log('None');
            $domElement.append($('<span></span>').text(Word));
        };
    });

    return $("<div></div>").append($domElement);
};
