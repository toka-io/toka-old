<!-- Modal -->
<div class="modal fade" id="create-chatroom-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Chatroom</h4>
            </div>
            <div class="modal-body">
                <!-- Text input-->
                <div class="form-group">
                    <label for="create-chatroom-title">Title</label>
                    <input type="text" name="Name" class="form-control" id="create-chatroom-title" placeholder="Chatroom Title">
                    <div class="help-block">Be creative!</div>
                </div>
                <!-- Text input-->
                <div class="form-group">
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
                        <option value="Popular">Popular</option>
                        <option value="Sports">Sports</option>
                        <option value="Startups">Startups</option>
                        <option value="Travel">Travel</option>
                        <option value="Trending">Trending</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="create-chatroom-tags">Tags</label>
                    <input type="text" name="tags" id="create-chatroom-tags" class="form-control" placeholder="Tags" />
                </div>              
            </div>
            <div class="modal-footer">
                <button id="createchatroom" class="btn btn-large btn-block btn-primary" type="button">Start your Chatroom</button>
            </div>
        </div>
    </div>
</div>