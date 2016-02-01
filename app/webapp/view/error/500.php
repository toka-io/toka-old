<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>500 - Toka</title>
    <?php include_once('common/header.php') ?>
    <style>
    #toka-welcome-msg {
        text-align: center;
        margin-bottom: 40px;
        background-color: #252525;
        color: #fff;
        padding: 50px 0 50px 0;
    }
    #img500 {
        width: 300px;
        margin: 30px auto 0px auto;
    }
    #img500 img {
        width: 300px;        
        border-radius: 50%;
        border: 2px #555 solid;
    }
    #msg500 {
        text-align: center;
        margin: auto;
    }
    </style>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
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
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <h2 id="toka-welcome-msg">500</h2>
            <div class="container-fluid">
                <div class="div-center">
                    <div id="img500"><img width="300px" src="/assets/images/error/500.gif" /></div>
                    <div id="msg500"><span class="label label-warning">500</span><h3>Ehh, did something break? We will fix it...after a few games.</h3></div>
               </div>
            </div>           
        </section>
        <section id="site-forms">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>