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
            <div class="text-right">
                <?php if(isset($_E['template']['login'])):?>
                <p><i><small><?php echo($_E['template']['login']);?></small></i></p>
                <?php else:?>
                <p><i><small>-Programming Is the New Literacy</small></i></p>
                <?php endif;?>
            </div>
            <form role="form" action="user.php" method="post">
                <input type="hidden" value="login" name="mod">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-success">登入</button>
            </form>
            <br>
            <h3>沒有帳號？立即加入！</h3>
            <button type="button" class="btn btn-primary"  onclick="location.href='user.php?mod=register'">註冊</button>
        </div>
    </div>
</div>
