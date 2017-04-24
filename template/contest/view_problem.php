<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?=$tmpl['problem']->GetRenderedContent()?>
        </div>
    </div>
    <br>
</div>