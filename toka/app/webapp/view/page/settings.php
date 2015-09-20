<?php include_once('common/session.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
	<title><?= $user->username . ' - Settings'; ?></title>
	<?php include_once('common/header.php') ?>	
	<link rel="stylesheet" href="/assets/css/settings.css" />
	<script src="/assets/js/settings-app.js"></script>
	<script>
	var settings;
	$(document).ready(function() {
		toka = new Toka();
		toka.ini();

		settings = new SettingsApp(<?= json_encode($userSettings); ?>);
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
				<div class='settings-bar'>
					<ul>
						<li><a id='settings-general' onclick="settings.settingsBar(this);" class='settings-orange'><img style='height:16px;' src="/assets/images/icons/settings.svg"/> General</a></li>
						<li><a id='settings-email' onclick="settings.settingsBar(this);"><img style='height:16px;' src="/assets/images/icons/email.svg"/> Email</a></li>
						<li><a id='settings-billing' onclick="settings.settingsBar(this);"><img style='height:16px;' src="/assets/images/icons/info.svg"/> Billing</a></li>
					</ul>
				</div>
			</section>
			<section class='settings-body'>
				<section id='settings-body-general' class='settings-active'>
					<div class='settings-subtitle'>
						General
					</div>
					<ul class="settings-settings">
					</ul>
				</section>
				<section id='settings-body-email'>
					<div class='settings-subtitle'>
						Email
					</div>
					<div class='settings-settings'>
						<ul>
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
				</section>
				<section id='settings-body-billing'>
					<div class='settings-subtitle'>
						Billing
					</div>
					<div class='settings-settings'>
						:P
					</div>
				</section>
			</section>
		</section>
		<section id="site-forms">
			<?php include_once('common/site.php') ?>
		</section>
	</div>
</body>
</html>