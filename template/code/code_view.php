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
        <div class="col-lg-4 col-md-4">
            <h1>Information</h1>
            <?php if($tmpl['owner']!=0):?>
            <p>Owner : <a href='<?=$_E['SITEROOT']."user.php/view/".$tmpl['owner']?>'><?=htmlspecialchars($_E['nickname'][$tmpl['owner']])?></a></p>
            <?php else:?>
            <p>Owner : <?=htmlspecialchars($_E['nickname'][$tmpl['owner']])?></p>
            <?php endif;?>
            <p>Submit : <?=$tmpl['timestamp']?></p>
            <p></p>
            <hr>
            <div class="info">
                <h4><span class="glyphicon glyphicon-share"></span>&nbsp;Share or Embed source code</h4>
                <textarea class="form-control" rows="3"><iframe src='<?=$_E['SITEROOT']."code.php/view/".$tmpl['hash']."/iframe"?>' width='100%' height='300px'></iframe></textarea>
            </div>
        </div>
    </div>
    <div class="row" style = "margin-top:15px;">
        <div class="col-lg-offset-6 col-md-offset-6 col-lg-2 col-md-2">
            <a href="<?=$_E['SITEROOT']?>code.php" class="btn btn-success active">Creat a new one</a>
        </div>
    </div>
</div>