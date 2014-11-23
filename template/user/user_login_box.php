<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#loginform").submit(function(e)
    {
        $("#display").html('...');
        e.preventDefault();
        $.post("user.php",
            $("#loginform").serialize(),
            function(res){
                if(res.status == 'error')
                {
                    $("#display").html(res.data);
                    $("#display").css('color','Red');
                }
                else
                {
                    $("#display").css('color','Lime');
                    $("#display").html('Welcome!');
                    location.href = res.data;
                }
        },"json");
        return true;
    });
})
</script>
<div class="container">

        <center>
        <div id = "signin">
        
            <h3><?php echo($_E['site']['name']);?><br><small>User Login</small></h3>
            <div class="text-right">
                <p><i><small>-Programming Is the New Literacy</small></i></p>
            </div>
            
            <form role="form" action="user.php" method="post" id="loginform">
            
                <input type="hidden" value="login" name="mod">
                    
                <br>
                
                <div class="form-group">
                <label for="email" style = "display: block">Nickname / Email</label>
                <input type="text" class="textinput" id="email" name="email" placeholder="Nickname / Email" required>
                </div>
                
                <div class="form-group">
                <label for="password" style = "display: block">PASSWORD</label>
                <input type="password" class="textinput" id="password" name="password" placeholder="Password" required>
                </div>
                
                <br>
                <div>
                    <small><span id="display"></span></small>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="usenickname" value="1" checked > Login By Nickname
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class= "btn-grn btn-large btn-wide" style = "width:168px">
                    <b>Login</b>
                    </button>
                </div>
                
            </form>
            <!--
            <br>
            <h3>沒有帳號？<br>立即加入！</h3>
            <button type="button" class="btn-blu btn-large btn-wide"  onclick="location.href='user.php?mod=register'">
            <b>REGISTER</b>
            </button>-->
            <small>OR</small>
        
            <div class = 'link-like' onclick="location.href='user.php?mod=register'">
            <u><b>Register</b></u>
            </div>
            
        </div>

    </center>

</div>
