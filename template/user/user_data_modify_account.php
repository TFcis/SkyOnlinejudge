<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
var pageid = <?=$_E['template']['showid']?> ;
function authacct(c)
{
    var disname = '#display-modal-' + c ;
    $.post("<?=$_E['SITEROOT']?>user.php",{
            mod : 'edit',
            page: 'authacct',
            id  : pageid,
            cls : c,
        },
        function(res){
            if( res.status == 'error' )
            {
                $(disname).css('color','Red');
                $(disname).html(res.data);
            }
            else
            {
                $(disname).css('color','Lime');
                $(disname).html('Success! reload page');
                setTimeout( function(){location.reload();},500);
            }
    });
}
$(document).ready(function()
{
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
        $.post("<?=$_E['SITEROOT']?>user.php",
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
                }
        },"json");
        return true;
    });
})
</script>

<div class="container">

    <div class="row">
        <div><h2>Account</h2></div>
        <div class="panel panel-default">
            <div class="panel-heading">Account Information</div>
            <div class="panel-body">
                <p>SOJ ID : <?=$tmpl['showid']?></p>
                <?php if(userControl::isAdmin($tmpl['showid'])):?>
                <p>WOW ADMIN!</p>
                <?php endif;?>
                <p>avatar via <a href="https://gravatar.com/" class="btn btn-primary active" target="_blank">gravatar</a></p>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Account Setting</div>
            <div class="panel-body">
                <h2>Password</h2>
                <form class="form-horizontal" role="form" id="acct">
                    <input type='hidden' name='mod' value='edit'>
                    <input type='hidden' name='page' value='acct'>
                    <input type='hidden' name='id' value='<?=$tmpl['showid']?>'>
                    <div class="form-group">
                        <label class="col-md-2 control-label">舊密碼</label>
                        <div class="col-md-3">
                            <input type="password" class="form-control" name="oldpasswd" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">新密碼</label>
                        <div class="col-md-3">
                            <input type="password" class="form-control" name="newpasswd" placeholder="New Password" id="newpass">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">再次確認</label>
                        <div class="col-md-3">
                            <input type="password" class="form-control" placeholder="Repeat again" id="renewpass">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-3">
                            <button type="submit" class="btn btn-success text-right">送出</button>
                            <small><span id="acct-show"></span></small>
                        </div>
                    </div>
                </form>
            </div><!--account setting panel-body end-->
        </div>
        
    </div>
</div>
