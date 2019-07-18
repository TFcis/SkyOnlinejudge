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

        var editor = ace.edit("_score_data");
        score_data = editor.getValue();
        $("#score_data").val(score_data);

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
                        new HTML_INPUT_HIDDEN(['name'=>'score_data','id'=>'score_data','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['problem']->title,'required'=>'required','option' => ['help_text' => '題目名稱']]),
                        new HTML_INPUT_SELECT(['name'=>'content_type'
                            ,'key-pair'=> \SkyOJ\Problem\ContentTypenEnum::getConstants()
                            ,'default' => $tmpl['problem']->content_type
                            ,'option'  => ['help_text' => '題目格式']]),

                        new HTML_INPUT_SELECT(['name'=>'judge_profile'
                            ,'key-pair'=> \SkyOJ\Judge\JudgeProfileEnum::getConstants()
                            ,'default' => $tmpl['problem']->judge_profile
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
                        new HTML_INPUT_TEXT(['name'=>'runtime_limit','value'=>$tmpl['problem']->runtime_limit,'required'=>'required','option' => ['help_text' => '測資運行時限']]),
                        new HTML_INPUT_TEXT(['name'=>'memory_limit','value'=>$tmpl['problem']->memory_limit,'required'=>'required','option' => ['help_text' => '記憶體使用限制(Byte)']]),
                        new HTML_INPUT_SELECT(['name'=>'score_type'
                            ,'key-pair'=> \SkyOJ\Score\ScoreModeEnum::getConstants()
                            ,'default' => $tmpl['problem']->score_type
                            ,'option'  => ['help_text' => '配分系統']]),
                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>$tmpl['problem']->score_data,
                                'language'=>'json',
                                'id'=> '_score_data',
                                'setting'=>['minLines'=>10,'maxLines'=>10],
                                'help_text' => '配分方法'
                            ]]),
                        new HTML_INPUT_CODEPAD(['option' =>
                            [
                                'code'=>$tmpl['problem']->getJudgeJson(),//$tmpl['pjson'],
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