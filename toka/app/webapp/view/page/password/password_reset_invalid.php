<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Toka</title>
    <?php include_once('common/header.php') ?>
     <style>    
    #pr-alert {
        display: none;
    }
    #pr-form-container {
        max-width: 700px; 
        margin: 20px auto; 
        padding: 20px 20px 20px 20px; 
        border: 1px #ddd solid; 
        border-radius: 4px;
    }
    #pr-form-container .message {
        text-align: center;
        margin-bottom: 40px;
    }
    #pr-form {
        max-width: 600px; 
        margin: auto;
    }    
    #toka-msg {
        text-align: center;
        margin-bottom: 20px;
        background-color: #252525;
        color: #fff;
        padding: 50px 0 50px 0;
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
        <section id="site-left-nav">
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <h2 id="toka-msg">Whoa!</h2>
            <div style="max-width:700px; margin:auto; padding:20px; border:1px #ddd solid; border-radius:4px;">                
                <div style="width:100px; height:100px; margin:30px auto 40px auto; background-color:#ff7d1e; border-radius:50%;">
                    <img style="width: 50px; margin-left: 25px; margin-top: 20px;" src="/assets/images/icons/lock.svg" />
                </div>
                <p style="margin-bottom:20px;">The request is invalid, please contact <a href="mailto:support@toka.io">support@toka.io</a> or chat with us in the <a href="http://toka.io/chatroom/toka" target="_blank">Toka Developer Chatroom</a> to generate a new activation code.</p>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('form/login.php') ?>
            <?php include_once('form/signup.php') ?> 
        </section>
    </div>
</body>
</html>