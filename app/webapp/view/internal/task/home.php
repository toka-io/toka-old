<?php 
require_once('common/session.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>TokaTask</title>
    <?php include_once('common/header.php') ?>
	<link rel="stylesheet" href="/assets/css/src/task.css"/>
	<script src="/assets/js/src/task.js"></script>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	// Any other "on DOM ready" functions below
    	toka.ini();
		task.ini(<?= json_encode($tasks); ?>);
    });        
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once('common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left-nav.php') ?>
        </section>
        <section id="site-content">
			<ul id="tasks">
				<div class="timeline"></div>
			</ul>
        </section>
        <section id="site-modals">
            <!-- <?php include_once('form/site.php') ?> -->
        </section>
    </div>
</body>
</html>