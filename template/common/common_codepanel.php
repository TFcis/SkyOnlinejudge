<?php
    if (!defined('IN_TEMPLATE')) {
        exit('Access denied');
    }
?>
<script>
$(document).ready( function()
{
    var editor = ace.edit("<?=htmlspecialchars($tmpl['_id'])?>");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setValue(<?=json_encode($tmpl['_defaultcode'])?>);
    editor.getSession().setMode("ace/mode/<?=$tmpl['_language']?>");
    editor.getSession().setUseWrapMode(true);
    editor.setOptions(<?=json_encode($tmpl['_set'])?>);
})
</script>
<div class="code_editor" id="<?=htmlspecialchars($tmpl['_id'])?>"></div>

