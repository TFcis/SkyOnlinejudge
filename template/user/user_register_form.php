<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-1" id="signinlogo">
        </div>
        <div class="col-md-4 col-md-offset-1" id="signin">
            <h3><?php echo($_E['site']['name']);?><small>帳號資料</small></h3>
            <div class="text-right">
                <p><i><small>Coding is like poetry</small></i></p>
            </div>
            <div class=".container-fluid">
                <div class="row">
                    <form role="form" action="user.php" method="post" class="form-horizontal">
                        <input type="hidden" value="reg" name="mod">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-9">
                                 <input type="text" class="form-control" id="username" placeholder="Username">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-9">
                                 <input type="email" class="form-control" id="email" placeholder="Email">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-9">
                                 <input type="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group">
                            <label for="password2" class="col-sm-2 control-label">Repeat</label>
                            <div class="col-sm-9">
                                 <input type="password" class="form-control" id="password2" placeholder="Password">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">註冊</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>