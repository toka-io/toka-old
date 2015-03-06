<?php
// Maybe add an alias and require that alias file, then require using the name from the alias file...to have encapsulation
require_once(__DIR__ . '/../../../service/IdentityService.php');

$identityService = new IdentityService();

$request = array();

if (isset($_GET['login']))
    $request['login'] = $_GET['login'];
if (isset($_GET['v_code']))
    $request['v_code'] = $_GET['v_code'];

$response = array();

$response = $identityService->activateUser($request, $response);
?>
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
    #toka-welcome ul li {
        float: left; 
        border: 1px #eee solid; 
        width: 200px;
        height: 300px;
        border-radius:10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    #toka-welcome ul li .image {
        margin: auto;
        padding: 50px;
        height: 100px; 
        width: 100px;
        background-color:#ff7d1e; 
        border-radius:50%;
    }
    #toka-welcome ul li img {
        width: 50px;
        margin-left: -25px;
        margin-top: -25px;
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
             <nav class="navbar navbar-default" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/"><img class="toka-menu-logo" src="/assets/images/logo/toka_logo_150ppi.png" /></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a id="category-all" href="/category" class="toka-menu-item"><img src="/assets/images/icons/categories.svg" class="menu-icon" />Categories</a>
                        </li>
                        <li><a id="search-page" href="#" class="toka-menu-item"><img src="/assets/images/icons/search.svg" class="menu-icon" />Search</a></li>
                        <li><span id="alpha-test-info" style="display:block; padding:15px; color:#fff" class="toka-menu-item"><img src="/assets/images/icons/info.svg" class="menu-icon" />The application will be under maintenance throughout the weekend 2/27-2/29. Apologies beforehand if something isn't working!~     -Arc</span></li>
                    </ul>    
                </div><!-- /.navbar-collapse -->
            </nav>   
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
<?php 
if ($response['status'] === "0") {
?>
            <div style="max-width:700px; margin:auto; padding:20px; border:1px #ddd solid; border-radius:4px;">
                <h2 id="toka-welcome-msg">Whoa!</h2>
                <div style="width:100px; height:100px; margin:0px auto 40px auto; background-color:#ff7d1e; border-radius:50%;">
                    <img style="width: 50px; margin-left: 25px; margin-top: 20px;" src="/assets/images/icons/lock.svg" />
                </div>
                <p style="margin-bottom:20px;">The verification code is invalid, please contact <a href="mailto:support@toka.io">support@toka.io</a> or chat with us in the <a href="http://toka.io/chatroom/1" target="_blank">Toka Developer Chatroom</a> to generate a new activation code.</p>
            </div>
<?php
} else {
?>
            <div id="toka-welcome" style="width:100%;">                
                <h2 id="toka-welcome-msg">Welcome to Toka, <?php echo $request['login']; ?>!</h2>
                <ul style="list-style:none; max-width:840px; margin:auto;">
                    <li style="margin-right: 100px;">
                        <div class="image">
                            <img src="/assets/images/icons/home.svg" />
                        </div>
                        <div>
                            <div style="text-align:center"><h3><b>Create</b></h3></div>
                            <div>Build a chatroom that's right for you.</div>
                        </div>
                    </li>
                    <li style="margin-right: 100px;">
                        <div class="image">
                            <img src="/assets/images/icons/connect.svg" />
                        </div>
                        <div>
                            <div style="text-align:center"><h3><b>Discover</b></h3></div>
                            <div>Take a step into the unknown and broaden your network with other chatroom communities.</div>
                        </div>
                    </li>
                    <li>
                        <div class="image">
                            <img src="/assets/images/icons/chat.svg" />
                        </div>
                        <div>
                            <div style="text-align:center"><h3><b>Chat</b></h3></div>
                            <div>Don't be shy! Meeting people on Toka is always like a new beginning.</div>
                        </div>
                    </li>
                </ul>
            </div>
<?php    
}
?>
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
    </div>
</body>
</html>