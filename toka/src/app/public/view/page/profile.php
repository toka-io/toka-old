<?php
    require_once(__DIR__ . '/../../../service/IdentityService.php');
    require_once(__DIR__ . '/../../../model/UserModel.php');
    
    $identityService = new IdentityService();
    $user = $identityService->getUserSession();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $user->username . ' - Toka'; ?></title>
    <?php include_once(__DIR__ . '/../common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka = new Toka();
    	toka.ini();
    });
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once(__DIR__ . '/../common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once(__DIR__ . '/../common/left_nav.php') ?>
        </section>
        <section id="site-content">
				
				
				
				
				
				
				
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '/../form/site.php') ?>
        </section>
    </div>
</body>
</html>