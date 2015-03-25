<?php 
require_once(__DIR__ . '/../../../../service/ChatroomService.php');
require_once(__DIR__ . '/../../../../service/IdentityService.php');
require_once(__DIR__ . '/../../../../service/TokadownService.php');
require_once(__DIR__ . '/../../../../model/ChatroomModel.php');

$identityService = new IdentityService();
$user = $identityService->getUserSession();

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
    
    $tokaUser = new UserModel();
    $tokaUser->setUsername($chatroom->chatroomID);
    $userExists = $identityService->checkUserExists($tokaUser);    
    
    if ($userExists) {        
        $chatroom->chatroomName = "@" . $request['data']['chatroomID'];
    } else {
        $chatroom->chatroomName = "#" . $request['data']['chatroomID'];
    }
}
?>
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
        <section id="site-subtitle">
            <div id="chatroom-title">
                <div id="chatroom-title-text"><?php echo $chatroom->chatroomName; ?></div>
                <div id="chatroom-title-menu">
                    <div id="chatroom-title-users"><img src="/assets/images/icons/user.svg" class="img-responsive" /><span class="chatroom-item-users-count">0</span></div>
<?php
if ($identityService->isUserLoggedIn($user) && $user->username === $chatroom->owner) {
?>                  <div id="chatroom-title-update-chatroom">
                        <div data-toggle="tooltip" data-original-title="Update Chatroom">
                            <div id="chatroom-update-chatroom-icon" data-toggle="modal" data-target="#update-chatroom-form">
                                <img src="/assets/images/icons/settings.svg" class="img-responsive" />
                            </div>
                        </div>
                    </div>
<?php 
}
?>
                </div>
            </div>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            
            <div class="chatroom-container"> 
                <div class="chatroom" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>">
                    <div class="chatroom-heading"><span class="chatroom-name"><?php echo $chatroom->chatroomName; ?></span></div>
                    
<?php 
if ($chatroom->chatroomID !== "dualchatroom") { 
?>
                    <div class="chatroom-body">
                        <div class="chatroom-chat-container">
                            <ul class="chatroom-chat"></ul>
                        </div>
                    </div>
<?php 
} else {
?>
                    <div class="chatroom-body">
                        <div class="chatroom-chat-member-container" style="float:left;overflow:hidden;">
                            <ul class="chatroom-chat-member"></ul>
                        </div>
                        <div class="chatroom-chat-visitor-container" style="float:right;overflow:hidden;">
                            <ul class="chatroom-chat-visitor"></ul>
                        </div>
                    </div>
<?php    
}
?>
                    <div class="chatroom-footer">
                        <textarea class="form-control input-sm chatroom-input-msg" placeholder="Type your message..."></textarea>
                    </div>
                </div>
            </div>
            <div id="chatroom-info">
                <div id="chatroom-info-text">
<?php
    $tokadownService = new TokadownService();
    echo (!empty(trim($chatroom->info))) ? $tokadownService->render($chatroom->info) : $tokadownService->render("Something should be here...^^v"); 
?>
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
            <?php include_once(__DIR__ . '/../../form/update_chatroom.php') ?>  
        </section>
    </div>
</body>
</html>