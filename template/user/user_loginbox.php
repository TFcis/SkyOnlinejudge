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
        <div class="col-md-3 col-md-offset-1" id="signin">
            <h3>登入開始今日的挑戰！</h3>
            <form role="form" action="user.php" method="post">
                <input type="hidden" value="login" name="mod">
                <div class="form-group">
                    <label for="accountname">Username</label>
                    <input type="text" class="form-control" id="accountname" name="accountname" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-success">登入</button>
            </form>
            <br>
            <h3>沒有帳號？立即加入！</h3>
            <button type="button" class="btn btn-primary"  onclick="location.href='user.php?mod=register'">註冊</button>
        </div>
    </div>
</div>
