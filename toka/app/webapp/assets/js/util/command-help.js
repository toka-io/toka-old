"use strict"

function CommandHelp(container, input) {
    this.container = container;
    this.input = input;
    this.app;
    this.active;
    this.autocomplete = true;
    this.commands = {
            '/define': { 
                options: "[word]",
                desc: "Define a word"
            },
            '/me': {
                options: "[text]",
                desc: "Perform an action"
            },
            '/spoiler': {
                options: "[text]",
                desc: "Hide text in a spoiler block"
            },
            '/urban': {
                options: "[word]",
                desc: "Find the urban dictionary definition of a word"
            },
            '/view': {
                options: "[room]",
                desc: "Peek into a room by providing a chatroom id"
            }
    };
};
CommandHelp.prototype.ini = function() {
    var self = this;
    
    self.app = $("<div></div>", {
        'class': 'commands-help',
        'style': 'display: none;'
    }).append($("<div></div>", {
        'class': 'commands-top',
        'html': '<span>Commands</span>'
    })).append($("<ul></ul>", {
        'class': 'commands-list'
    }))
    
    self.container.append(self.app);
    
    for (var key in self.commands) {
        if (self.commands.hasOwnProperty(key)) {
            self.app.find('.commands-list').append($("<li></li>", {
                'html': '<span class="command">' + key + ' </span><span class="command-options">' + self.commands[key].options + '</span><span class="command-desc">' + self.commands[key].desc +  '</span>'
            }))
        }
    }
    self.app.find(".commands-list li:first").addClass("selected");

    self.app.find("li").on('click', function() {
        self.loadCommand($(this));
    });
    
    self.input.on('keyup', function(e) {
        var command = self.input.val(); 
        
        if (command === "/") {
            self.app.show();
            self.active = true;
            $(self.input).addClass("commandActive");
            $(self.app).css("bottom", $(self.input).outerHeight());
        }
        else if (command == "") {
            self.hide();
        }
        
        if (self.active) {
            if (e.which == 27) {
                self.hide();
                return;
            }
            else if (self.active && e.which == 38) {
                var $selected  = self.app.find("li.selected");
                
                if ($selected.prev().length) {
                    $selected.removeClass("selected");
                    $selected.prev().addClass("selected");
                }
                
                return;
            }
            else if (e.which == 40) {
                var $selected  = self.app.find("li.selected");
                
                if ($selected.next().length) {
                    $selected.removeClass("selected");
                    $selected.next().addClass("selected");
                }
                
                return;
            }
            else if (e.which == 13) {
                var $selected  = self.app.find("li.visible.selected");
                
                if ($selected.length != 0) {                
                    e.preventDefault();           
                    self.loadCommand($selected);
                }
                
                return;
            }
            
            self.app.find("li").filter(function () {
                $(this).show();
                $(this).addClass("visible");
                $(this).removeClass("selected");
                
                var commandListItem = $(this).text();
                if (command != commandListItem.substr(0, command.length)) {
                    $(this).hide();
                    $(this).removeClass("visible");
                }
                
            });
            var $selected = self.app.find("li.visible:first").addClass("selected");

            if ($selected.length == 0)
                self.autocomplete = false;
            else
                self.autocomplete = true;
        }
    });
}
CommandHelp.prototype.hide = function() {
    var self = this;
    $(self.app).hide();
    self.active = false;
    $(self.input).removeClass("commandActive");
}
CommandHelp.prototype.loadCommand = function($command) {
    var self = this;
    var command = $command.find(".command").text();        
    self.input.val(command);
    self.hide();;
}
CommandHelp.prototype.sendReady = function() {
    var self = this;
    return !self.active || (self.active && !self.autocomplete);
}
