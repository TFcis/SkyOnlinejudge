<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
function lol(){
    alert('lol');
}
</script>
<div class = "container">
    <div class="row">
        <div class="text-center">
            <h1><?=\SKYOJ\html($tmpl['contest']->title)?></h1>
            before start
            <div data-toggle="sky-countdown" data-value="<?=$tmpl['contest']->starttime?>" onclockdownzero="lol();"></div>
            wait and lol
        </div>
    </div>
</div>
