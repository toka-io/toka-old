<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Toka</title>
    <?php include_once(__DIR__ . '/../common/header.php') ?>
    <style>
    #toka-welcome-msg {
        text-align: center;
        margin-bottom: 40px;
        background-color: rgba(0,0,0,0.8);
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
             <?php include_once(__DIR__ . '/../common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <h2 id="toka-welcome-msg">500</h2>
            <div class="container-fluid">
                <div class="div-center">
                    <div id="img404"><img src="/assets/images/icons/globe_g.svg" /></div>
                    <div id="msg404"><span class="label label-warning">500</span><h3>RIP Toka D:</h3></div>
               </div>
            </div>       
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../form/login.php') ?>  
        </section>
    </div>
</body>
</html>