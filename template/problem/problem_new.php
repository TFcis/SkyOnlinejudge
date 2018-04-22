<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_BUTTOM;
?>
<script>
$(document).ready(function(){
    $("#new-problem-from").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('problem','api','new')?>","#new-problem-from","#btn-show",function(e){
            setTimeout(function(){
                location.href="<?=$SkyOJ->uri('problem','modify')?>"+'/'+e.data;
            }, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>新增題目<small></small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <?php
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'new-problem-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-4">
            <h1>Advance&nbsp;</h1>
        </div>
    </div>
    <br>
</div>