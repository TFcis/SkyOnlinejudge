<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');


$allowmod =array('login','loginbox');

$mod = '';
if( isset($_POST['mod']) )
{
  $mod = @$_POST['mod'];
}
elseif( isset($_GET['mod']) && $_GET['mod']!='login')
{
  $mod=@$_GET['mod'];
}
if($mod == '' )
{
  $mod = 'loginbox';
}

if( !in_array($mod,$allowmod) )
{
  render('nonedefine');
}
else
{
  if(!file_exists("$_E[ROOT]/function/user_$mod.php"))
  {
    render("user/user_$mod");
  }
  else
  {
    reuqire("$_E[ROOT]/function/user_$mod.php");
  }
}

?>