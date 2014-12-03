<?php
require_once('GlobalSetting.php');
require_once('function/user/user.lib.php');
$allowmod =array('list','commonboard','cbedit','edit','cbfetch');
$mod = isset($_REQUEST['mod'])?$_REQUEST['mod']:'list';

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