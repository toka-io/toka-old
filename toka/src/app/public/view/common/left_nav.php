<?php
include_once(__DIR__ . '../session.php');

require_once(__DIR__ . '/../../../model/ChatroomModel.php');

$data = $identityService->getChatroomsByOwner($user); // Get chatrooms owned by user
$hasMaxChatroom = $identityService->hasMaxChatrooms($user); // Can user create more chatrooms?
$hasChatroom = false; // Does user have a chatroom?
$userChatroom = new ChatroomModel();

if (!empty($data)) {
    $mongoObj = $data["0"];
    $userChatroom->bindMongo($mongoObj);
    $hasChatroom = true;
}
?>

<!-- Left Sidebar -->
<div class="toka-sidebar">
<?php
if (isset($_COOKIE['sessionID']) && isset($_COOKIE['username'])) {
    if (file_exists('/../../assets/images/users/'.$_COOKIE['username'].'.png')) {
       $userPic = $_COOKIE['username'].'.png';
    } else {
       $userPic = 'default.svg';
    }
?>
    <!-- Profile -->
    <div id="profile">
        <a>			
    		<div id="profile-picture">
    			<img id="profile-picture-img" src="/assets/images/users/<?php echo $userPic ?>"/>								
    		</div>			
    		<div id="profile-username">
    			<p>						
    				<?php echo $_COOKIE['username'] ?><b class="caret"></b>
    			</p>
    			<span class="list arrow"></span>
    		</div>
    	</a>		
    	<ul id='profile-menu' class='toka-sidebar-inner toka-sidebar-closed'>
    		<!-- Profile -->
    		<li>
    			<a href="/profile/<?php echo $_COOKIE['username'] ?>">
    				<img src="/assets/images/icons/user.svg"/><span>Profile</span>
    			</a>
    		</li>
    		<!-- Settings -->
    		<li>
    			<a href='/settings'>
    				<img src="/assets/images/icons/settings.svg"/><span>Settings</span>
    			</a>
    		</li>
    		<!-- Log Out -->
    		<li>
    			<a href='/logout'>
    				<img src="/assets/images/icons/lock.svg"/><span>Log Out</span>
    			</a>
    		</li>
    	</ul>
    </div>
<?php
}
?>

    <!-- Categories -->
    <ul id="action-menu">
<?php if ($identityService->isUserLoggedIn($user) && !$hasMaxChatroom) { 
?>                              
        <li>
        	<a data-toggle="modal" data-target="#create-chatroom-form" style='padding: 10px 20px;display: block;'>
        		<img src="/assets/images/icons/add.svg"/><span>Create Chatroom</span>
        	</a>
        </li>
<?php
}
if ($identityService->isUserLoggedIn($user) && $hasChatroom) {
?>
        <li>
        	<a href="/chatroom/<?php echo $userChatroom->chatroomID; ?>" style='padding: 10px 20px;display: block;'>
        		<img src="/assets/images/icons/home.svg"/><span>My Chatroom</span>
        	</a>
        </li>
<?php
}
?>
        <li>
        	<a href="/category" style='padding: 10px 20px;display: block;'>
        		<img src="/assets/images/icons/categories.svg"/><span>Categories</span>
        	</a>
        </li>
<!-- Share -->
<!--     		<li> -->
<!--      			<a id="sidebar-share" href="#" style='padding: 10px 20px;display: block;'> -->
<!--     				<img src="/assets/images/icons/connect.svg"/><span>Share</span> -->
<!--     			</a> -->
<!--     		</li> -->
    	<!-- Random -->
    	<li>
    		<a id="sidebar-random" href="#" style='padding: 10px 20px;display: block;'>
    			<img src="/assets/images/icons/random.svg"/><span>Random Room</span>
    		</a>
    	</li>
	</ul>
	<div id="toka-footer">
        <div id="footer-quick-links">
            <a href="/faq">FAQ</a> · <a>About Us</a> · <a>Terms</a>
        </div>
        <div id="copyright">
            <span>Toka © 2015</span>
        </div>
	</div>
</div>