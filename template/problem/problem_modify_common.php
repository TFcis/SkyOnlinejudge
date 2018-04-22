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

        api_submit("<?=$SkyOJ->uri('problem','api','modify',$tmpl['problem']->pid)?>","#modify-problem-from","#btn-show",function(e){
            setTimeout(function(){
                location.reload();
            }, 500);
        });
        return true;
    });
})
</script>
<style>body .ace_scrollbar-v{overflow-y: auto;}</style>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <?php
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_ROW(['html'=>'<div class="col">']),
                        new HTML_INPUT_HIDDEN(['name'=>'pid','value'=>$tmpl['problem']->pid]),
                        new HTML_INPUT_HIDDEN(['name'=>'content','id'=>'content','value'=>'']),
                        new HTML_INPUT_HIDDEN(['name'=>'json_data','id'=>'json_data','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['problem']->title,'required'=>'required','option' => ['help_text' => '題目名稱']]),
                        new HTML_INPUT_SELECT(['name'=>'contenttype'
                            ,'key-pair'=> \SkyOJ\Problem\ContentTypenEnum::getConstants()
                            ,'default' => $tmpl['problem']->content_type
                            ,'option'  => ['help_text' => '題目格式']]),

                        new HTML_INPUT_SELECT(['name'=>'judge'
                            ,'key-pair'=> $tmpl['judges']
                            ,'default' => $tmpl['problem']->judge
                            ,'option'  => ['help_text' => 'Judge']]),
                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>$tmpl['problem']->getRowContent(),
                                'language'=>'markdown',
                                'id'=> 'editor',
                                'setting'=>['minLines'=>20,'maxLines'=>20],
                                'help_text' => '題目敘述'
                            ]]),

                        new HTML_ROW(['html'=>'</div><div class="col">']),
                        /*new HTML_INPUT_SELECT(['name'=>'judge_type'
                            ,'key-pair'=> \SKYOJ\ProblemJudgeTypeEnum::getConstants()
                            ,'default' => 0//$tmpl['problem']->GetJudgeType()
                            ,'option'  => ['help_text' => '題目輸入類型']]),*/
                        new HTML_INPUT_SELECT(['name'=>'content_access'
                            ,'key-pair'=> \SkyOJ\Problem\ProblemLevel::getConstants()
                            ,'default' => $tmpl['problem']->content_access
                            ,'option'  => ['help_text' => '題目檢視權限']]),
                        new HTML_INPUT_SELECT(['name'=>'submit_access'
                            ,'key-pair'=> \SkyOJ\Problem\ProblemSubmitLevel::getConstants()
                            ,'default' => $tmpl['problem']->submit_access
                            ,'option'  => ['help_text' => '上傳權限']]),
                        new HTML_INPUT_SELECT(['name'=>'codeview_access'
                            ,'key-pair'=> \SKYOJ\ProblemCodeviewAccessEnum::getConstants()
                            ,'default' => $tmpl['problem']->codeview_access
                            ,'option'  => ['help_text' => '程式碼檢視']]),
                        new HTML_HR(),
                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>'',//$tmpl['pjson'],
                                'language'=>'json',
                                'id'=> '_json_data',
                                'setting'=>['minLines'=>20,'maxLines'=>20],
                                'help_text' => 'JSON'
                            ]]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                        new HTML_ROW(['html'=>'</div>']),
                    ]
                ]),'modify-problem-from');
            ?>
        </div>
    </div>
    <br>
</div>