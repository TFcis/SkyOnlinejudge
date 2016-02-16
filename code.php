<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');

$allowmod =array('codepad','view','submit');

if( empty($QUEST[0]) )
{
    header("Location:".$_E['SITEROOT']."code.php/codepad");
    exit(0);
}
if( $_E['Codepad']['enabled'] == false )
{
    Render::ShowMessage("Codepad Closed QQ");
    exit(0);
}
//set Default mod
if( !in_array($QUEST[0],$allowmod) )
{
    Render::render('nonedefined');
    exit(0);
}
else
{
    $mod = $QUEST[0];
    $funcpath =  $_E['ROOT']."/function/code/code_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("code_$mod",'code');
    }
}

?>