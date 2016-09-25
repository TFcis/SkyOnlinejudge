<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#uninstall").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('admin','api','PluginUninstall')?>","#uninstall","#btn-show",function(){
            setTimeout(function(){
                loadTemplateToBlock('plugins/list/?folder=<?=urlencode($tmpl['folder'])?>','main-page');
            }, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <center>
            <h3><?=htmlentities($tmpl['class']::NAME)?><br><small class="login_sub_title">解除安裝</small></h3>
        </center>
    </div>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
            <p>
                該動作無法取消，是否繼續操作?
            </p>
            <br>
            <?php Render::renderForm($tmpl['pif_uninstall'], 'uninstall')?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <a class="btn btn-warning" href="#" tmpl="plugins/list/?folder=<?=urlencode($tmpl['folder'])?>">取消</a>
        </div>
    </div>
</div>

