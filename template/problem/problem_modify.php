<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_DIV;
use \SKYOJ\HTML_INPUT_SELECT;
use \SKYOJ\HTML_INPUT_BUTTOM;
use \SKYOJ\HTML_INPUT_HIDDEN;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/github-gist.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
$(document).ready(function(){
    $("#modify-problem-from").submit(function(e)
    {
        e.preventDefault();
        var editor = ace.edit("editor");
        content = editor.getValue();
        $("#content").val(content);

        api_submit("<?=$SkyOJ->uri('problem','api','modify',$tmpl['problem']->pid())?>","#modify-problem-from","#btn-show",function(e){
            setTimeout(function(){
                location.reload();
            }, 500);
        });
        return true;
    });
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setMode("ace/mode/markdown");
    editor.setOptions({
        minLines: 20,
        maxLines: Infinity
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>編輯題目<small></small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?php
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_HIDDEN(['name'=>'pid','value'=>$tmpl['problem']->pid()]),
                        new HTML_INPUT_HIDDEN(['name'=>'content','id'=>'content','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['problem']->GetTitle(),'required'=>'required','option' => ['help_text' => '題目名稱']]),
                        new HTML_INPUT_SELECT(['name'=>'contenttype'
                            ,'key-pair'=> \SKYOJ\ProblemDescriptionEnum::getConstants()
                            
                            ,'option'  => ['help_text' => '題目格式']]),

                        new HTML_INPUT_SELECT(['name'=>'judge'
                            ,'key-pair'=> $tmpl['judges']
                            ,'default' => $tmpl['problem']->GetJudge()
                            ,'option'  => ['help_text' => 'Judge']]),

                        new HTML_INPUT_SELECT(['name'=>'judge_type'
                            ,'key-pair'=> \SKYOJ\ProblemJudgeTypeEnum::getConstants()
                            ,'default' => $tmpl['problem']->GetJudgeType()
                            ,'option'  => ['help_text' => '題目類型']]),

                        new HTML_INPUT_DIV(['name'=>'','id'=>'editor','option' =>
                            [
                                'html'=>$tmpl['problem']->GetRowContent(),
                                'help_text' => '題目敘述'
                            ]]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'modify-problem-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-2">
            <h1>Tips</h1>
            <p>
                <a class="btn btn-primary" href="<?=$SkyOJ->uri('problem','view',$tmpl['problem']->pid())?>">檢視題目</a>
            </p>
        </div>
    </div>
    <br>
</div>