<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Leefter's Profile</title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    });
    </script>
    <link rel="stylesheet" href="/assets/css/LeefterProfile.css">
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
                <div class="default-subtitle">Save Avalon!</div>
                <div class="background-color">
                    <div class="profile_name">Leefter</div>
                    <div class="profile_styling">
                        <div class="info_section">Lo traveller, I am Sir Leefter. I hail from the future 50 years from now. The king, Arc, has sent me to save his beautiful wife, Saber, who has been underhandedly taken from Arc by bob620. I cannot post a photo of myself on here and I caution you to not because bob620 has already sent his underlings known as bobbots that traverse these pages and gather valuable information for bob. If you wish to further read my plea for help, click on "Adventure".
                        </div>
                    </div>
                    <!-- Cool Nav Bar -->
                    <nav id="menu">
                        <ul>
                            <li class="rocket"><a href="https://toka.io">Go Home</a></li>
                            <li class="wine"><a href="">Friends</a></li>
                            <li class="burger"><a href="">Social</a></li>
                            <li class="comment"><a href="">My Chatroom</a></li>
                            <li class="sport" ><a href="">Games</a></li>
                            <li class="earth"><a href="#adventure">Adventure</a></li>
            
                        <div class="current">
                            <div class="top-arrow"></div>   
                            <div class="current-back"></div>
                            <div class="bottom-arrow"></div>
                        </div>
                        </ul>
                    </nav>
                    <div class="profile_styling">
                        <div id="adventure">Welcome, brave warrior. Mark today on your calendar as it is an important date in which we depart on our adventure to stop the evil mage, Bob620. The evil king and Cirno has already started their invasion of Toka. If you navigate to a non existent page then you will be greeted by a mocking Cirno. She must be stopped. <img src="http://www.tshirtvortex.net/wp-content/uploads/Dangerous-Take-This-Sword-Link-Legend-of-Zelda-T-Shirt-sq.jpg"></div>
                    </div>
                </div>
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
