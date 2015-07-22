<!-- Toka Menu Bar -->
<div id="toka-masthead-container" class="navbar navbar-default" role="navigation">
    <div id="toka-masthead-logo-container" class="navbar-header">
        <a id="logo-container" href="/">
            <img class="toka-menu-logo" src="/assets/images/logo/toka_logo_150ppi.png" />
            <span style="margin-left:-5px; font-size: 10px; vertical-align: baseline;">beta</span>
        </a>
    </div>
    <div id="toka-masthead-user">        
                  
<?php
if ($identityService->isUserLoggedIn()) {
?>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="/assets/images/icons/user.svg" class="menu-icon" />
                    <?php echo $_COOKIE[ 'username']; ?><b class="caret"></b>
                </a>
                <ul class="dropdown-menu" data-no-collapse="true">
                    <li><a href="/profile/<?php echo $_COOKIE[ 'username']; ?>" id="profile-page">Profile</a></li>
                    <li><a href="/profile/<?php echo $_COOKIE[ 'username']; ?>/settings" id="settings-page">Settings</a></li>
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
    </div>
    <div id="toka-masthead-content">
        <form id="masthead-search" role="search" autocomplete="off" onsubmit="if (document.getElementById('masthead-search-term').value == '') return false;">
            <div id="masthead-search-btn">
                <button class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
            </div>
            <div id="masthead-search-terms">
                <input id="masthead-search-term" type="text" class="form-control" placeholder="Search (Doesn't work yet :3)" name="q">                
            </div>            
        </form>
    </div>
</div>