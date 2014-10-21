<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
//no login
if(!$_G['uid'])
{
    $_E['template']['alert'].="權限不足";
    include('rank_list.php');
    exit('');
}

$default = array();
$default['name']='';
$default['owner']=$_G['uid'];
$default['userlist']='';
$default['problems']='';
$default['state']='1';
$default['id']='0';

if( !isset($_GET['id']) )
{
    //new
    $_E['template']['title'] = 'NEW!';
    $_E['template']['form'] = $default;
}
else
{
    $id = $_GET['id'];
    if(!($setting = getCBdatabyid($id)))
    {
        $_E['template']['alert'].="沒有這一個記分板";
        include('rank_list.php');
        exit('');
    }
    if( $setting['owner']!=$_G['uid'] )
    {
        $_E['template']['alert'].="權限不足";
        include('rank_list.php');
        exit('');
    }
    $_E['template']['title'] = $setting['name'];
    $_E['template']['form'] = $setting;
}
userControl::registertoken('CBEDIT',900);
Render::render('rank_cbedit','rank');