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

        $('#profile-at').mCustomScrollbar({
            theme: "dark",
            alwaysShowScrollbar: 1,
            mouseWheel:{ scrollAmount: 240, normalizeDelta: true,},
            callbacks: {
                whileScrolling: function() {
                    self.autoScroll = false;
                    
                    if (this.mcs.topPct >= 99.5)
                        self.autoScroll = true;
                }
            }
        });
    });
    </script>
    <style>
        #site-profile {
            padding: 0px;
            margin: 0px;
        }
        #profile-at {
            padding: 5px;
            width: 330px;
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
            left: 330px;
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
            width: 95%;
            position: relative;
            left: 10px;
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
                    <div class="at-message">
                        <div class="at-message-name chatroom-user-name">bob620</div>
                        <div class="tokabot-normal-other-msg"><span style="background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold">@bob620</span> First I was thinking of adding a personal/other thing like in chat, but decided that all @s in this case should be presented as to you and not from you for ease of reading and logic!</div>
                    </div>
                    <div class="at-message">
                        <div class="at-message-name chatroom-user-name">bob620</div>
                        <div class="tokabot-normal-other-msg"><span style="background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold">@bob620</span> test!</div>
                    </div>
                    <div class="at-message">
                        <div class="at-message-name chatroom-user-name">arc</div>
                        <div class="tokabot-normal-other-msg"><span style="background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold">@bob620</span> test!</div>
                    </div>
                    <div class="at-message">
                        <div class="at-message-name chatroom-user-name">bob620</div>
                        <div class="tokabot-normal-other-msg"><span style="background-color: rgba(20, 24, 27, 0.5); color: white; border-radius: 4px; padding: 2px; font-weight: bold">@bob620</span> test!</div>
                    </div>
                </section>
                <section id="profile-pic">
                    <div class="picture">
                        <img id="site-profile-picture" src="http://www.bobco.moe/assets/images/touhou/cirno.png"/>
                    </div>
                    <div id="profile-buttons">
                        <a class="profile-button-active" href="/chatroom/j9YTF02CGcQ">Chatroom</a>
                        <a class="profile-button-active">Send a PM</a>
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
            <?php include_once('form/site.php') ?>
        </section>
    </div>
</body>
</html>