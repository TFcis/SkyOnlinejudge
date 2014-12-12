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
    $.post("user.php",{
            mod : 'edit',
            page: 'authacct',
            id  : pageid,
            cls :c,
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
            //alert(data);
            
    });
    
    //$(disname).html(c);
    
}
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
                }
        },"json");
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
                    <div class="col-md-3"><?php if($oj['user']['approve']==1)$disabled='disabled';else $disabled='';?>
                        <input type="text" class="form-control" name="<?=$oj['class']?>" placeholder="<?=$oj['description']?>" value="<?=$oj['user']['acct']?>" <?=$disabled?>>
                    </div>
                    <div class="col-md-3"><?=$oj['info'];?>
                    <?php if( $oj['user']['acct'] && $oj['user']['approve'] == 0 && method_exists($oj['c'],'authenticate_message') ) : ?>
                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-<?=$oj['class']?>">
                            立即驗證帳號
                        </button>
                        <div class="modal fade" id="modal-<?=$oj['class']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content" style="color:#000">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><?=$oj['name']?>帳號驗證</h4>
                                    </div>
                                    
                                    <div class="modal-body">
                                    <?= $oj['c']->authenticate_message($_E['template']['showid'],$oj['user']['acct']); ?>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <span id="display-modal-<?=$oj['class']?>"></span>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                        <button type="button" class="btn btn-primary" onclick="authacct('<?=$oj['class']?>')">驗證</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-md-3">
                        <button type="submit" class="btn btn-success text-right">送出</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    
</div>
