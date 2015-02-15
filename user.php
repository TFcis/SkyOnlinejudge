<?php
require_once('GlobalSetting.php');

$allowmod =array('login','register','logout','view','edit');

//set Default mod
$mod = $_G['uid']?'view':'login';
if( isset($_REQUEST['mod']) )
{
    $mod = @$_REQUEST['mod'];
}
if( !empty($QUEST[0]) )
{
    $mod = $QUEST[0];
}
if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
    exit('');
}

else
{
    require_once($_E['ROOT']."/function/user/user.lib.php");
    $funcpath =  $_E['ROOT']."/function/user/user_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("user_$mod",'user');
    }
}

?>