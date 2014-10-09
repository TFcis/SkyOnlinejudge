<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');


$allowmod =array('login','register','logout','view');

//set Default mod
$mod = $_G['uid']?'view':'login';

if( isset($_REQUEST['mod']) )
{
    $mod = @$_REQUEST['mod'];
}

if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
}
else
{
    require_once($_E['ROOT']."/function/user/user.lib.php");
    if(!file_exists($_E['ROOT']."/function/user/user_$mod.php"))
    {
        Render::render("user_$mod",'user');
    }
    else
    {
        require($_E['ROOT']."/function/user/user_$mod.php");
    }
}

?>