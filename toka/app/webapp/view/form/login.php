<div class="modal fade" id="login-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Log In</h4>
            </div>
            <div class="modal-body">
                <section id="login-alert">
                </section>
                <form class="form-horizontal" onsubmit="return loginApp.validateLogin()" action="/login" method="post">
                    <div class="form-group">
                        <label for="toka-login-username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-login-username" name="username" placeholder="Username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-login-password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="toka-login-password" name="password" placeholder="Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <div style="display:inline-block; float:left;">
                                    <label>
                                    <input type="checkbox"> Remember me
                                    </label>
                                </div>
                                <div style="display:inline-block; float:right;">
                                    <a href='/password' style="padding-right:5px;">Forgot Password?</a><a href='/signup' style="border-left:1px solid #bbb; padding-left:5px;">Sign Up</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="toka-login-button" type="submit" class="btn btn-primary">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display:none"></div>
        </div>
    </div>
</div>
<script>
var loginApp = new (function() {
    this.alertLogin = function(alertMsg) {
        var $alert =$("<div></div>", {
            "id" : "login-alert-text",
            "class" : "alert alert-warning",
            "text" : alertMsg
        });
        
        $("#login-alert").empty().append($alert);
    };
    
    this.validateLogin = function() {
        var password = $("#toka-login-password").val().trim();
        var username = $("#toka-login-username").val().trim();
        
        if (username === "") {
            this.alertLogin("Please provide a username.");
            return false;
        } else if (password === "") {
            this.alertLogin("Please provide a password.");
            return false;
        }
        
        return true;
    };
})();
</script>