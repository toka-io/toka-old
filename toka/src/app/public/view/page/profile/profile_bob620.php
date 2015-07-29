<?php include_once(__DIR__ . '/../../common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Bob620's Profile</title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
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
             <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once(__DIR__ . '/../../common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-subtitle">
                <div class="default-subtitle">Profiles Coming Soon!</div>                
                <h1>Bob620</h1>
            </section>
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/site.php') ?>
        </section>
    </div>
</body>
</html>