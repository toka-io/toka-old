<?php 
include_once(__DIR__ . '/../../common/session.php');

require_once(__DIR__ . '/../../../../service/CategoryService.php');
require_once(__DIR__ . '/../../../../model/CategoryModel.php');

$request = array();
$response = array();

$categoryService = new CategoryService();

$request['data']['categoryName'] = $categoryService->getCategoryNameFromUrl(urldecode($_SERVER['REQUEST_URI']));
$response = $categoryService->getChatrooms($request, $response);

$categoryName = $response['categoryName'];

$chatrooms = array();
foreach ($response['data'] as $key => $mongoObj) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
    $chatroom = new ChatroomModel();
    $chatroom->bindMongo($mongoObj);
    $chatrooms[$chatroom->chatroomID] = $chatroom;
}

$categoryImages = $categoryService->getCategoryImages();

// Garbage Collect
unset($categoryService);
unset($request);
unset($response);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $categoryName . ' - Toka'; ?></title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
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
             <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once(__DIR__ . '/../../common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-subtitle">
                <div id="chatroom-list-title">
                    <div id="chatroom-list-title-text"><?php echo $categoryName; ?></div>
                </div>
            </section>
            <section id="site-alert">
            </section>
            <ul id="chatroom-list">
<?php
foreach ($chatrooms as $chatroomID => $chatroom) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
?>
                <li class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="chatroom-item" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>">
                        <a href="/chatroom/<?php echo $chatroom->chatroomID; ?>"class="chatroom-item-top">
                            <div class="chatroom-item-image">
                                <img src="<?php echo isset($categoryImages[$chatroom->categoryName]) ? $categoryImages[$chatroom->categoryName] : ""; ?>" class="img-responsive">
                            </div>
                        </a>
                        <div class="chatroom-item-bottom">
                            <div class="chatroom-item-name">
                                <h4 title="<?php echo htmlentities($chatroom->chatroomName); ?>"><?php echo htmlentities($chatroom->chatroomName); ?></h4>
                            </div>
                            <div class="chatroom-item-details">
                                <div class="chatroom-item-users"><img src="/assets/images/icons/user_g.svg" class="img-responsive"><span class="chatroom-item-users-count">0</span>
                                </div>
                                <!-- <div class="chatroom-item-follow"><a class="btn btn-primary" role="button">Follow</a> -->
                            </div>
                            <div class="chatroom-item-host">Hosted by <span class="user-profile-link"><?php echo $chatroom->owner; ?></span>
                            </div>
                        </div>
                    </div>
                </li>
<?php
}
?>
            </ul>  
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/site.php') ?>  
        </section>
    </div>
</body>
</html>