<nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/"><img class="toka-menu-logo" src="/assets/images/logo/toka_logo_white_150ppi.png" /></a>
    </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li><a id="category-all" href="#" class="toka-menu-item"><img src="/assets/images/icons/categories.svg" class="menu-icon" />Categories</a></li>
      <li><a id="search-page" href="#" class="toka-menu-item"><img src="/assets/images/icons/search.svg" class="menu-icon" />Search</a></li>
      <li><a id="alpha-test-info" href="#" class="toka-menu-item"><img src="/assets/images/icons/info.svg" class="menu-icon" />Chat server is back up! The application will be under maintenance again tomorrow @6pm EST 2/24/15~     -Arc</a></li>
      <!-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
          <li class="divider"></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li> -->
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
                <a href="#" id="signup-page" class="btn toka-button">Sign Up</a>
            </p>
        </li>
    </ul>
<?php 
}
?>
    </div><!-- /.navbar-collapse -->
</nav>