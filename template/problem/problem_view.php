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
        <div class="col-lg-2">
            <h3><?=$tmpl['problem']->pid()?>. <?=htmlentities($tmpl['problem']->GetTitle())?></h3>
            <?php if( userControl::getpermission($tmpl['problem']->owner()) ):?>
                <hr>
                <p>
                    <a class="btn btn-primary" href="<?=$SkyOJ->uri('problem','modify',$tmpl['problem']->pid())?>">修改題目</a>
                </p>
            <?php endif;?>
        </div>
        <div class="col-lg-10">
            <?=$tmpl['problem']->GetRenderedContent()?>
        </div><!--Main end-->
    </div>
    <br>
</div>