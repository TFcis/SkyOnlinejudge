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
function update_problem_string()
{
    problem_str = "";
    problem_str = $("#problem_editor div.row").map(function()
    {
        return $(this).find("input").map(function(){return $(this).val()}).get().join(":");
    }).get().join(",");
    $("#prostr").val(problem_str);
}

function problem_editor_add(tag="",pid="",state="",priority="")
{
    selected = [];
    selected.length = <?=count($tmpl['pstate_keypair'])?>;
    <?php foreach($tmpl['pstate_keypair'] as $key => $val):?>
    selected[<?=$val?>] = "";
    <?php endforeach;?>
    selected[state] = "selected";
    newpro = $("#problem_editor").append(`<div class="row">\
    <div class="form-group col-md-2">\
        <div class="input-group">\
            <span class="input-group-addon">TAG</span>\
            <input type="text" class="form-control" name="tag" value="${tag}" placeholder="TAG">\
        </div\>
    </div>\
    <div class="form-group col-md-2">\
        <div class="input-group">\
            <span class="input-group-addon">Pid</span>\
            <input type="text" class="form-control" name="pid" value="${pid}" placeholder="Problem ID">\
        </div\>
    </div>\
    <div class="form-group col-md-2">\
        <div class="input-group">\
            <span class="input-group-addon">State</span>\
            <select class="form-control">\
            <?php foreach($tmpl['pstate_keypair'] as $key => $val):?>
                <option value="<?=$val?>" ${selected[<?=$val?>]}><?=$key?></option>
            <?php endforeach;?>
            </select>\
        </div\>
    </div>\
    <div class="form-group col-md-2">\
        <div class="input-group">\
            <span class="input-group-addon">Pri</span>\
            <input type="text" class="form-control" name="priority" value="${priority}" placeholder="priority">\
        </div\>
    </div>\
    <div class="form-group col-md-1">\
        <button type="button" class="btn btn-danger" name="delete" aria-label="Del">\
            <span class="glyphicon glyphicon-trash" aria-hidden="false"></span>\
        </button>\
    </div>\
</div>`);
    newpro.find("button[name='delete']").click(function()
    {
        $(this).parent().parent().remove();
        update_problem_string();
    });
    newpro.change(function()
    {
        update_problem_string();
    });
}

function update_problem_editor(pstr="")
{
    $("#problem_editor").empty();
    problems = pstr.split(",");
    problems.forEach(function(p,idx,all)
    {
        val = p.split(":");
        problem_editor_add(val[0],val[1],val[2],val[3]);
    });
}

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
    $("#add_problem").click(function()
    {
        problem_editor_add();
    });
    $("#prostr").change(function()
    {
        update_problem_editor($(this).val());
    });
    update_problem_editor($("#prostr").val());
});
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>編輯競賽<small></small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <?php
                $p = $tmpl['contest']->GetProblems();
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_HIDDEN(['name'=>'cont_id','value'=>$tmpl['contest']->cont_id()]),
                        new HTML_INPUT_HIDDEN(['name'=>'content','id'=>'content','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['contest']->title,'required'=>'required','option' => ['help_text' => '競賽名稱']]),
						new HTML_INPUT_TEXT(['name'=>'start','value'=>$tmpl['contest']->starttime,'option' => ['help_text' => '開始時間']]),
                        new HTML_INPUT_TEXT(['name'=>'end','value'=>$tmpl['contest']->endtime,'option' => ['help_text' => '結束時間']]),
                        new HTML_INPUT_TEXT(['name'=>'problems','id'=>'prostr','value'=>implode(',',$p),'option' => ['help_text' => '題目列表']]),
                        new HTML_INPUT_SELECT(['name'=>'registertype'
                            ,'key-pair'=> \SKYOJ\ContestUserRegisterStateEnum::getConstants()
                            ,'default' =>(int)$tmpl['contest']->register_type
                            ,'option'  => ['help_text' => '註冊模式']]),
                        new HTML_INPUT_TEXT(['name'=>'registerpassword','value'=>$tmpl['contest']->register_password,'option' => ['help_text' => '註冊密碼（少於100個字元）']]),
						new HTML_INPUT_TEXT(['name'=>'registerbegin','value'=>$tmpl['contest']->register_beginsec,'option' => ['help_text' => '註冊開放於競賽開始前(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'registerdelay','value'=>$tmpl['contest']->register_delaysec,'option' => ['help_text' => '註冊開放於競賽開始後(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'freezesec','value'=>$tmpl['contest']->freeze_sec,'option' => ['help_text' => '凍結於競賽結束前(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'penalty','value'=>$tmpl['contest']->penalty,'option' => ['help_text' => '答錯罰時(sec)']]),
                        new HTML_INPUT_TEXT(['name'=>'class','value'=>$tmpl['contest']->class,'option' => ['help_text' => 'class']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'modify-contest-from');
            ?>
        </div><!--Main end-->
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>題目列表:</h2><!---ptag:pid:state:priority(state:0->hidden,1->normal,2->readonly)-->
        </div>
    </div>
    <form id="problem_editor">
    </form>
    <div class="row">
        <div class="form-group col-md-9">
            <button id="add_problem" type="button" class="btn btn-success btn-block">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
</div>
