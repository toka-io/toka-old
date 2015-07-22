<?php include_once(__DIR__ . '/../../common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $chatroom->chatroomName . ' - Toka'; ?></title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
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
        <section id="site-left-nav">
            <?php include_once(__DIR__ . '/../../common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-subtitle">
                <div id="chatroom-title">
                    <div id="chatroom-title-text"><?php echo $chatroom->chatroomName; ?></div>
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
                    <div class="chatroom" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>">
                        <div class="chatroom-heading"><span class="chatroom-name"><?php echo $chatroom->chatroomName; ?></span></div>
                        <?php include_once('chatroom_body.php') ?>
                        <div class="chatroom-footer">
                            <textarea class="form-control input-sm chatroom-input-msg" placeholder="Type your message..." rows=1></textarea>
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
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/site.php') ?>
            <?php include_once(__DIR__ . '/../../form/update_chatroom.php') ?>   
        </section>
    </div>
</body>
</html>