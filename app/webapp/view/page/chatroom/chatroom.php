<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?= $chatroom->chatroomName . ' - Toka'; ?></title>
    <?php include_once('common/header.php') ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 1) { ?><link rel="stylesheet" href="/assets/css/chatroom-embed1.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 2) { ?><link rel="stylesheet" href="/assets/css/chatroom-embed2.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 3) { ?><link rel="stylesheet" href="/assets/css/chatroom-embed3.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 4) { ?><link rel="stylesheet" href="/assets/css/chatroom-embed4.css"><?php } ?>
    <link rel="stylesheet" href="/assets/components/lightbox2/src/css/lightbox.css">
    <style>
    html {
        overflow: hidden;
    }
    #site-menu {
        position: absolute;
    }
    .lb-nav {
        display: none !important;
    }
    </style>
    <script>
    /* DOM Ready */
    var chatroomApp;
    $(document).ready(function() {
    	toka.ini();
    	toka.tokabot = new TokaBot({
        	embed: <?= (isset($_GET['embed'])) ? "true" : "false"; ?>,
        	target: "<?= (isset($_GET['target'])) ? $_GET['target'] : "_self"; ?>",
        	settings: <?= json_encode($settings); ?>,
        	metadataCache: <?= json_encode($metadataCache); ?>
    	});    	
    	chatroomApp = new ChatroomApp();
    	chatroomApp.ini(<?= json_encode($chatroom); ?>);
    });        
    </script>
</head>
<?php 
echo cloudinary_js_config(); 
?>
<body>
    <div id="site">
        <section id="site-menu">
            <?php include_once('common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left-nav.php') ?>
        </section>
        <section id="site-content">
            <div class="chatroom" data-chatroom-id="<?= $chatroom->chatroomId; ?>">
                <div class="chatroom-heading">
                    <div class="title"><?= $chatroom->chatroomName; ?></div>
                    <div class="title-menu">
                        <div class="users"><img src="/assets/images/icons/user.svg" class="img-responsive" /><span class="chatroom-item-users-count">0</span></div>
                        <?php include_once('update-chatroom-button.php') ?>
                    </div>
                </div>
                <div class="chatroom-body">
                    <div class="chatbox">
                        <?php include_once('chatroom-body.php') ?>
                        <div class="inputbox">
                            <textarea class="form-control input-sm input-msg" placeholder="Type here to chat. Use / for commands." rows=1></textarea>
                            <?php if (IdentityService::isUserLoggedIn()) { ?>
                                <span class="upload-img-btn glyphicon glyphicon-camera"></span>
                            <?php 
                                echo cl_unsigned_image_upload_tag('tvg4odgw', 'tvg4odgw', array("cloud_name" => "toka", "tags" => $chatroom->chatroomId));
                            }
                            ?>
                        </div>                       
                    </div>
                    <div class="infobox">
                        <div class="text">
                        <?php include_once('chatroom-info.php') ?>
                        </div>
                    </div>
                    <div class="user-list">
                        <ul>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('chatroom-popup.php') ?>
            <?php include_once('common/site.php') ?>
            <?php include_once('form/update-chatroom.php') ?>   
        </section>
    </div>    
    <script src="/assets/components/lightbox2/src/js/lightbox.js"></script>
</body>
</html>