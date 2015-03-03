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
        <li><span id="alpha-test-info" style="display:block; padding:15px; color:#fff" class="toka-menu-item"><img src="/assets/images/icons/info.svg" class="menu-icon" />The application will be under maintenance throughout the week 3/2-3/8 for private beta preparations. Apologies beforehand if something isn't working!~     -Arc</span></li>
    </ul>    
<?php
if (isset($_COOKIE['sessionID'])) { 
?>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="/assets/images/icons/user.svg" class="menu-icon" /><?php echo $_COOKIE['username']; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#" id="profile-page">Profile</a></li>
          <li><a href="#" id="settings-page">Settings</a></li>
          <li><a href="#" id="help-page">Help</a></li>
          <li class="divider"></li>
          <li><a href="/logout" id="user-logout">Log Out</a></li>
        </ul>
      </li>
    </ul>
<?php 
} else {
?>
    <ul class="nav navbar-nav navbar-right pad-right15">
        <li>
            <p class="navbar-btn">
                <a href="#" id="login-page" class="btn toka-button" data-toggle="modal" data-target="#login-form">Log In</a>
                <a href="#" id="signup-page" class="btn toka-button" data-toggle="modal" data-target="#signup-form">Sign Up</a>
            </p>
        </li>
    </ul>
<?php 
}
?>
    </div><!-- /.navbar-collapse -->
</nav>