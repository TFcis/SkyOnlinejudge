<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#codesubmit").click(function(e)
    {
        var editor = ace.edit("editor");
        code = editor.getValue();
        if( code === '')
        {
            alert('Empty!');
            return ;
        }
        $.post("<?=$_E['SITEROOT']?>code.php/submit",{code : code},function(res){
            if( res.status == 'SUCC' )
            {
                location.href="<?=$_E['SITEROOT']?>code.php/view/"+res.data;
            }
            else
            {
                alert(res.data);
            }
        },"json").error(function(e){
            console.log(e);
        });
    });
})
</script>
<div class="container">
    <div class="row">
        <h2>Codepad</h2>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8">
        <?php Render::renderSingleTemplate('common_codepanel'); ?>
        </div>
    </div>
    <div class="row" style = "margin-top:15px;">
        <div class="col-lg-offset-7 col-md-offset-7 col-lg-1 col-md-1">
            <buttom class="btn btn-success" id="codesubmit">Submit</buttom>
        </div>
    </div>
</div>