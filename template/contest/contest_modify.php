<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_HR;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_CODEPAD;
use \SKYOJ\HTML_INPUT_SELECT;
use \SKYOJ\HTML_INPUT_BUTTOM;
use \SKYOJ\HTML_INPUT_HIDDEN;
?>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/github-gist.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>-->
<script>
$(document).ready(function(){
    $("#modify-contest-from").submit(function(e)
    {
        e.preventDefault();
        
        api_submit("<?=$SkyOJ->uri('contest','api','modify',$tmpl['contest']->cont_id())?>","#modify-contest-from","#btn-show",function(e){
            setTimeout(function(){
                location.reload();
            }, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>編輯競賽<small></small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?php
                $p = $tmpl['contest']->GetProblems();
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_HIDDEN(['name'=>'cont_id','value'=>$tmpl['contest']->cont_id()]),
                        new HTML_INPUT_HIDDEN(['name'=>'content','id'=>'content','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['contest']->GetTitle(),'required'=>'required','option' => ['help_text' => '競賽名稱']]),
						new HTML_INPUT_TEXT(['name'=>'start','value'=>$tmpl['contest']->GetStart(),'option' => ['help_text' => '開始時間']]),
                        new HTML_INPUT_TEXT(['name'=>'end','value'=>$tmpl['contest']->GetEnd(),'option' => ['help_text' => '結束時間']]),
                        new HTML_INPUT_TEXT(['name'=>'problems','value'=>implode(',',$p),'option' => ['help_text' => '題目列表']]),
                        new HTML_INPUT_SELECT(['name'=>'registertype'
                            ,'key-pair'=> \SKYOJ\ContestUserRegisterStateEnum::getConstants()
                            ,'default'=>$tmpl['contest']->GetRegisterType()
                            ,'option'  => ['help_text' => '註冊模式']]),
							
						new HTML_INPUT_TEXT(['name'=>'registerbegin','value'=>$tmpl['contest']->GetRegBegin(),'option' => ['help_text' => '註冊開放於競賽開始前(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'registerdelay','value'=>$tmpl['contest']->GetRegDelay(),'option' => ['help_text' => '註冊開放於競賽開始後(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'freezesec','value'=>$tmpl['contest']->GetFreezeSec(),'option' => ['help_text' => '凍結於競賽結束前(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'penalty','value'=>$tmpl['contest']->GetPenalty(),'option' => ['help_text' => '答錯罰時(sec)']]),

                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'modify-contest-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-10">
            <h1>填寫說明</h2><br>
            <h2>題目列表:</h2>ptag:pid:state:priority(state:0->hidden,1->normal,2->readonly)
        </div>
    </div>
    <br>
</div>
