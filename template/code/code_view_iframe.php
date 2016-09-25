<?php
if (!defined('IN_TEMPLATE')) {
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
    <p><a href="<?=$SkyOJ->uri('code','view',$tmpl['hash'])?>" target="_blank">View in original site</a></p>
    <?php Render::renderCode($tmpl['defaultcode']??'','c_cpp','editor'); ?>
    <div class="text-right">
        <p><?=$_E['site']['name']?></p>
    </div>
</div>
<?php Render::renderSingleTemplate('common_footer'); ?>