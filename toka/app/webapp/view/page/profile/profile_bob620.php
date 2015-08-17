<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $username . ' - Toka'; ?></title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
        toka = new Toka();
        toka.ini();

        $('#site-profile').css("min-height", $('#site').height() - $('#site-menu').height() - $('#site-subtitle').height());
        $('#site-profile').css("height", $('#site-profile').height());
        $('#profile-desc').css("width", $('#site').width() - $('#profile-at').width() - $('#profile-pic').width() - $('#site-left-nav').width() - 10);
    });
    </script>
    <style>
        #site-profile {
            padding: 0px;
            margin: 0px;
        }
        #profile-at {
            padding: 0px;
            width: 350px;
            height: inherit;
            display: inline-block;
            position: absolute;
        }
        #profile-desc {
            padding: 10px;
            width: 33.33%;
            height: inherit;
            display: inline-block;
            position: absolute;
            left: 360px;
            overflow: hidden;
            background-repeat: repeat;
            background-image: url(http://www.bobco.moe/assets/images/cirno-flake-background_zpsij0psdwp.gif);
            color: white;
        }
        #profile-pic {
            width: 330px;
            height: inherit;
            background-color: rgba(0,0,0,0.82);
            display: inline-block;
            position: absolute;
            right: 0px;
        }
        .at-message-name {
            left: 20px;
            position: relative;
        }
        .at-message {
            width: 105%;
            height: 99%;
            position: relative;
        }
        #site-profile-picture {
            width: 200px;
            margin: 30px 65px;
        }
        .picture {
            max-height: 255px;
            overflow: hidden;
        }
        .profile-button-active, .profile-button-active:visited{
            padding: 10px 20px;
            min-width: 100px;
            display: inline-block;
            background-color: rgba(0,0,0,0.3);
            color: white;
            cursor: pointer;
            text-decoration: none !important;
        }
        #profile-buttons {
            padding: 10px 50px;
        }
        .profile-button-active:hover {
            color: white;
            background-color: #FF7D1E;
            cursor: pointer;
            display: inline-block;
            text-decoration: none !important;
        }
    </style>
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
            <section id="site-subtitle">
                <div class="default-subtitle">Bob620</div>
            </section>
            <section id="site-profile">
                <section id="profile-at">
                    <iframe class="at-message" src="https://www.toka.io/chatroom/bob620?embed=2"></iframe>
                </section>
                <section id="profile-pic">
                    <div class="picture">
                        <img id="site-profile-picture" src="http://www.bobco.moe/assets/images/touhou/cirno.png"/>
                    </div>
                    <div id="profile-buttons">
                        <a class="profile-button-active" href="/chatroom/j9YTF02CGcQ">Chatroom</a>
                        <a class="profile-button-active">Send a PM</a>
                        <br />
                        <a class="profile-button-active" style="position: relative; top: 10px; left: 55px; padding: 10px 30px;">Listen</a>
                    </div>
                </section>
                <section id="profile-desc">
                    <div>I am a very shy and awkward person who doesn't like to interact with anyone until he gets to know them. I'm also not much of a creative person but I love to write and code websites and games but mostly to know that it is impacting other people in a positive way.<br /><br />As for a job I am currently helping out create Toka and hope that it can become big enough to start hiring people and become a huge influence to as many people and communities as possible.<br /><br />Thanks to @FZTime from CrunchyRoll for making me this awesome background!</div>
                </section>
            </section>
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>
        </section>
        <section id="site-forms">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>