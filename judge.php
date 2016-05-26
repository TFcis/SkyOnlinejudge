<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');
require_once('function/socket.php');
require_once('function/challenge/challenge.lib.php');

$allowmod =array('test');

//set Default mod
$mod = empty($QUEST[0])?'list':$QUEST[0];

if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
    exit('');
}
else
{
    require_once($_E['ROOT']."/function/judge/judge.lib.php");
    $funcpath =  $_E['ROOT']."/function/judge/judge_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("judge_$mod",'judge');
    }
}
?>