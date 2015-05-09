<?php 
require_once(__DIR__ . '/../../common/session.php');

// Require any other services you need aka 
// require_once(__DIR__ . '/../../../../service/CategoryService.php');

// Add any code to retrieve data here
// Manipulate the view below using php script <?php > tags
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Toka</title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	// Any other "on DOM ready" functions below
    	toka = new Toka();
    	toka.ini();
    });        
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once(__DIR__ . '/../../common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-subtitle">
                <!-- subtitle goes here -->
            </section>
            <section id="site-alert">
                <!-- alert goes here -->
            </section>
            <div id="wrapper">
                <!-- content goes here -->
            </div>
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/site.php') ?>
        </section>
    </div>
</body>
