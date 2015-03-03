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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $mongoObj['chatroom_name'] . ' - Toka'; ?></title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    	toka.iniChatroom();
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
                <div id="chatroom-title-text"><?php echo $mongoObj['chatroom_name']; ?></div>
                <div id="chatroom-title-users"><img src="/assets/images/icons/user.svg" class="img-responsive"><span class="chatroom-item-users-count">0</span></div>
            </div>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div class="chatroom-container">
                <div class="panel chatroom" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>" data-chatroom='<?php echo json_encode($chatroom); ?>'>
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
</html>