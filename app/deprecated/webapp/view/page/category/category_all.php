<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title>Categories - Toka</title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
    	toka.ini();
    });        
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
            <section id="site-subtitle">
                <div id="category-list-title" class="default-subtitle">Categories</div>
            </section>
            <section id="site-alert">
            </section>
            <div id="category-list">
                <?php foreach ($categories as $category) { ?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <a data-category-name="<?= $category->categoryName; ?>" href="/category/<?= $category->categoryName; ?>" class="category-item thumbnail">
                        <div class="category-image"><img src="<?= $category->categoryImgUrl; ?>" class="img-responsive">
                        </div>
                        <div class="category-caption"><?= $category->categoryName; ?></div>
                    </a>
                </div>
                <?php } ?>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('common/site.php') ?>
        </section>
    </div>
</body>
</html>