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

        var editor = ace.edit("_json_data");
        json_data = editor.getValue();
        $("#json_data").val(json_data);

        api_submit("<?=$SkyOJ->uri('problem','api','modify',$tmpl['problem']->pid())?>","#modify-problem-from","#btn-show",function(e){
            setTimeout(function(){
                location.reload();
            }, 500);
        });
        return true;
    });
})
</script>
<style>body .ace_scrollbar-v{overflow-y: auto;}</style>
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
                        new HTML_INPUT_HIDDEN(['name'=>'json_data','id'=>'json_data','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['problem']->GetTitle(),'required'=>'required','option' => ['help_text' => '題目名稱']]),
                        new HTML_INPUT_SELECT(['name'=>'contenttype'
                            ,'key-pair'=> \SKYOJ\ProblemDescriptionEnum::getConstants()
                            
                            ,'option'  => ['help_text' => '題目格式']]),

                        new HTML_INPUT_SELECT(['name'=>'judge'
                            ,'key-pair'=> $tmpl['judges']
                            ,'default' => $tmpl['problem']->GetJudge()
                            ,'option'  => ['help_text' => 'Judge']]),

                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>$tmpl['problem']->GetRowContent(),
                                'language'=>'markdown',
                                'id'=> 'editor',
                                'setting'=>['minLines'=>20,'maxLines'=>20],
                                'help_text' => '題目敘述'
                            ]]),

                        new HTML_ROW(['html'=> <<<TAG
<div class="panel-group col-md-offset-3" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <span class="h4" id="formtitle">權限設定</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
TAG
                        ]),
                        new HTML_INPUT_SELECT(['name'=>'judge_type'
                            ,'key-pair'=> \SKYOJ\ProblemJudgeTypeEnum::getConstants()
                            ,'default' => $tmpl['problem']->GetJudgeType()
                            ,'option'  => ['help_text' => '題目輸入類型']]),
                        new HTML_INPUT_SELECT(['name'=>'content_access'
                            ,'key-pair'=> \SKYOJ\ProblemContentAccessEnum::getConstants()
                            ,'default' => $tmpl['problem']->GetContentAccess()
                            ,'option'  => ['help_text' => '題目檢視權限']]),
                        new HTML_INPUT_SELECT(['name'=>'submit_access'
                            ,'key-pair'=> \SKYOJ\ProblemSubmitAccessEnum::getConstants()
                            ,'default' => $tmpl['problem']->GetSubmitAccess()
                            ,'option'  => ['help_text' => '上傳權限']]),
                        new HTML_HR(),
                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>$tmpl['pjson'],
                                'language'=>'json',
                                'id'=> '_json_data',
                                'setting'=>['minLines'=>20,'maxLines'=>20],
                                'help_text' => 'JSON'
                            ]]),
                        new HTML_ROW(['html'=> <<<TAG
            </div>
        </div>
    </div>
</div>
TAG
                        ]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'modify-problem-from');
            ?>
        </div><!--Main end-->
        <div class="col-lg-2">
            <h1>Tips</h1>
            <p>
                <a class="btn btn-primary" href="<?=$SkyOJ->uri('problem','view',$tmpl['problem']->pid(),'')?>">檢視題目</a>
            </p>
        </div>
    </div>
    <br>
</div>