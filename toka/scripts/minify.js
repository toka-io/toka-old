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
if (option == "-d")
    options = {
        output : {
            beautify : true
        },
        compress : false
    };
else
    options = {};

// Compile toka.js
var input = getJSLocation([ 'util/autocomplete.js', 'util/command-help.js',
        'src/toka.js' ]);

var result = UglifyJS.minify(input, options);
fs.writeFileSync(getJSLocation('toka.min.js'), result.code);

// Compile tokabot.js
var input = getJSLocation([ 'src/tokabot.js' ]);
var result = UglifyJS.minify(input, options);
fs.writeFileSync(getJSLocation('tokabot.min.js'), result.code);

// Compile chatroom-list-app.js
var input = getJSLocation([ 'src/chatroom-list-app.js' ]);
var result = UglifyJS.minify(input, options);
fs.writeFileSync(getJSLocation('chatroom-list-app.min.js'), result.code);

// Compile chatroom-app.js
var input = getJSLocation([ 'src/chatroom-app.js' ]);
var result = UglifyJS.minify(input, options);
fs.writeFileSync(getJSLocation('chatroom-app.min.js'), result.code);

// Compile settings-app.js
var input = getJSLocation([ 'src/settings-app.js' ]);
var result = UglifyJS.minify(input, options);
fs.writeFileSync(getJSLocation('settings-app.min.js'), result.code);

/*******************************************************************************
 * Minify CSS Files
 ******************************************************************************/
var css = uglifycss.processFiles(getCSSLocation([ 'toka.css' ]), {
    maxLineLen : 0,
    expandVars : true
});

fs.writeFileSync(getCSSLocation('toka.min.css'), css);

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