<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
    <title>About Us</title>
    <?php include_once(__DIR__ . '../../common/header.php') ?>

    <!--Google Fonts-->
    <link href= 'http://fonts.googleapis.com/css?family=Roboto:900,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
    
    <!-- Include Font Awesome Stylesheet in Header -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!--css for about us page-->
    <style>
        html{
            height:100%;
        }

        body{
            height:100%;
            background-image: ;
        }

		#title {
			height: auto;
			width: 100%;
			margin-left: auto;
			margin-right: auto;
			padding-top: 30px;
			padding-bottom: 30px;

		}


		#aboutus {
			font-size: 80px;
			font-family: 'Abel';
			color: #ff7d1e;
			text-align: center;
		}
		
		#description {
            width: 50%;
            height: auto;
            margin: 0 auto;
            padding: 20px
            position: relative;
		}
		
		h3{
			font-size: 35px;
			font-family: 'Abel';
			color: black;
			text-align: center;
				
		}
				
		p{
			font-size: 20px;
			font-family: 'Abel';
			color: black;
			text-align: center;	
		}
    /* css for social media links */
		#social:hover {
    			-webkit-transform:scale(1.1); 
				-moz-transform:scale(1.1); 
				-o-transform:scale(1.1); 
		}		
		#social {
				-webkit-transform:scale(0.8);
				/* Browser Variations: */
				-moz-transform:scale(0.8);
				-o-transform:scale(0.8); 
				-webkit-transition-duration: 0.5s; 
				-moz-transition-duration: 0.5s;
				-o-transition-duration: 0.5s;
		}           
    /* Only Needed in Multi-Coloured Variation */
		.social-fb:hover {
			color: #3B5998;
		}
		.social-tw:hover {
			color: #4099FF;
		}
		.social-gp:hover {
			color: #d34836;
		}
		.social-em:hover {
			color: #f39c12;
		}
		.container{
			height: auto;
            margin-left: auto;
			margin-right: auto;
		}

		#footer {
            top: 100%;
            width: 100%;
            margin-left: auto;
			margin-right: auto;
            margin-top: 2%;
		}

        .col-xs-4 {
            height: auto;
            width: 50%;
            margin: 0 auto;
            padding-left: 10%;
            padding-right: 10%;
            position: relative;
        }
    </style>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once(__DIR__ . '../../common/menu.php') ?>     
        </section>
        <section id="site-subtitle">
        </section>
        <section id="site-alert">
        </section>
        <section id="site-content">
            <div id="title">
        		<h1 id="aboutus">About Us</h1>
            </div>
            <div id="description">
            	<h3>The Legend</h3>
            	<p>
                	History tells us that the origins of Toka lie somewhere around February 2015 (if sources are to be trusted). Its genesis was the mastermind of creators Andy (arcthefallen) and Jihoon (gghoon), two enigmatic figures forever shrouded in mystery and intrigue. <br><br>
        
                    For endless days and nights, the creators toiled amidst the soft white glow of the computer screen and the pungent odor of the Hot Pocket, oblivious to the world around them. The clock ticked. The seasons changed. Civilizations rose and fell, yet the creators knew not. Eternity came, then passed. All that was real in the world began to fade away, as the very dimension of time began to take on a new shape. And yet, the creators advanced on their incalculable quest - to forge a neoteric entity, to shift all paradigms known to mankind. <br><br>And at last, just when it seemed that the universe could take it no longer, Toka was conceived. <br><br>
                    From whence they came? We do not know. <br>We may never know.<br><br>
        
                </p>
                <h3>Our Mission</h3>
                <p>
                    Our mission is to provide a community for people all around the world to meet and build meaningful relationships. 
                    The social taboo of meeting people online is outdated; we are going to make it the norm, not the exception :)
                </p>
            </div>
            <!-- Social Footer, Colour Matching Icons -->
            <div class="container">
                <hr>
                    <div class="text-center center-block">
                        <br />
                            <a href="https://www.facebook.com/bootsnipp"><i id="social" class="fa fa-facebook-square fa-3x social-fb"></i></a>
                            <a href="https://twitter.com/bootsnipp"><i id="social" class="fa fa-twitter-square fa-3x social-tw"></i></a>
                            <a href="https://plus.google.com/+Bootsnipp-page"><i id="social" class="fa fa-google-plus-square fa-3x social-gp"></i></a>
                            <a href="mailto:bootsnipp@gmail.com"><i id="social" class="fa fa-envelope-square fa-3x social-em"></i></a>
                    </div>
            </div>
        </section>
        <section id="site-footer">
            <?php include_once(__DIR__ . '../../common/footer.php') ?>        
        </section>
        <section id="site-forms">
            <?php include_once(__DIR__ . '../../form/login.php') ?>
            <?php include_once(__DIR__ . '../../form/signup.php') ?>
        </section>
    </div>
</body>

</html>