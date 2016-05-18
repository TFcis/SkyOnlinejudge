<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
var pageid = <?=$tmpl['showid']?> ;
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
                setTimeout( function(){
                    $('#modal-'+c).modal('hide');
                },300);
                setTimeout( function(){
                    SubloadTemplate('ojacct');
                },500);
            }
    },"json");
}
$(document).ready(function()
{
    $("#ojacct").submit(function(e)
    {
        e.preventDefault();
        $.post("<?=$_E['SITEROOT']?>user.php",
            $("#ojacct").serialize(),
            function(res){
            if( res.status == 'error' )
            {
                $('#display-ojacct').css('color','Red');
                $('#display-ojacct').html(res.data);
            }
            else
            {
                $('#display-ojacct').css('color','Lime');
                $('#display-ojacct').html('Success! reload page');
                setTimeout( function(){
                    //SubloadTemplate In USER STEEING PAGE
                    SubloadTemplate('ojacct');
                },500);
            }
    },"json");
        return true;
    });
})
</script>

<div class="container">
    <div class="row">
        <div><h2>Availavle online judge systems:</h2></div>
        
        <div>
            <form class="form-horizontal" role="form" id="ojacct">
                <input type='hidden' name='mod' value='edit'>
                <input type='hidden' name='page' value='ojacct'>
                <input type='hidden' name='id' value='<?=$tmpl['showid']?>'>
                
                <?php foreach ($tmpl['oj'] as $oj) {
    ?>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?=$oj['name']?></label>
                    <div class="col-md-4"><?php if ($oj['user']['approve'] == 1) {
    $disabled = 'disabled';
} else {
    $disabled = '';
}
    ?>
                        <input type="text" class="form-control" name="<?=$oj['class']?>" placeholder="<?=$oj['description']?>" value="<?=$oj['user']['account']?>" <?=$disabled?>>
                    </div>
                    <div class="col-md-5"><?=$oj['info'];
    ?>
                    <?php if ($oj['user']['account'] && $oj['user']['approve'] == 0 && method_exists($oj['c'], 'authenticate_message')) : ?>
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
                                    <?= $oj['c']->authenticate_message($tmpl['showid'], $oj['user']['account']);
    ?>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <span id="display-modal-<?=$oj['class']?>"></span>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                        <button type="button" class="btn btn-primary" onclick="authacct('<?=$oj['class']?>')">驗證</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;
    ?>
                    </div>
                </div>
                <?php 
} ?>
                
                <div class="form-group">
                    <div class="col-sm-offset-7 col-md-5">
                        <button type="submit" class="btn btn-success text-right">送出</button>
                        <span id="display-ojacct"></span>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    
</div>
