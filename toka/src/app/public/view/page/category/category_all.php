<?php 
require_once(__DIR__ . '/../../../../service/CategoryService.php');
require_once(__DIR__ . '/../../../../model/CategoryModel.php');

$request = array();
$response = array();

$categoryService = new CategoryService();

$response = $categoryService->getAllCategories($request, $response);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Categories - Toka</title>
    <?php include_once(__DIR__ . '/../../common/header.php') ?>
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
             <?php include_once(__DIR__ . '/../../common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
            <div id="category-list-title">Categories</div>
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div id="category-list">
<?php
foreach ($response['data'] as $key => $value) {
    // Add a try and catch if for some reason the chatroom is missing fields, do not show
    $category = new CategoryModel();
    $category->bindMongo($value);
?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <a data-category-name="<?php echo $category->categoryName; ?>" href="/category/<?php echo $category->categoryName; ?>" class="category-item thumbnail">
                        <div class="category-image"><img src="<?php echo $category->categoryImageUrl; ?>" class="img-responsive">
                        </div>
                        <div class="category-caption"><?php echo $category->categoryName; ?></div>
                    </a>
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