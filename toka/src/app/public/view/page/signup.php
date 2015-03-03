<?php
// Maybe add an alias and require that alias file, then require using the name from the alias file...to have encapsulation
require_once(__DIR__ . '/../../../controller/IdentityController.php');

$controller = new IdentityController();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
    $response = $controller->get();
else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    $response = $controller->post();

$response = json_decode($response, true);

if ($response['status'] === "1")
    header("Location: http://" . $_SERVER['SERVER_NAME']);
else {
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
             <nav class="navbar navbar-default" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/"><img class="toka-menu-logo" src="/assets/images/logo/toka_logo_150ppi.png" /></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a id="category-all" href="/category" class="toka-menu-item"><img src="/assets/images/icons/categories.svg" class="menu-icon" />Categories</a>
                        </li>
                        <li><a id="search-page" href="#" class="toka-menu-item"><img src="/assets/images/icons/search.svg" class="menu-icon" />Search</a></li>
                        <li><span id="alpha-test-info" style="display:block; padding:15px; color:#fff" class="toka-menu-item"><img src="/assets/images/icons/info.svg" class="menu-icon" />The application will be under maintenance throughout the weekend 2/27-2/29. Apologies beforehand if something isn't working!~     -Arc</span></li>
                    </ul>    
                </div><!-- /.navbar-collapse -->
            </nav>   
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-alert">
            <div id="site-alert-text" class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span>Username has already been taken.</span></div>
        </section>
        <section id="site-content">Signup Form Here
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>        
        </section>
    </div>
</body>
</html>
<?php
}