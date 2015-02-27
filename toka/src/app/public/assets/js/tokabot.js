/* TokaBot
* @desc: Toka's #1 bot
*/
function TokaBot() {
    
};

TokaBot.prototype.parseMessage = function(text) {
    textSplit = text.split(' ');
    $domElement = $('<span></span>');
    
    //Loop over the array of words in the message
    textSplit.forEach(function(Word) {
        run = false
        Word = ' '+Word+' ';
        
        //Temp Emote 1
        if (Word == ' Kappa ') {
            run = true;
            $domElement.append($('<img>', {'alt': "Kappa", 'src': "http://174.53.203.111/bobbotconsole/kappa.png", 'height': "26px", 'width': "26px"}));
        };
        //Temp Emote 2
        if (Word == ' OpieOP ') {
            run = true;
            $domElement.append($('<img>', {'alt': "OpieOP", 'src': "http://174.53.203.111/bobbotconsole/pie.png", 'height': "26px", 'width': "26px"}));
        };
        
        //If no keywords have been activated, add as plain text
        if (run == false) {
            $domElement.append($('<span></span>').text(Word))
        };
    });
    
    return $domElement;
};
