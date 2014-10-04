<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');


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
    render('nonedefined');
}
else
{
    ;
    if(!file_exists($_E['ROOT']."/function/user/user_$mod.php"))
    {
        render("user/user_$mod");
    }
    else
    {
        require($_E['ROOT']."/function/user/user_$mod.php");
    }
}

?>