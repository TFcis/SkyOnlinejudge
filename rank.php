<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');
require_once('function/pluginsCore.php');


if(!$_G['uid'] && false)
{
    Render::render('rank_index','rank');
}
else
{
    require_once($_E['ROOT']."/function/rank/rank.lib.php");
    require_once($_E['ROOT']."/function/rank/rank_commonboard.php");
}