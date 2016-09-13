<?php
    if (!defined('IN_TEMPLATE')) {
        exit('Access denied');
    }
?>
<script>
$(document).ready( function()
{
    var editor = ace.edit("<?=\SKYOJ\html($tmpl['_id'])?>");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setMode("ace/mode/<?=$tmpl['_language']?>");
    editor.setOptions({
        minLines: 20,
        maxLines: Infinity
    });
})
</script>
<div class="code_editor" id="<?=\SKYOJ\html($tmpl['_id'])?>"><?=htmlspecialchars($tmpl['_defaultcode'],ENT_HTML5|ENT_COMPAT,"UTF-8")?></div>

