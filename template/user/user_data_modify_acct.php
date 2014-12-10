<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#ojacct").submit(function(e)
    {
        e.preventDefault();
        $.post("user.php",
            $("#ojacct").serialize(),
            function(data){
                alert("Data Loaded: " + data);
        });
        return true;
    });
    $("#acct").submit(function(e)
    {
        e.preventDefault();
        var x1 = $("#newpass").val();
        var x2 = $("#renewpass").val();
        if( x1 == '' || x2 == '' )
        {
            $("#acct-show").html('Empty!');
            $("#acct-show").css('color','Red');
            return false;
        }
        if( x1!=x2 )
        {
            $("#acct-show").html('Are you kidding?');
            $("#acct-show").css('color','Red');
            return false;
        }
        $.post("user.php",
            $("#acct").serialize(),
            function(res){
                if(res.status == 'error')
                {
                    $("#acct-show").html(res.data);
                    $("#acct-show").css('color','Red');
                }
                else
                {
                    $("#acct-show").css('color','Lime');
                    $("#acct-show").html('Success!');
                    $('#acct').trigger("reset");
                    //window.location.replace('http://ulkk2285d976.lfswang.koding.io/SkyOJ/user.php?mod=login');
                }
        }/*,"json"*/);
        return true;
    });
})
</script>

<div class="container">

    <div class="row">
        <div><h2>Account</h2></div>
        <form class="form-horizontal" role="form" id="acct">
            <input type='hidden' name='mod' value='edit'>
            <input type='hidden' name='page' value='acct'>
            <input type='hidden' name='id' value='<?=$_E['template']['showid']?>'>
            <div class="form-group">
                <label class="col-md-2 control-label">舊密碼</label>
                <div class="col-md-5">
                    <input type="password" class="form-control" name="oldpasswd" placeholder="Old Password">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">新密碼</label>
                <div class="col-md-5">
                    <input type="password" class="form-control" name="newpasswd" placeholder="New Password" id="newpass">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">再次確認</label>
                <div class="col-md-5">
                    <input type="password" class="form-control" placeholder="Repeat again" id="renewpass">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-md-5">
                    <button type="submit" class="btn btn-success text-right">送出</button>
                    <small><span id="acct-show">info</span></small>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row">
        <div><h2>Availavle online judge systems:</h2></div>
        
        <div>
            <form class="form-horizontal" role="form" id="ojacct">
                <input type='hidden' name='mod' value='edit'>
                <input type='hidden' name='page' value='ojacct'>
                <input type='hidden' name='id' value='<?=$_E['template']['showid']?>'>
                
                <?php foreach($_E['template']['oj'] as $oj){ ?>
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=$oj['name']?></label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="<?=$oj['class']?>" placeholder="<?=$oj['description']?>" <?php if(isset($oj['value'])):?> value="<?=$oj['value']?>" <?php endif;?>>
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-md-5">
                        <button type="submit" class="btn btn-success text-right">送出</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    
</div>
