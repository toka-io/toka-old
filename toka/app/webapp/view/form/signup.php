<!-- Modal -->
<div class="modal fade" id="signup-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Sign Up</h4>
            </div>
            <div class="modal-body">
                <section id="signup-alert">
                </section>
                <form class="form-horizontal" onsubmit="return signupApp.validateSignup()" action="/signup" method="post">
                    <div class="form-group">
                        <label for="toka-signup-username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-signup-username" name="username" placeholder="Username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="toka-signup-email" name="email" placeholder="Email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="toka-signup-password" name="password" placeholder="Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="toka-signup-password-again" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="toka-signup-password-again" name="password-again" placeholder="Repeat Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="toka-signup-button" type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display:none"></div>
        </div>
    </div>
</div>
<script>
var signupApp = new (function() {
    this.alertSignup = function(alertMsg) {
        var $alert =$("<div></div>", {
            "id" : "signup-alert-text",
            "class" : "alert alert-warning",
            "text" : alertMsg
        });
        
        $("#signup-alert").empty().append($alert);
    };
    
    this.validateSignup = function() {    
        var email = $("#toka-signup-email").val().trim();
        var password = $("#toka-signup-password").val().trim();
        var passwordRepeat = $("#toka-signup-password-again").val().trim();
        var username = $("#toka-signup-username").val().trim();
        
        var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        if (username === "") {
            this.alertSignup("Please provide a username.");
            return false;
        }
        else if (!/^[a-zA-Z0-9_]{3,25}$/.test(username)) {
            this.alertSignup("Username must be 3-25 characters in length and can contain only alphanumeric characters with the exception of '_'.");
            return false;
        }
        else if (banned_list.hasOwnProperty(username) || reserved_list.hasOwnProperty(username)) {
            this.alertSignup("You cannot use that name.");
            return false;
        }
        else if (email === "") {
            this.alertSignup("Please provide an email address.");
            return false;
        } else if (!emailRegex.test(email)) {
            this.alertSignup("Please provide a valid email address (i.e. email@address.com).");
            return false;
        } else if (password === "") {
            this.alertSignup("Please provide a password.");
            return false;
        } else if (password !== passwordRepeat) {
            this.alertSignup("Passwords do not match.");
            return false;
        }
        
        return true;
    };
})();
signupApp.ini();
</script>