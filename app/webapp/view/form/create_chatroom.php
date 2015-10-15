<div class="modal fade" id="create-chatroom-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Chatroom (Max: 1)</h4>
            </div>
            <div class="modal-body">
                <section id="create-chatroom-alert">
                </section>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="create-chatroom-title">Title</label>
                    <input type="text" name="Name" class="form-control" id="create-chatroom-title" placeholder="Chatroom Title" />
                    <div class="help-block">Be creative!</div>
                </div>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="create-chatroom-category">Category</label>
                    <select id="create-chatroom-category" class="form-control">
                        <option value="0">Please Select a Category</option>
                        <option value="Anime">Anime</option>
                        <option value="Food">Food</option>
                        <option value="Gaming">Gaming</option>
                        <option value="Health">Health</option>
                        <option value="Movies and TV">Movies and TV</option>
                        <option value="Music">Music</option>
                        <option value="News">News</option>
                        <option value="Programming">Programming</option>
                        <option value="Sports">Sports</option>
                        <option value="Startups">Startups</option>
                        <option value="Travel">Travel</option>
                        <option value="Trending">Trending</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="help-block">What best idenitifies this chatroom?</div>
                </div>
                <div class="form-group" style="margin-bottom:25px">
                    <label for="create-chatroom-info">Information Page</label>
                    <textarea name="Name" class="form-control" id="create-chatroom-info" placeholder="Details Here" rows="6"></textarea>
                    <div class="help-block">Put any important rules, quick links, or information here!</div>
                </div>
                <div class="form-group">
                    <label for="create-chatroom-tags">Tags</label>
                    <div id="create-chatroom-tags-input">
                        <input type="text" name="tags" id="create-chatroom-tags" placeholder="tag" value="" />
                    </div>
                    <div class="help-block">Make it easier to find this chatroom! (Max: 5 | Use commas to separate tags "toka, chatroom, ..")</div>
                </div>
                <div id="create-chatroom-loader">
                    <div class="loading-wrapper">
                        <div class="loading"></div>
                        <div class="loading-message">Creating Chatroom...</div>
                    </div>
                </div>            
            </div>
            <div class="modal-footer">
                <button id="create-chatroom-btn" class="btn btn-large btn-block btn-primary" type="button">Chat</button>
            </div>
        </div>
    </div>
</div>
<script>
var createChatroomApp = new (function() {
    this.ini = function() {
        var self = this;
        
        $("#create-chatroom-tags-input input").tagsinput({
            tagClass: "chatroom-tag label label-info"
        });

        $("#create-chatroom-btn").off("click").on("click", function() {
            var chatroom = new Chatroom({});
            chatroom.chatroomName = $("#create-chatroom-title").val().trim();
            chatroom.categoryName = $("#create-chatroom-category").val();
            chatroom.info = $("#create-chatroom-info").val();
            
            try {
                chatroom.tags = $("#create-chatroom-tags-input input").val().replace(/[\s,]+/g, ',').split(",");
                
                // flatten tags to lowercase
                for (var i = 0; i < chatroom.tags; i++) {
                    chatroom.tags[i] = chatroom.tags[i].toLowerCase(); 
                }
            } catch (err) {
                chatroom.tags = [];
            }
            
            if (self.validateCreateChatroom(chatroom)) {
                self.createChatroom(chatroom);
            }
        });       
    }

    this.alertCreateChatroom = function(alertMsg) {
        var $alert = $("<div></div>", {
            "id" : "create-chatroom-alert-text",
            "class" : "alert alert-warning alert-dismissible",
            "text" : alertMsg
        }).append($("<button></button>", {
            "type" : "button",
            "class" : "close",
            "data-dismiss" : "alert",
            "aria-label" : "Close"
        }).append($("<span></span>", {
            "aria-hidden" : "true",
            "html" : "&times;"
        })));
        
        $("#create-chatroom-alert").empty().append($alert);
    };

    this.validateCreateChatroom = function(chatroom) {    
        if (chatroom.chatroomName === "") {
            this.alertCreateChatroom("Please provide a chatroom title.");
            return false;
        } if (chatroom.chatroomName.trim().length > 100) {
            this.alertCreateChatroom("Please keep chatroom titles limited to 100 characters.");
            return false;
        } else if (chatroom.categoryName === "0") {
            this.alertCreateChatroom("Please select a category.");
            return false;
        } else if (chatroom.tags.length > 5) {
            this.alertCreateChatroom("Please limit tags to 5.");
            return false;
        }
        
        return true;
    }

    this.createChatroom = function(chatroom) {
        var self = this;

        var username = toka.getCookie("username");
        
        if (username === "") {
            alertCreateChatroom("Cannot create chatroom! Please log in."); // Make this a better pop up
            return;
        }
        
        var data = {};
        data["categoryName"] = chatroom.categoryName;
        data["chatroomName"] = chatroom.chatroomName;
        data["info"] = chatroom.info;
        data["tags"] = chatroom.tags;
        
        var loadingOptions = {
            "beforeSend" : function() {
                $("#create-chatroom-loader").show();
            },
            "complete" : function() {
                $("#create-chatroom-loader").hide();
            }
        }
        
        $.ajax({
            url: "chatroom/create",
            type: "post",
            data: data,
            dataType: "json",
            beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
            complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
            success: function(response) {
                if (response["status"] !== 200) {
                    var statusMessage = response["message"];
                    statusMessage = statusMessage.charAt(0).toUpperCase() + statusMessage.slice(1);
                    self.alertCreateChatroom("Server Error: " + statusMessage);
                }
                else {
                    window.location.href = "/chatroom/" + response["chatroomId"];
                }
            }
        });
    };
})();
createChatroomApp.ini();
</script>