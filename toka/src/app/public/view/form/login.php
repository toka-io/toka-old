<!-- Modal -->
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
                <form class="form-horizontal" onsubmit="return toka.validateLogin()" action="/login" method="post">
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
                                <label>
                                <input type="checkbox"> Remember me
                                </label>
                                <a class="col-sm-offset-5" href='/password'>
                                Forgot Password?
                                </a>
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