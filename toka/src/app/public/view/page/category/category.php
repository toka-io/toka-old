<?php 
require_once(__DIR__ . '/../../../../service/CategoryService.php');
require_once(__DIR__ . '/../../../../service/IdentityService.php');
require_once(__DIR__ . '/../../../../model/CategoryModel.php');

$user = new UserModel();

if (isset($_COOKIE['username']))
    $user->setUsername($_COOKIE['username']);

$isLoggedIn = !empty($user->username);

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

$identityService = new IdentityService();

$data = $identityService->getChatroomsByOwner($user); // Get chatrooms owned by user
$hasMaxChatroom = $identityService->hasMaxChatrooms($user); // Can user create more chatrooms?
$hasChatroom = false; // Does user have a chatroom?
$userChatroom = new ChatroomModel();

if (!empty($data)) {
    $mongoObj = $data["0"];
    $userChatroom->bindMongo($mongoObj);
    $hasChatroom = true;
}

// Garbage Collect
unset($categoryService);
unset($identityService);
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
        <section id="site-subtitle">
            <div id="chatroom-list-title">
                <div id="chatroom-list-title-text"><?php echo $categoryName; ?></div>
<?php if ($isLoggedIn && !$hasMaxChatroom) { 
?>                
                <div id="chatroom-list-add">
                    <div data-toggle="tooltip" data-original-title="Create Chatroom">
                        <div id="chatroom-list-add-icon" data-toggle="modal" data-target="#create-chatroom-form">
                            <img src="/assets/images/icons/add.svg" class="img-responsive">
                        </div>
                    </div>
                </div>
<?php 
} 
?>
<?php if ($isLoggedIn && $hasChatroom) { 
?>                
                <div id="mychatroom">
                    <div data-toggle="tooltip" data-original-title="My Chatroom">
                        <a href="/chatroom/<?php echo $userChatroom->chatroomID; ?>">
                            <div id="mychatroom-icon">
                                <img src="/assets/images/icons/home.svg" class="img-responsive">
                            </div>
                        </a>
                    </div>
                </div>
<?php
} 
?>
                <div class="clearfix"></div>
            </div>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div id="chatroom-list">
<?php
foreach ($chatrooms as $chatroomID => $chatroom) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
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
                </div>
<?php
}
?>
            </div>  
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../../form/login.php') ?>
            <?php include_once(__DIR__ . '/../../form/signup.php') ?>
            <?php include_once(__DIR__ . '/../../form/create_chatroom.php') ?>  
        </section>
    </div>
</body>
</html>