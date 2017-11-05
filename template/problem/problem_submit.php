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
        if( code === '' )
        {
            if( !confirm('Are you sure to submit an empty code?') )
            {
                return ;
            }
        }
        $("#s_code").val(code);
        api_submit("<?=$SkyOJ->uri('problem','api','submit')?>","#submit","#info",function(res){
            <?=$tmpl['jscallback']??''?>
        });
    });
    function init()
    {
        var j_submit = $('#codesubmit');
        $('#s_file').on('change',function(e)
        {
            var editor = ace.edit("editor");
            var reader = new FileReader();
            reader.onload = function(e){
                editor.getSession().setValue(reader.result);
            }
            if( this.files.length === 0 ){
                editor.getSession().setValue("");
                editor.setReadOnly(false);
            }else{
                editor.setReadOnly(true);
                editor.getSession().setValue("Loading...");
                reader.readAsText(this.files[0]);
            }
        });
    };
    init();
})
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2><?=$tmpl['problem']->pid?>. <?=htmlentities($tmpl['problem']->title)?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="submit">
                <input type="hidden" name="code" id="s_code" value="">
                <input type="hidden" name="pid" value="<?=$tmpl['problem']->pid?>">
                <div class="form-group">
                    <label for="language" class="col-sm-2 control-label">Compiler</label>
                    <select class="form-control" name="compiler">
                        <?php foreach($tmpl['compiler'] as $key => $data): ?>
                        <option value="<?=\SKYOJ\html($key)?>"><?=\SKYOJ\html($data)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <?php Render::renderCode('','c_cpp','editor',['minLines'=>20,'maxLines'=>20]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <input type="file" id="s_file" name="codefile">
                </div>
            </form>
        </div>
    </div>

    

    <div class="row" style = "margin-top:15px;">
        <div class="col-sm-offset-6 col-sm-6 text-right">
            <span id="info"></span><buttom class="btn btn-success" id="codesubmit">Submit</buttom>
        </div>
    </div>
</div>