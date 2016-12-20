<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h3><?=$tmpl['problem']->pid()?>. <?=\SKYOJ\html($tmpl['problem']->GetTitle())?></h3>
        </div>
        <div class="col-md-3 text-right">
            <p><?=array_search($tmpl['problem']->GetJudgeType(),SKYOJ\ProblemJudgeTypeEnum::getConstants())?> Judge</p>
            <p>Open(Stats)</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <?=$tmpl['problem']->GetRenderedContent()?>
        </div><!--Main end-->
    </div>
    <br>
</div>