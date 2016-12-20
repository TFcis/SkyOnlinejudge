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
$(document).ready(function()
{
    $("#codesubmit").click(function(e)
    {
        var editor = ace.edit("editor");
        var code = editor.getValue();
        if( code === '' && false )
        {
            if( !confirm('Are you sure to submit an empty code?') )
            {
                return ;
            }
        }
        $("#s_code").val(code);
        api_submit("<?=$SkyOJ->uri('problem','api','submit')?>","#submit","#info",function(res){
            loadTemplate('log');
        });
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2><?=$tmpl['problem']->pid()?>. <?=htmlentities($tmpl['problem']->GetTitle())?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="submit">
                <input type="hidden" name="code" id="s_code" value="">
                <input type="hidden" name="pid" value="<?=$tmpl['problem']->pid()?>">
                <div class="form-group">
                    <label for="language" class="col-sm-2 control-label">Compiler</label>
                    <select class="form-control" name="compiler">
                        <?php foreach($tmpl['compiler'] as $key => $data): ?>
                        <option value="<?=htmlentities($key)?>"><?=htmlentities($data)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
        <?php Render::renderCode('','c_cpp','editor',['minLines'=>20,'maxLines'=>20]); ?>
        </div>
    </div>

    <div class="row" style = "margin-top:15px;">
        <div class="col-sm-offset-6 col-sm-6 text-right">
            <span id="info"></span><buttom class="btn btn-success" id="codesubmit">Submit</buttom>
        </div>
    </div>
</div>