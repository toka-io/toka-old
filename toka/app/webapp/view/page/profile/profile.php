<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?= $user->username . ' - Toka'; ?></title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    });
    </script>
    <style>
    .chatfeed {
        position: absolute;
        top: 250px;
        left: 1120px;
        max-height: 670px;
        border-top-right-radius: 12px;
        border-top-left-radius: 12px;
        border-bottom-right-radius: 6px;
        border-bottom-left-radius: 6px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.16);
        padding-top: 6px;
        background-color: #fff;        
    }
        .chatfeed h3 {
            padding: 0px 16px;
        }
        .chatfeed iframe {
            border-top: 1px solid #ddd;
            border-left: none;
            border-right: none;
            border-bottom: none;
            height: 600px;
            width: 350px;
        }
    .cover-photo {
        overflow: hidden;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        height: 400px;
    }
    .cover-photo img {
        position: absolute;
        left: 0;
        right: 0;
        top: -9999px;
        bottom: -9999px;
        margin: auto 0;
        min-width: 100%;
        height: auto;
    }
    .friend-list {
        overflow: hidden;
    }
    .friend-list .profile-pic {
        display: inline-block;
        margin: 0px 5px;
        order-radius: 2px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.16);
        padding: 6px 6px;
    }
    .profile {
        min-height: 100%;
        background-color: rgb(238, 238, 238);
    }
    .profile h3 {
        color: rgb(77, 87, 99);
        font-weight: 600;
        margin: 12.5px 0;
    }
    .info .panel {
        width: 100%;
        margin-left: 450px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.16);
        padding: 6px 16px;
        background-color: #fff;
    }
    .profile-head {
        position: absolute;
        top: 300px;        
        margin-left: 200px;
    }
    .profile-pic {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.16);
        padding: 6px 16px;
        margin-bottom: 20px; 
    }
        .profile-pic img {
            height: 150px;
            width: 150px;
        }
    .profile .info {
        position: absolute;
        top: 380px;
    }
    @media (min-width: 768px) {
        .info .panel {
            width: 600px;
        }
    }
    </style>
</head>
<body class="profile">
    <div id="site">
        <section id="site-menu">
             <?php include_once('common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <div class="profile">
                <div class="cover-photo-canopy">
                    <div class="cover-photo"><img src="/assets/images/temp/profile/cover.jpg" /></div>                    
                </div>
                <div class="profile-head">
                    <div class="profile-pic"><img src="/assets/images/temp/profile/profile.png" /></div>
                    <div class="panel"><b>@<?= $user->username ?></b></div>
                </div>
                <div class="info">
                    <div class="panel">
                        <h3>About Me</h3>
                        I am a somewhat fresh out the oven university student who enjoys playing games and listening music in his free time. Definitely feel free to chat if you have any questions or just want to chill. ^^
                    </div>
                    <div class="panel">
                        <h3>Friends</h3>
                        <div class="friend-list">
                            <div class="col-lg-4"><div class="profile-pic"><img src="/assets/images/temp/profile/arc.jpg" /></div></div>
                            <div class="col-lg-4"><div class="profile-pic"><img src="/assets/images/temp/profile/bob620.png" /></div></div>
                            <div class="col-lg-4"><div class="profile-pic"><img src="/assets/images/temp/profile/leefter.png" /></div></div>
                        </div>
                    </div>
                </div>
                <div class="chatfeed">
                    <h3>Chatfeed</h3>
                    <iframe src="/chatroom/<?= $user->username ?>?embed=4"></iframe>
                </div>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>