<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#install").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('admin','api','PluginInstall')?>","#install","#btn-show",function(){
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
            <h3><?=htmlentities($tmpl['class']::NAME)?><br><small class="login_sub_title">安裝設定</small></h3>
        </center>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <?php Render::renderForm($tmpl['pif_install'], 'install')?>
            <a class="btn btn-danger" href="#" tmpl="plugins/install/<?=base64_encode($tmpl['folder'])?>/<?=base64_encode($tmpl['class'])?>">重新檢查</a>
        </div>
    </div>
</div>
