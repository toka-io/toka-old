var UglifyJS = require('uglify-js');
var fs = require('fs');

// Compile toka.js
var input = ['../util/autocomplete.js', '../util/command-help.js', '../src/toka.js']
var result = UglifyJS.minify(input, {
    compress: false
});
fs.writeFileSync('../toka.min.js', result.code);

// Compile tokabot.js
var input = ['../src/tokabot.js']
var result = UglifyJS.minify(input, {
    compress: false
});
fs.writeFileSync('../tokabot.min.js', result.code);

// Compile chatroom-list-app.js
var input = ['../src/chatroom-list-app.js']
var result = UglifyJS.minify(input, {
    compress: false
});
fs.writeFileSync('../chatroom-list-app.min.js', result.code);

//Compile chatroom-app.js
var input = ['../src/chatroom-app.js']
var result = UglifyJS.minify(input, {
    compress: false
});
fs.writeFileSync('../chatroom-app.min.js', result.code);

//Compile settings-app.js
var input = ['../src/settings-app.js']
var result = UglifyJS.minify(input, {
    compress: false
});
fs.writeFileSync('../settings-app.min.js', result.code);
