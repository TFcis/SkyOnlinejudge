<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?=$tmpl['problem']->getRendedContent()?>
            <br/>
            <h2>Judge Setting</h2>
            run-time limit: <?=$tmpl['problem']->runtime_limit?> ms
            <br/>
            memory limit: <?=$tmpl['problem']->memory_limit?> byte
            <br/>
            測資數量: <?=count($tmpl['problem']->getTestdataInfo())?> 
        </div>
    </div>
    <br>
</div>