"use strict"

function Autocomplete(container, input) {
    this.container = container;
    this.input = input;
    this.app;
    this.active = false;
    this.startIndex = -1;
}
Autocomplete.prototype.ini = function() {
    var self = this;
    
    self.input.on('input', function(e) {
        var inputText = self.input.val();
        var recentChar = inputText.substr(inputText.length-1, inputText.length);
        
        console.log("User typed: " + recentChar);
        
        if (recentChar === "@") {
            self.active = true;
        }
        else if (self.active) {
            if (self.startIndex == -1)
                self.startIndex = inputText.length-1;
            
            var username = inputText.substr(self.startIndex);
            self.getUsernameMatches(username);
        }
    });
}
Autocomplete.prototype.getUsernameMatches = function(username) {
    var self = this;
    
    self.app = $("<div></div>", {
        'class': 'autocomplete-username',
        'style': 'display: none;'
    }).append($("<div></div>", {
        'class': 'title',
        'html': '<span>usernames matching:</span><span class="text"></span>'
    })).append($("<ul></ul>", {
        'class': 'ac-username-list'
    }))
    
    self.container.append(self.app);
    
    var loadingOptions = {};
    
    $.ajax({
        url: "/rs/user/search?u="+username,
        type: "GET",
        dataType: "json",
        beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
        complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
        success: function(response) {
            if (response["status"] !== 200) {
                console.log("Error!!");
            }
            else {
                self.app.show();
            }
        }
    });
}