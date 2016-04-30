<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');

$allowmod =array('list');

//set Default mod
$mod = empty($QUEST[0])?'list':$QUEST[0];

if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
    exit('');
}
else
{
    $funcpath =  $_E['ROOT']."/function/challenge/challenge_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("challenge_$mod",'challenge');
    }
}
?>