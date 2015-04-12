	<!-- Left Sidebar -->
	<ul class='list-unstyled toka-sidebar' style=''>
		
		<!-- Profile -->
        <?php
        if (isset($_COOKIE['sessionID']) && isset($_COOKIE['username'])) {
            if (file_exists('/../../assets/images/users/'.$_COOKIE['username'].'.png')) {
               $userPic = $_COOKIE['username'].'.png';
            } else {
                $userPic = 'default.svg';
            }
        ?>
			<li>
				<a id='profile' class='toka-sidebar-profile'>
					<div>
						<img style='height:73.33px;max-width:100px;' src="/assets/images/users/<?php echo $userPic ?>"/>
						<p style='padding: 10px 0px; 0px 0px;'>
							Logged in as: <br />
							<?php echo $_COOKIE['username'] ?>
						</p>
						<img id='profile-img' style='height:16px;width:200%;right:15px;' src='/assets/images/icons/add.svg' />
					</div>
				</a>
			</li>
			<div id='profile-tabs' class='toka-sidebar-inner toka-sidebar-closed'>
				<!-- Profile -->
				<li>
					<a href='/user/<?php echo $_COOKIE['username'] ?>' style='padding: 10px 10px;display: block;'>
						<img style='height:16px;' src="/assets/images/icons/user.svg"/> Profile
					</a>
				</li>
				<!-- Settings -->
				<li>
					<a href='/settings' style='padding: 10px 10px;display: block;'>
						<img style='height:16px;' src="/assets/images/icons/settings.svg"/> Settings
					</a>
				</li>
				<!-- Help -->
				<li>
					<a href='/chatroom/toka' style='padding: 10px 10px;display: block;'>
						<img style='height:16px;' src="/assets/images/icons/info.svg"/> Help
					</a>
				</li>
				<!-- Log Out -->
				<li>
					<a href='/logout' style='padding: 10px 10px;display: block;'>
						<img style='height:16px;' src="/assets/images/icons/lock.svg"/> Log Out
					</a>
				</li>
			</div>
		<?php
        } else {
        ?>
			<p class="navbar-btn">
				<a href="#" id="login-page" class="btn toka-button" data-toggle="modal" data-target="#login-form">Log In</a>
				<a href="#" id="signup-page" class="btn toka-button" data-toggle="modal" data-target="#signup-form">Sign Up</a>
			</p>
		<?php
        }
        ?>
		<!-- Categories -->
		<li>
			<a id="category-all" href="/category" style='padding: 10px 20px;display: block;'>
				<img style='height:16px;' src="/assets/images/icons/categories.svg"/> Categories
			</a>
		</li>
		<?php
        if (isset($chatroom)) {
        ?>
			<!-- Share -->
			<li>
				<a id="sidebar-share" href="#" style='padding: 10px 20px;display: block;'>
					<img style='height:16px;' src="/assets/images/icons/connect.svg"/> Share
				</a>
			</li>
		<?php
        }
        ?>
		<!-- Random -->
		<li>
			<a id="sidebar-random" href="#" style='padding: 10px 20px;display: block;'>
				<img style='height:16px;' src="/assets/images/icons/random.svg"/> Random Room
			</a>
		</li>
	</ul>