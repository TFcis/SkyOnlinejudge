<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
require_once $_E['ROOT'].'/function/common/FormInfo.php';
//use \SKYOJ\FormInfo;
?>
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector:'#announcement',
        plugins :[
            "advlist autolink lists link charmap preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime nonbreaking table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ]
    });
</script>
<script>
$(document).ready(function()
    $("#board").submit(function(e)
    {
        $("#display").html("SUBMIT...");
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('problem','api','new')?>","#new-problem-from","#btn-show",function(e){
            setTimeout(function(){
                location.href="<?=$SkyOJ->uri('problem','modify')?>"+'/'+e.data;
            }, 500);
        });
        return true;
        $("#announce").val(tinymce.activeEditor.getContent());
        $.post("<?=$_E['SITEROOT']?>rank.php",
            $("#board").serialize(),
            function(res){
                if(res.status === 'SUCC')
                {
                    $("#display").html("YES");
                    setTimeout(function(){location.href="<?=$_E['SITEROOT']?>rank.php?mod=cbedit&id="+res.data;}, 500);
                }
                else if(res.status === 'error')
                {
                   $("#display").html(res.data);
                }
        },"json");
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
                        new HTML_INPUT_TEXT(['name'=>'title','option' => ['help_text' => '題目名稱']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'new-problem-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-4">
            <h1>Advance&nbsp;<small><span id="adv-act-info"></span></small></h1>
                <p>
                    <buttom class="btn btn-primary" adv-act="freeze">Freeze</buttom>
                    凍結記分板 <small><span id="adv-act-freeze">重建並鎖定</span></small>
                </p>
                <p>
                    <buttom class="btn btn-danger" adv-act="close">Close</buttom>
                    關閉記分板 <small><span id="adv-act-close">關閉記分板</span></small>
                </p>
                <p>
                    <buttom class="btn btn-success" adv-act="open">Open</buttom>
                    開啟記分板 <small><span id="adv-act-open">開啟記分板</span></small>
                </p>
        </div>
    </div>
    <br>
</div>