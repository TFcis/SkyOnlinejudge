<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');
require_once('function/mysqlCore.php');

$allowmod =array('loginbox','register');

$mod = '';
if( isset($_POST['mod']) )
{
    $mod = @$_POST['mod'];
}
elseif( isset($_GET['mod']) && $_GET['mod']!='login')
{
    $mod = @$_GET['mod'];
}
if($mod == '' )
{
    $mod = 'loginbox';
}

if( !in_array($mod,$allowmod) )
{
    $Render->render('nonedefined');
}
else
{
    require_once($_E['ROOT']."/function/user/user.lib.php");
    if(!file_exists($_E['ROOT']."/function/user/user_$mod.php"))
    {
        $Render->render("user_$mod",'user');
    }
    else
    {
        require($_E['ROOT']."/function/user/user_$mod.php");
    }
}

?>