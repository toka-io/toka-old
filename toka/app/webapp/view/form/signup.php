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
                <form class="form-horizontal" onsubmit="return toka.validateSignup()" action="/signup" method="post">
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