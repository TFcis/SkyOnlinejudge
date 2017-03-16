<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_SELECT;
use \SKYOJ\HTML_INPUT_BUTTOM;
?>
<script>
$(document).ready(function(){
    $("#new-contest-from").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('contest','api','new')?>","#new-contest-from","#btn-show",function(e){
            setTimeout(function(){
                location.href="<?=$SkyOJ->uri('contest','modify')?>"+'/'+e.data;
            }, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>新增競賽<small></small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <?php
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_TEXT(['name'=>'title','required'=>'required','option' => ['help_text' => '競賽名稱']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'new-contest-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-4">
            <h1>Advance&nbsp;</h1>
        </div>
    </div>
    <br>
</div>
