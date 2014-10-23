<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <!--<div class="col-md-4 col-md-offset-1" id="signinlogo">
        </div>-->
        <center>
        <div id="signin">
        
            <h3>登入開始今日的挑戰！</h3>
            <div style = "text-align: right">
                <p><i><small>-Programming Is the New Literacy</small></i></p>
            </div>
            
            <form role="form" action="user.php" method="post">
            
                <input type="hidden" value="login" name="mod">
                    
                    <br>
                    
                    <div class="form-group">
                    <label for="email" style = "display: block">EMAIL</label>
                    <input type="email" class="textinput" id="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="form-group">
                    <label for="password" style = "display: block">PASSWORD</label>
                    <input type="password" class="textinput" id="password" name="password" placeholder="Password" required>
                    </div>
                    
                    <br>
                    
                <button type="submit" class="btn btn-success">登入</button>
                
            </form>
            
            <br>
            <h3>沒有帳號？立即加入！</h3>
            <button type="button" class="btn btn-primary"  onclick="location.href='user.php?mod=register'">註冊</button>
        </div>
        </center>
    </div>
</div>
