<?php 
require_once(__DIR__ . '/../../../../service/CategoryService.php');
require_once(__DIR__ . '/../../../../model/CategoryModel.php');

$request = array();
$response = array();

$categoryService = new CategoryService();

$request['data']['categoryName'] = "Popular";
$response = $categoryService->getChatrooms($request, $response);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $response['categoryName'] . ' - Toka'; ?></title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    	toka.iniChatroomList();
    });        
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
            <?php echo '<div id="chatroom-list-title">' . $response['categoryName'] . '</div>'; ?>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div id="chatroom-list">
<?php
foreach ($response['data'] as $key => $mongoObj) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
    $chatroom = new ChatroomModel();
    $chatroom->bindMongo($mongoObj);
?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="chatroom-item" data-chatroom-id="<?php echo $chatroom->chatroomID; ?>" data-chatroom='<?php echo json_encode($chatroom); ?>'>
                        <a href="/chatroom/<?php echo $chatroom->chatroomID; ?>"class="chatroom-item-top">
                            <div class="chatroom-item-image"><img src="/assets/images/icons/chat.svg" class="img-responsive">
                            </div>
                        </a>
                        <div class="chatroom-item-bottom">
                            <div class="chatroom-item-name">
                                <h4><?php echo $chatroom->chatroomName; ?></h4>
                            </div>
                            <div class="chatroom-item-details">
                                <div class="chatroom-item-users"><img src="/assets/images/icons/user_g.svg" class="img-responsive"><span class="chatroom-item-users-count">2</span>
                                </div>
                                <div class="chatroom-item-follow"><a class="btn btn-primary" role="button">Follow</a>
                                </div>
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
        </section>
    </div>
</body>
</html>