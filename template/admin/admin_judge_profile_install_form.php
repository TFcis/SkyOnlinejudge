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
        api_submit("<?=$SkyOJ->uri('admin','api','NewJudgeProfile')?>","#install","#btn-show",function(){
            setTimeout(function(){
                loadTemplateToBlock('judge_profile/list','main-page');
            }, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <center>
            <h3>安裝設定</h3>
        </center>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <?php Render::renderForm($tmpl['formInfo'], 'install')?>
        </div>
    </div>
</div>
