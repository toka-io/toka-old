<?php 
if ($chatroom->chatroomId !== "dualchatroom") { 
?>
    <div class="chatroom-body">
        <div class="chatroom-chat-container">
            <ul class="chatroom-chat"></ul>
        </div>
    </div>
<?php 
} else {
?>
    <div class="chatroom-body">
        <div class="chatroom-chat-member-container" style="float:left;overflow:hidden;">
            <ul class="chatroom-chat-member"></ul>
        </div>
        <div class="chatroom-chat-visitor-container" style="float:right;overflow:hidden;">
            <ul class="chatroom-chat-visitor"></ul>
        </div>
    </div>
<?php    
}