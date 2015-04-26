<?php
// Maybe add an alias and require that alias file, then require using the name from the alias file...to have encapsulation
require_once(__DIR__ . '/../../../controller/IdentityController.php');

$controller = new IdentityController();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
    $response = $controller->get();
else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    $response = $controller->post();

$response = json_decode($response, true);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Toka</title>
    <?php include_once(__DIR__ . '/../common/header.php') ?>
    <style>
    #toka-msg {
        text-align: center;
        margin-bottom: 40px;
        background-color: rgba(0,0,0,0.8);
        color: #fff;
        padding: 50px 0 50px 0;
    }
    </style>
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
             <?php include_once(__DIR__ . '/../common/menu_no_login.php') ?> 
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-alert">
<?php 
if ($response['status'] === "0") {
?>
            <div id="site-alert-text" class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span><?php echo ucfirst($response['statusMsg']) . '.'; ?></span></div>
<?php 
}
?>
        </section>
        <section id="site-content">
<?php 
if ($response['status'] !== "1") {
?>
            <h2 id="toka-msg">Sign Up</h2>            
            <div style="max-width:700px; margin:auto; padding:40px 20px 20px 20px; border:1px #eee solid; border-radius:4px;">
                <section id="signup-alert">
                </section>
                <form class="form-horizontal" onsubmit="return toka.validateSignup()" action="/signup" method="post">
                    <div class="form-group">
                        <label for="toka-signup-username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-signup-username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-signup-email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="toka-signup-password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-password-again" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="toka-signup-password-again" name="password-again" placeholder="Repeat Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="toka-signup-button" type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
<?php
} else {
?>
            <h2 id="toka-msg">A verification email has been sent!</h2>
<?php 
}
?>
        </section>
    </div>
</body>
</html>