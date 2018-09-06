<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#ojacct").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('user','edit','ojacct')?>","#ojacct","#btn-show",function(){
            setTimeout(function(){
                location.href="<?=$SkyOJ->uri('user','view',$tmpl['showid'],'setting','ojacct')?>";
            }, 500);
        });
        return true;
    });
})
</script>

<div class="container">
    <div class="row">
        <div><h2>Availavle online judge systems:</h2></div>
        <div>
            <form class="form-horizontal" role="form" id="ojacct">
                <input type='hidden' name='id' value='<?=$tmpl['showid']?>'>
                <?php foreach($tmpl['ojs'] as $ojname => $ojid):?>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?=$ojname?></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="oj<?=$ojid?>" value="<?=$tmpl['ojacct'][$ojid]??''?>">
                    </div>
                    <div class="col-md-5"></div>
                </div>
                <?php endforeach; ?>
                
                <div class="col-sm-offset-2 col-md-5 text-right">
                    <small><span id='btn-show'></span></small>
                    <button class="btn btn-success" name="btn">送出</button>
                </div>              
            </form>
        </div>
    </div>
    
</div>
