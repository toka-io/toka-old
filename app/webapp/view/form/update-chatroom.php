<!-- Modal -->
<div class="modal fade" id="update-chatroom-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update Chatroom</h4>
            </div>
            <div class="modal-body">
                <section id="update-chatroom-alert">
                </section>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-title">Title</label>
                    <input type="text" name="Name" class="form-control" id="update-chatroom-title" placeholder="Chatroom Title" value="<?php echo $chatroom->chatroomName; ?>" />
                    <div class="help-block">Be creative!</div>
                </div>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-category">Category</label>
                    <select id="update-chatroom-category" class="form-control">
                        <option <?php if ($chatroom->categoryName === "0" || empty($chatroom->categoryName)) echo "selected";  ?> value="0">Please Select a Category</option>
                        <option <?php if ($chatroom->categoryName === "Anime") echo "selected";  ?> value="Anime">Anime</option>
                        <option <?php if ($chatroom->categoryName === "Food") echo "selected";  ?> value="Food">Food</option>
                        <option <?php if ($chatroom->categoryName === "Gaming") echo "selected";  ?> value="Gaming">Gaming</option>
                        <option <?php if ($chatroom->categoryName === "Health") echo "selected";  ?> value="Health">Health</option>
                        <option <?php if ($chatroom->categoryName === "Movies and TV") echo "selected";  ?> value="Movies and TV">Movies and TV</option>
                        <option <?php if ($chatroom->categoryName === "Music") echo "selected";  ?> value="Music">Music</option>
                        <option <?php if ($chatroom->categoryName === "News") echo "selected";  ?> value="News">News</option>
                        <option <?php if ($chatroom->categoryName === "Programming") echo "selected";  ?> value="Programming">Programming</option>
                        <option <?php if ($chatroom->categoryName === "Sports") echo "selected";  ?> value="Sports">Sports</option>
                        <option <?php if ($chatroom->categoryName === "Startups") echo "selected";  ?> value="Startups">Startups</option>
                        <option <?php if ($chatroom->categoryName === "Travel") echo "selected";  ?> value="Travel">Travel</option>
                        <option <?php if ($chatroom->categoryName === "Trending") echo "selected";  ?> value="Trending">Trending</option>
                        <option <?php if ($chatroom->categoryName === "Other") echo "selected";  ?> value="Other">Other</option>
                    </select>
                    <div class="help-block">What best idenitifies this chatroom?</div>
                </div>
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-info">Information Page</label>
                    <textarea name="Name" class="form-control" id="update-chatroom-info" placeholder="Details Here" rows="6"><?php echo $chatroom->info; ?></textarea>
                    <div class="help-block">Put any important rules, quick links, or information here!</div>
                </div>
                <div class="form-group">
                    <label for="update-chatroom-tags">Tags (max: 5)</label>
                    <div id="update-chatroom-tags-input">
                        <input type="text" name="tags" id="update-chatroom-tags" placeholder="tag" value="<?php echo implode(",", $chatroom->tags); ?>" />
                    </div>
                    <div class="help-block">Make it easier to find this chatroom!</div>
                </div>
                <div id="update-chatroom-loader">
                    <div class="loading-wrapper">
                        <div class="loading"></div>
                        <div class="loading-message">Updating Chatroom...</div>
                    </div>
                </div>            
            </div>
            <div class="modal-footer">
                <button id="update-chatroom-btn" class="btn btn-large btn-block btn-primary" type="button">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
var updateChatroomApp = new (function() {
    this.ini = function() {
        var self = this;

        $("#update-chatroom-tags-input input").tagsinput({
            tagClass: "chatroom-tag label label-info"
        });
        
        $("#update-chatroom-btn").off("click").on("click", function() {
            var chatroom = toka.currentChatroom;
            chatroom.chatroomName = $("#update-chatroom-title").val().trim();
            chatroom.categoryName = $("#update-chatroom-category").val();
            chatroom.info = $("#update-chatroom-info").val();
            
            try {
                chatroom.tags = $("#update-chatroom-tags-input input").val().replace(/[\s,]+/g, ',').split(",");
                
                // flatten tags to lowercase
                for (var i = 0; i < chatroom.tags; i++) {
                    chatroom.tags[i] = chatroom.tags[i].toLowerCase(); 
                }
            } catch (err) {
                chatroom.tags = [];
            }
            
            if (self.validateUpdateChatroom(chatroom)) {
                chatroom.update();
            }
        });
    }

    this.alertUpdateChatroom = function(alertMsg) {
        var $alert = $("<div></div>", {
            "id" : "update-chatroom-alert-text",
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
        
        $("#update-chatroom-alert").empty().append($alert);
    };

    this.validateUpdateChatroom = function(chatroom) {
        if (chatroom.chatroomName === "") {
            this.alertUpdateChatroom("Please provide a chatroom title.");
            return false;
        } if (chatroom.chatroomName.trim().length > 100) {
            this.alertUpdateChatroom("Please keep chatroom titles limited to 100 characters.");
            return false;
        } else if (chatroom.categoryName === "0") {
            this.alertUpdateChatroom("Please select a category.");
            return false;
        } else if (chatroom.tags.length > 5) {
            this.alertUpdateChatroom("Please limit tags to 5.");
            return false;
        }
        
        return true;
    }
})();
updateChatroomApp.ini();
</script>