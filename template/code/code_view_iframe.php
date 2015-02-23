<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<?php Render::renderSingleTemplate('common_header'); ?>
<script>
$(document).ready(function()
{
    var editor = ace.edit("editor");
    editor.setReadOnly(true);
})
</script>
</head>

<div id="wrap">
    <p><a href="<?=$_E['SITEROOT']."code.php/view/".$tmpl['hash']?>" target="_blank">View in original site</a></p>
    <?php Render::renderSingleTemplate('common_codepanel'); ?>
    <div class="text-right">
        <p><?=$_E['site']['name']?></p>
    </div>
</div>
<?php Render::renderSingleTemplate('common_footer'); ?>