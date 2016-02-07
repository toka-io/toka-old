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
    <title>Toka</title>
    <?php include_once('common/header.php') ?>
    <script>
    function Aya() {
        var self = this;
    }

    Aya.prototype.listen = function() {
        toka.socket.on('message', function() {
        });
    }
    
    /* DOM Ready */
    $(document).ready(function() {
    	// Any other "on DOM ready" functions below
    	toka.ini();
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
        <section id="site-modals">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
