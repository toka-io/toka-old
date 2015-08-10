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
    #pr-alert {
        display: none;
    }
    #pr-form-container {
        max-width: 700px; 
        margin: auto; 
        padding: 40px 20px 20px 20px; 
        border: 1px #eee solid; 
        border-radius: 4px;
    }
    #pr-form {
        max-width: 600px; 
        margin: auto;
    }
    #toka-msg {
        text-align: center;
        margin-bottom: 40px;
        background-color: #252525;
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
    function validatePR() {        
        var username = $("#toka-pr-username").val().trim();
        var email = $("#toka-pr-email").val().trim();

        var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        if (username === "") {
            alertPRMessage("Please provide a username");
            return false;
        }
        else if (!/^[a-zA-Z0-9_]{3,25}$/.test(username)) {
            alertPRMessage("Username must be 3-25 characters in length and can contain only alphanumeric characters with the exception of '_'.");
            return false;
        }
        else if (email === "") {
            alertPRMessage("Please provide an email address.");
            return false;
        } else if (!emailRegex.test(email)) {
            alertPRMessage("Please provide a valid email address (i.e. email@address.com).");
            return false;
        } 
        return false;
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
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <h2 id="toka-msg">Password Recovery</h2>
            <section id="site-alert">
<?php 
if (!empty($response) && $response['status'] === "0") {
?>
            <div id="site-alert-text" class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span><?php echo ucfirst($response['statusMsg']) . '.'; ?></span></div>
<?php 
}
?>
            </section>     
            <div id="pr-form-container">
                <section id="pr-alert" class="alert alert-warning">
                </section>
                <div>Please provide a username OR email to recover your password.</div>
                <form id="pr-form" class="form-horizontal" onsubmit="return validatePR()" action="/password" method="post">
                    <div class="form-group">
                        <label for="toka-pr-username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-pr-username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-pr-email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-pr-email" name="email" placeholder="Email">
                        </div>
                    </div>                   
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Recover</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>