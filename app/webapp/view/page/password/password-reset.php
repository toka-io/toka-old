<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Toka</title>
    <?php include_once('common/header.php') ?>
     <style>    
    .alert-container {
        padding: 0px 15px;
    }
    #pr-alert {
        display: none;
    }
    #pr-form-container {
        max-width: 700px; 
        margin: 20px auto; 
        padding: 20px 20px 20px 20px; 
        border: 1px #ddd solid; 
        border-radius: 4px;
    }
    #pr-form-container .message {
        text-align: center;
        margin-bottom: 40px;
    }
    #pr-form {
        max-width: 600px; 
        margin: auto;
    }    
    #toka-msg {
        text-align: center;
        margin-bottom: 20px;
        background-color: #252525;
        color: #fff;
        padding: 50px 0 50px 0;
    }
    </style>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka.ini();
    });
    function validatePR() {        
        var password = $("#toka-pr-password").val().trim();
        var password2 = $("#toka-pr-password2").val().trim();

        if (password === "") {
            alertPRMessage("Please provide a new password");
            return false;
        }
        else if (password2 === "") {
            alertPRMessage("Please type your new password again.");
            return false;
        }
        else if (password !== password2) {
            alertPRMessage("The passwords do not match.");
            return false;
        }
        else
            return true;
    };
    function alertPRMessage(message) {
        $("#pr-alert").show();
        $("#pr-alert").text(message);
    }
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once('common/menu.php') ?>   
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left-nav.php') ?>
        </section>
        <section id="site-content">                            
            <h2 id="toka-msg">Password Reset</h2>
            <section class="alert-container">
                <div class="alert <?php echo (!empty($response)) ? 'alert-info' : '' ?>"><span>
                    <?php 
                    if (!empty($response)) {
                        echo $response['displayMessage']; 
                    } else 
                        echo '&nbsp;';
                    ?>
                </span></div>
            </section>     
            <div id="pr-form-container">
                <section id="pr-alert" class="alert alert-warning">
                </section>
                <div class="message"><h4>Please provide your new password.</h4></div>
                <form id="pr-form" class="form-horizontal" onsubmit="return validatePR()" action="/password/reset" method="post">
                    <div class="form-group">
                        <label for="toka-pr-password" class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="toka-pr-password" name="password" placeholder="New Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-pr-password2" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="toka-pr-password2" name="password2" placeholder="Password Again!">
                        </div>
                    </div>                   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </div>
                    </div>
                    <input type='hidden' name='username' value="<?= isset($_GET['login']) ? $_GET['login'] : ""; ?>" />
                    <input type='hidden' name='vCode' value="<?= isset($_GET['vCode']) ? $_GET['vCode'] : ""; ?>" />
                </form>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>