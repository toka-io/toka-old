<div id="toka-sidebar">
    <?php
    if (IdentityService::isUserLoggedIn()) {
        if (file_exists('/../../assets/images/users/'.$_COOKIE['username'].'.png')) {
           $userPic = $_COOKIE['username'].'.png';
        } else {
           $userPic = 'default.svg';
        }
    ?>
    <div id="profile">
        <a>			
    		<div id="profile-picture">
    			<img id="profile-picture-img" src="/assets/images/temp/users/<?php echo $userPic ?>"/>								
    		</div>			
    		<div id="profile-username">
    			<p>						
    				<?php echo $user->username ?><b class="caret"></b>
    			</p>
    			<span class="list arrow"></span>
    		</div>
    	</a>		
    	<ul id='profile-menu' class='closed'>
    		<li class="item">
    			<a href="/profile/<?php echo $user->username ?>">
    				<img src="/assets/images/icons/user.svg"/><span>Profile</span>
    			</a>
    		</li>
    		<li class="item">
    			<a href="/settings">
    				<img src="/assets/images/icons/settings.svg"/><span>Settings</span>
    			</a>
    		</li>
    		<li class="item">
    			<a href="/logout">
    				<img src="/assets/images/icons/lock.svg"/><span>Log Out</span>
    			</a>
    		</li>
    	</ul>
    </div>
    <?php
    }
    ?>
        <div id="action-menu">
            <ul>
    <?php if (IdentityService::isUserLoggedIn()) { 
    ?>   
            <li class="item">
            	<a id="chatfeed-btn">
            		<img src="/assets/images/icons/myrooms.svg"/><span>@<?php echo $user->username; ?></span>
            	</a>
            </li>
    <?php
    }
     if (IdentityService::isUserLoggedIn() && !$user->hasMaxChatrooms) { 
    ?>                              
            <li class="item">
            	<a data-toggle="modal" data-target="#create-chatroom-form">
            		<img src="/assets/images/icons/add.svg"/><span>Create Chatroom</span>
            	</a>
            </li>
    <?php
    }
    if (IdentityService::isUserLoggedIn() && $user->hasChatrooms) {
    ?>
            <li class="item">
            	<a href="/chatroom/<?php echo $user->homeChatroom->chatroomId; ?>">
            		<img src="/assets/images/icons/home.svg"/><span>My Chatroom</span>
            	</a>
            </li>
    <?php
    }
    ?>
        	<li class="item">
        		<a href="#">
        			<img src="/assets/images/icons/random.svg"/><span>Random Room</span>
        		</a>
        	</li>
    	</ul>
	</div>
	<?php if (IdentityService::isUserLoggedIn()) { ?>
	<div id="recent-channels" class="section">
	   <div class="title"><img src="/assets/images/icons/otherrooms.svg"/><span>Channels</span></div>
	   <ul>
	       <?php
	           foreach ($user->recentRooms as $room) {
	               echo '<li><a href="/chatroom/'.$room['link'].'"># '.$room['name'].'</a></li>';
	           }
	       ?>
	   </ul>
	</div>
	<?php } ?>
	<div id="toka-footer">
        <div id="footer-quick-links">
            <a href="/faq">FAQ</a> · <a>About Us</a> · <a>Terms</a>
        </div>
        <div id="copyright">
            <span>Toka © 2015</span>
        </div>
	</div>
</div>