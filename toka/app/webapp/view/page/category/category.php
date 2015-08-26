<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?= $categoryName . ' - Toka'; ?></title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    	toka.iniChatroomList(<?php echo json_encode($chatrooms); ?>);
    });        
    </script>
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
                <div id="chatroom-list-title" class="default-subtitle"><?= $categoryName; ?></div>
            </section>
            <section id="site-alert">
            </section>
            <ul id="chatroom-list">
<?php
foreach ($chatrooms as $chatroomId => $chatroom) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
?>
                <li class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="chatroom-item" data-chatroom-id="<?= $chatroom->chatroomId; ?>">
                        <a href="/chatroom/<?= $chatroom->chatroomId; ?>"class="chatroom-item-top">
                            <div class="chatroom-item-image">
                                <img src="<?= isset($categoryImages[$chatroom->categoryName]) ? $categoryImages[$chatroom->categoryName] : ""; ?>" class="img-responsive">
                            </div>
                        </a>
                        <div class="chatroom-item-bottom">
                            <div class="chatroom-item-name">
                                <h4 title="<?= htmlentities($chatroom->chatroomName); ?>"><?= htmlentities($chatroom->chatroomName); ?></h4>
                            </div>
                            <div class="chatroom-item-details">
                                <div class="chatroom-item-users"><img src="/assets/images/icons/user_g.svg" class="img-responsive"><span class="chatroom-item-users-count">0</span>
                                </div>
                                <!-- <div class="chatroom-item-follow"><a class="btn btn-primary" role="button">Follow</a> -->
                            </div>
                            <div class="chatroom-item-host">Hosted by <span class="user-profile-link"><?= $chatroom->owner; ?></span>
                            </div>
                        </div>
                    </div>
                </li>
<?php
}
?>
            </ul>  
        </section>
        <section id="site-modals">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>