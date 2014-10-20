<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');
require_once('function/pluginsCore.php');
require_once('function/user/user.lib.php');
$allowmod =array('list','commonboard');
$mod = isset($_GET['mod'])?$_GET['mod']:'commonboard';

if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
    exit('');
}
else
{
    require_once($_E['ROOT']."/function/rank/rank.lib.php");
    $funcpath =  $_E['ROOT']."/function/rank/rank_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("rank_$mod",'rank');
    }
}