/*******************************************************************************
 * Imports
 ******************************************************************************/
var UglifyJS = require('uglify-js');
var uglifycss = require('uglifycss');
var fs = require('fs');

/*******************************************************************************
 * Script Variables
 ******************************************************************************/
var cssAssetLocation = "../app/webapp/assets/css";
var jsAssetLocation = "../app/webapp/assets/js";
var option = process.argv[2];

/*******************************************************************************
 * Minify JS Files
 ******************************************************************************/
// -d for development aka readable files
var options = {};
if (option == "-d")
    options = {
        output : {
            beautify : true
        },
        compress : false
    };

// Compile toka js files
minifyJS({
    'toka.min.js' : [ 'util/global-util.js', 'util/autocomplete.js',
            'util/command-help.js', 'util/log4n.js', 'src/chatroom-app.js',
            'src/left-nav-app.js', 'src/top-nav-app.js', 'src/toka.js' ],
    'tokabot.min.js' : [ 'src/tokabot.js' ],
    'chatroom-list-app.min.js' : [ 'src/chatroom-list-app.js' ],
    'atlas-client.min.js' : ['util/atlas/atlas-client.js', 'util/atlas/chat-message.js', 'util/atlas/event.js'],
    'settings-app.min.js' : [ 'src/settings-app.js' ]
}, options);

/*******************************************************************************
 * Minify CSS Files
 ******************************************************************************/
minifyCSS({
    'toka.min.css' : [ 'src/animation.css', 'src/toka.css', 'src/navbar.css',
            'src/left-nav.css', 'src/form.css', 'src/category.css',
            'src/chatroom.css', 'src/command-help.css' ],
    'settings-app.min.css' : [ 'src/settings-app.css' ]
}, {
    maxLineLen : 0,
    expandVars : true
})

/*******************************************************************************
 * Script Functions
 ******************************************************************************/
function minifyJS(jsMap, options) {
    for ( var target in jsMap) {
        var input = getJSLocation(jsMap[target]);
        var result = UglifyJS.minify(input, options);
        fs.writeFileSync(getJSLocation(target), result.code);
    }
}

function minifyCSS(cssMap, options) {
    for ( var target in cssMap) {
        var css = uglifycss.processFiles(getCSSLocation(cssMap[target]),
                options);
        fs.writeFileSync(getCSSLocation(target), css);
    }
}

function getCSSLocation(files) {
    if (files.constructor !== Array)
        return cssAssetLocation + "/" + files;

    for (var i = 0; i < files.length; i++)
        files[i] = cssAssetLocation + "/" + files[i];
    return files;
}

function getJSLocation(files) {
    if (files.constructor !== Array)
        return jsAssetLocation + "/" + files;

    for (var i = 0; i < files.length; i++)
        files[i] = jsAssetLocation + "/" + files[i];
    return files;
}