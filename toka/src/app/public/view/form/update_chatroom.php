<!-- Modal -->
<div class="modal fade" id="update-chatroom-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Chatroom</h4>
            </div>
            <div class="modal-body">
                <section id="update-chatroom-alert">
                </section>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-title">Title</label>
                    <input type="text" name="Name" class="form-control" id="update-chatroom-title" placeholder="Chatroom Title" />
                    <div class="help-block">Be creative!</div>
                </div>
                <!-- Text input-->
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-category">Category</label>
                    <select id="update-chatroom-category" class="form-control">
                        <option value="0">Please Select a Category</option>
                        <option value="Anime">Anime</option>
                        <option value="Food">Food</option>
                        <option value="Gaming">Gaming</option>
                        <option value="Health">Health</option>
                        <option value="Movies and TV">Movies and TV</option>
                        <option value="Music">Music</option>
                        <option value="News">News</option>
                        <option value="Programming">Programming</option>
                        <option value="Popular">Popular</option>
                        <option value="Sports">Sports</option>
                        <option value="Startups">Startups</option>
                        <option value="Travel">Travel</option>
                        <option value="Trending">Trending</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="help-block">What best idenitifies this chatroom?</div>
                </div>
                <div class="form-group" style="margin-bottom:25px">
                    <label for="update-chatroom-info">Information Page</label>
                    <textarea type="text" name="Name" class="form-control" id="update-chatroom-info" placeholder="Details Here" rows="6"></textarea>
                    <div class="help-block">Put any important rules, quick links, or information here! (Markup will be supported soon)</div>
                </div>
                <div class="form-group">
                    <label for="update-chatroom-tags">Tags (max: 5)</label>
                    <div id="update-chatroom-tags-input">
                        <input type="text" name="tags" id="update-chatroom-tags" placeholder="tag" value="" />
                    </div>
                    <div class="help-block">Make it easier to find this chatroom!</div>
                </div>
                <div id="update-chatroom-loader">
                    <div class="loading-wrapper">
                        <div class="loading"></div>
                        <div class="loading-message">Creating Chatroom...</div>
                    </div>
                </div>            
            </div>
            <div class="modal-footer">
                <button id="update-chatroom-btn" class="btn btn-large btn-block btn-primary" type="button">Chat!</button>
            </div>
        </div>
    </div>
</div>