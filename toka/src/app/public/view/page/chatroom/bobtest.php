<?php 
require_once(__DIR__ . '/../../../../service/ChatroomService.php');
require_once(__DIR__ . '/../../../../model/ChatroomModel.php');

$request = array();
$response = array();

$chatroomService = new ChatroomService();

$request['data']['chatroomID'] = $chatroomService->getChatroomIDFromUrl(urldecode($_SERVER['REQUEST_URI']));
$response = $chatroomService->getChatroom($request, $response);

$mongoObj = $response['data'];

$chatroom = new ChatroomModel();
$chatroom->bindMongo($mongoObj);

if (empty($chatroom->chatroomName)) {
    $chatroom->chatroomID = $request['data']['chatroomID'];
    $chatroom->chatroomName = "#" . $request['data']['chatroomID'];
}
$chatroom->owner = "bob620";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $chatroom->chatroomName . ' - Toka'; ?></title>
    <!-- Common Header Links -->
    <!-- Need to put images and stuff in a cdn... -->
    
    <!-- Google App -->
    <meta name="google-site-verification" content="nX6SZU9KpPD2KbNOSizx0p2x9HZR0Y8o1-e8k-yZYOo" />
    
    <link rel="canonical" href="http://toka.io" />
    
    <!-- Facebook -->
    <meta property="og:title" content="Toka" />
    <meta property="og:image" content="http://toka.io/assets/images/logo/toka_logo_orange_300ppi.png" />
    <meta property="og:url" content="http://toka.io" />
    <meta property="og:description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything." />
    <meta property="og:type" content="website" />
    
    <!-- Twitter -->
    <meta name="twitter:title" content="Toka" />
    <meta name="twitter:url" content="http://toka.io" />
    <meta name="twitter:card" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything." />
    <meta name="twitter:image" content="http://toka.io/assets/images/logo/toka_logo_orange_300ppi.png" />
    
    <!-- Misc Links -->
    <link rel="image_src" href="/assets/images/logo/toka_logo_orange_300ppi.png" />
    <link rel="icon" href="/assets/images/favicon/toka_favicon_white-01.png" />
    
    <!-- Latest compiled and minified Boostrap CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    
    <!-- jQuery CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
    
    <!-- External CSS -->
    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='http://bobco.moe/toka/theme.css' rel='stylesheet' type='text/css'>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/toka.css">
    <link rel="stylesheet" href="/assets/css/tokabot_themes.css">
    <link rel="stylesheet" href="/assets/css/navbar-custom.css">
    
    <!-- External JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="/assets/js/bootstrap-tagsinput.min.js"></script>
    
    <!-- Latest compiled Bootstrap JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- NodeJS Scripts (Need to update url when migrating to higher envrionments) -->
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/toka-test.js"></script>
    <script src="http://174.53.203.111/bobbotconsole/tokabot2.js"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-58660744-2', 'auto');
      ga('send', 'pageview');
    </script>
    <style>
    html {
        overflow: hidden;
    }
    </style>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    	toka.iniChatroom(<?php echo json_encode($chatroom); ?>);
    });        
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
            <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
            <div id="chatroom-title">
                <div id="chatroom-title-text"><?php echo $chatroom->chatroomName; ?></div>
                <div id="chatroom-title-users"><img src="/assets/images/icons/user.svg" class="img-responsive"><span class="chatroom-item-users-count">0</span></div>
            </div>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div class="chatroom-container">
                <div class="panel chatroom" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>">
                    <div class="panel-heading"><span class="chatroom-name"><?php echo $chatroom->chatroomName; ?></span></div>
                    <div class="panel-body">
                        <ul class="chatroom-chat"></ul>
                    </div>
                    <div class="panel-footer">
                        <div class=""><textarea class="form-control input-sm chatroom-input-msg" placeholder="Type your message..."></textarea></div>
                    </div>
                </div>
            </div>
            <div id="chatroom-user-list">
                <ul>
                </ul>
            </div>
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/login.php') ?>
            <?php include_once(__DIR__ . '/../../form/signup.php') ?>  
        </section>
    </div>
</body>
