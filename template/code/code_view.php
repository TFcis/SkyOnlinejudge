<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?><script>
$(document).ready(function()
{
    var editor = ace.edit("editor");
    editor.setReadOnly(true);
})
</script>
<div class="container">
    <div class="row">
        <h2>Codepad - view</h2>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8">
        <?php Render::renderSingleTemplate('common_codepanel'); ?>
        </div>
    </div>
    <div class="row" style = "margin-top:15px;">
        <div class="col-lg-offset-6 col-md-offset-6 col-lg-2 col-md-2">
            <a href="<?=$_E['SITEROOT']?>code.php" class="btn btn-success active">Creat a new one</a>
        </div>
    </div>
</div>