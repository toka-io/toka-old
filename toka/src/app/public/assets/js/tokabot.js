/* TokaBot
 * @desc: Toka's #1 bot
 */

function TokaBot() {
    
}

TokaBot.prototype.parseMessage = function(text) {

    textSplit = text.split(' ');
    $domElement = $('<span></span>');
    console.log(textSplit)
    
    textSplit.forEach(function(Word) {
        run = false
        Word = ' '+Word+' ';
        console.log(Word);
        
        if (Word == ' Kappa ') {
            console.log('Kappa');
            run = true;
            $domElement.append($('<img>', {'alt': "Kappa", 'src': "http://174.53.203.111/bobbotconsole/kappa.png", 'height': "26px", 'width': "26px"}));
        };
        
        if (Word == ' OpieOP ') {
            console.log('OpieOP');
            run = true;
            $domElement.append($('<img>', {'alt': "OpieOP", 'src': "http://174.53.203.111/bobbotconsole/pie.png", 'height': "26px", 'width': "26px"}));
        };
        
        if (run == false) {
            console.log('None');
            $domElement.append($('<span></span>').text(Word))
        };
    });

    return $domElement;
};
