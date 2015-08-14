<?php
include_once('common/session.php');
//header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>404 - Toka</title>
    <?php include_once('common/header.php') ?>
    <style>
    #toka-welcome-msg {
        text-align: center;
        margin-bottom: 40px;
        background-color: #252525;
        color: #fff;
        padding: 50px 0 50px 0;
    }
    #img404 {
        width: 100px;
        margin: 30px auto 0px auto;
    }
    #msg404 {
        text-align: center;
        margin: auto;
    }
    </style>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    });        
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once('common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-alert">
            </section>
            <h2 id="toka-welcome-msg">404</h2>
            <div class="container-fluid">
                <div class="div-center">
                    <div id="img404"><img width="100px" src="/assets/images/icons/404.png" /></div>
                    <div id="msg404"><span class="label label-warning">404</span><h3>Navigating a site is easy--even <a title="Cirno" href="http://en.touhouwiki.net/wiki/Cirno" target="_blank">Cirno</a> could do it.</h3></div>
               </div>
            </div>       
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
        <section id="site-forms">
            <?php include_once('form/login.php') ?>  
        </section>
    </div>
</body>
</html>