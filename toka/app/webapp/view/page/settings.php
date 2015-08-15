<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
	<title><?php echo $user->username . ' - Settings'; ?></title>
	<?php include_once('common/header.php') ?>
	<script src="/assets/js/profile_settings.js"></script>
	<link rel="stylesheet" href="/assets/css/profile_settings.css" />
	<script>
	/* DOM Ready */
	$(document).ready(function() {
		toka = new Toka();
		toka.ini();

		settings = new Settings();
		settings.ini();
	});
	</script>
</head>
<body>
	<div id="site">
		<section id="site-menu">
			 <?php include_once('common/menu.php') ?>
		</section>
		<section id="site-left-nav">
			<?php include_once('common/left_nav.php') ?>
		</section>
		<section id="site-content">
			<section id='site-subtitle'>
				<div class='toka-settings-bar'>
					<ul class="toka-settings-buttons">
						<li><a id='settings-general' class='toka-settings-bar-active'><img style='height:16px;' src="/assets/images/icons/settings.svg"/> General</a></li>
						<li><a id='settings-email' class='toka-settings-bar-inactive'><img style='height:16px;' src="/assets/images/icons/email.svg"/> Email</a></li>
						<li><a id='settings-billing' class='toka-settings-bar-inactive'><img style='height:16px;' src="/assets/images/icons/info.svg"/> Billing</a></li>
					</ul>
				</div>
			</section>
			<div class='toka-settings-body'>
				<div id='settings-body-general' class='toka-settings-body-div toka-settings-body-active'>
					<div class='toka-settings-subtitle'>
						General
					</div>
					<div class='toka-settings-settings'>
						<ul class='list-unstyled'>
							<li>
								<h3>Chat Audio Notifications</h3>
								<a id='settings-chat-notifications-on' class='settings-button-active'>
									On
								</a>
								<a id='settings-chat-notifications-off' class='settings-button-inactive'>
									Off
								</a>
							</li>
							<li>
								<a href='#' class='settings-button-inactive'>
									Change Password
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div id='settings-body-email' class='toka-settings-body-div toka-settings-body-inactive'>
					<div class='toka-settings-subtitle'>
						Email
					</div>
					<div class='toka-settings-settings'>
						<ul class='list-unstyled'>
							<li>
								<h3>Email Notifications</h3>
								<a id='settings-email-notifications-on' class='settings-button-active'>
									On
								</a>
								<a id='settings-email-notifications-off' class='settings-button-inactive'>
									Off
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div id='settings-body-billing' class='toka-settings-body-div toka-settings-body-inactive'>
					<div class='toka-settings-subtitle'>
						Billing
					</div>
					<div class='toka-settings-settings'>
						:P
					</div>
				</div>
			</div>
		</section>
		<section id="site-forms">
			<?php include_once('common/site.php') ?>
		</section>
	</div>
</body>
</html>