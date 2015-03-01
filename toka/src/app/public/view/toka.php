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
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();        
        toka.iniSockets();
        
    	var prop = {};
    	prop["category_name"] = "Popular";
    	var popularCategory = new Category(prop);
    	popularCategory.getChatrooms();
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
        <section id="site-alert">
        </section>
        <section id="site-content">   
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
        <section id="site-forms">
            <?php include_once('form/login.php') ?>
            <?php include_once('form/signup.php') ?>  
        </section>
    </div>
</body>
</html>