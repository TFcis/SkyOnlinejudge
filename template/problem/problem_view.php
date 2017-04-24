<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_DIV;
use \SKYOJ\HTML_INPUT_SELECT;
use \SKYOJ\HTML_INPUT_BUTTOM;
use \SKYOJ\HTML_INPUT_HIDDEN;
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h3><?=$tmpl['problem']->pid()?>. <?=\SKYOJ\html($tmpl['problem']->GetTitle())?></h3>
        </div>
        <div class="col-md-3 text-right">
            <p><?=array_search($tmpl['problem']->GetJudgeType(),SKYOJ\ProblemJudgeTypeEnum::getConstants())?> Judge</p>
            <p>Code: <?=array_search($tmpl['problem']->GetCodeviewAccess(),SKYOJ\ProblemCodeviewAccessEnum::getConstants())?></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2">
            <div class="container-fluid">
                <div class="row">
                    <?php if($tmpl['problem']->hasSubmitAccess($_G['uid'])): ?>
                    <p class="col-md-12 col-sm-3">
                        <a class="btn btn-success btn-block hidden-xs" href="<?=$SkyOJ->uri('problem','submit',$tmpl['problem']->pid())?>">送出</a>
                    </p>
                    <?php endif;?>
                    <p class="col-md-12 col-sm-3">
                        <a class="btn btn-primary btn-block" href="<?=$SkyOJ->uri('chal').'?pid='.$tmpl['problem']->pid()?>">本題狀態</a>
                    </p>
                    <p class="col-md-12 col-sm-3">
                        <a class="btn btn-primary btn-block" href="#">統計</a>
                    </p>
                    <!--<p class="col-md-12 col-sm-3">
                        <a class="btn btn-primary btn-block hidden-xs" href="#">列印</a>
                    </p>-->
                </div>
                <hr>
                <?php if( userControl::getpermission($tmpl['problem']->owner()) ):?>
                <div class="row hidden-xs">
                    <p class="col-md-12 col-sm-3">
                        <a class="btn btn-warning btn-block" href="<?=$SkyOJ->uri('problem','modify',$tmpl['problem']->pid())?>">修改題目</a>
                    </p>
                </div>
                <hr>
                <?php endif;?>
            </div>
        </div>
        <div class="col-md-10">
            <?=$tmpl['problem']->GetRenderedContent()?>
        </div><!--Main end-->
    </div>
    <br>
</div>