"use strict"

/** jQuery Extensions **/
$.fn.sorted = function(customOptions) {
    var options = {
        reversed: false,
        by: function(a) {
            return a.text();
        }
    };
    $.extend(options, customOptions);
    var $data = $(this);
    var arr = $data.get();
    
    arr.sort(function(a, b) {
        var valA = options.by($(a));
        var valB = options.by($(b));
        
        if (options.reversed) {
            return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;
        } else {
            return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;
        }
    });
    return $(arr);
};

function ChatroomListApp() {
    this.sorted = false;
    
    this.ini = function(chatrooms) {
        var self = this;
        
        /* Load Toka Chatroom List */
        toka.setChatrooms(chatrooms);
        
        try {
            toka.socket = io.connect(toka.chata, {secure: true});    
            
            // Connection with chat server established
            toka.socket.on("connect", function() {
                console.log('Connection opened.');            
                toka.socket.emit("activeViewerCount");
            }); 
            
            // Retreive list of users for active chatrooms
            toka.socket.on("activeViewerCount", function(activeViewerCount) {
                for (var chatroomId in activeViewerCount) {
                    if (toka.chatrooms.hasOwnProperty(chatroomId))
                        toka.chatrooms[chatroomId].updateChatroomItemUsers(activeViewerCount[chatroomId]);
                }

                if (!self.sorted) {
                    self.sort();
                }
                self.sorted = true;
            });
            
            // Connect to chat server closed (Server could be offline or an error occurred or client really disconncted)
            toka.socket.on("disconnect", function() {
                console.log('Connection closed.');
            });
        }
        catch (err) {
            console.log('Could not connect to chata!');
        }
    }
    
    this.sort = function() {
        var $chatroomList = $("#chatroom-list");

        // clone applications to get a second collection
        var $data = $chatroomList.clone();
        
        // Add a data-attribute filter if you want to filter specific items
        var $filteredData = $data.find('li');
        
        var $sortedData = $filteredData.sorted({
            reversed: true,
            by: function(v) {
                return parseFloat($(v).find('.chatroom-item-users-count').text());
            }
        });
        
        $chatroomList.empty().append($sortedData);
    }
}