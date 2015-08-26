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
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 1) { ?><link rel="stylesheet" href="/assets/css/chatroom_embed1.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 2) { ?><link rel="stylesheet" href="/assets/css/chatroom_embed2.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 3) { ?><link rel="stylesheet" href="/assets/css/chatroom_embed3.css"><?php } ?>
    <?php if (isset($_GET['embed']) && $_GET['embed'] == 4) { ?><link rel="stylesheet" href="/assets/css/chatroom_embed4.css"><?php } ?>
    <?php if (isset($_GET['blind']) && $_GET['blind'] == 1) { ?><link rel="stylesheet" href="/assets/css/chatroom_blind.css"><?php } ?>
    <link rel="stylesheet" href="/assets/components/lightbox2/src/css/lightbox.css">
    <style>
    html {
        overflow: hidden;
    }
    .lb-nav {
        display: none !important;
    }
    </style>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    	toka.iniChatroom(<?= json_encode($chatroom); ?>);
    	toka.tokabot = new TokaBot({
        	embed: <?= (isset($_GET['embed']) && $_GET['embed'] == 1) ? "true" : "false"; ?>,
        	target: "<?= (isset($_GET['target'])) ? $_GET['target'] : "_self"; ?>"
    	});
    });        
    </script>
</head>
<?php 
echo cloudinary_js_config();
$cors_location = "https://toka.io/assets/components/cloudinary/html/cloudinary_cors.html"; 
?>
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
                <div id="chatroom-title">
                    <div id="chatroom-title-text"><?= $chatroom->chatroomName; ?></div>
                    <div id="chatroom-title-menu">
                        <div id="chatroom-title-users"><img src="/assets/images/icons/user.svg" class="img-responsive" /><span class="chatroom-item-users-count">0</span></div>
                        <?php include_once('update_chatroom_button.php') ?>
                    </div>
                </div>
            </section>
            <section id="site-alert">
            </section>
            <div class="chatroom-section">
                <div class="chatroom-container"> 
                    <div class="chatroom" data-chatroom-id="<?= $chatroom->chatroomId; ?>">
                        <div class="chatroom-heading"><span class="chatroom-name"><?= $chatroom->chatroomName; ?></span></div>
                        <?php include_once('chatroom_body.php') ?>
                        <div class="chatroom-footer">
                            <textarea class="form-control input-sm chatroom-input-msg" placeholder="Type your message..." rows=1></textarea>
                            <?php if ($identityService->isUserLoggedIn()) { ?>
                                <span class="upload-img-btn glyphicon glyphicon-camera"></span>
                            <?php 
                                echo cl_image_upload_tag('upload-img', array("callback" => $cors_location));
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="chatroom-info">
                    <div id="chatroom-info-text">
                    <?php include_once('chatroom_info.php') ?>
                    </div>
                </div>
                <div id="chatroom-user-list">
                    <ul>
                    </ul>
                </div>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('chatroom_popup.php') ?>
            <?php include_once('common/site.php') ?>
            <?php include_once('form/update_chatroom.php') ?>   
        </section>
    </div>    
    <script src="/assets/components/lightbox2/src/js/lightbox.js"></script>
</body>
</html>