<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

$page = isset($_GET['page'])?$_GET['page']:'1';
if(!preg_match('/^[0-9]+$/',$page))
{
    $page = '1';
}

$tbstats = DB::tname('statsboard');
if(! ($res = DB::query("SELECT COUNT(1) FROM `$tbstats`") ))
{
    Render::ShowMessage('Something error...');
    exit('');
}

$res = DB::fetch($res);
$numofAllboard = $res[0];
$rowprepage = 10;

$_E['template']['pagerange'] = $tmp =page_range($numofAllboard,$rowprepage,$page,3);
$page = $tmp[1];
$jump = ($page-1)*$rowprepage;
if(! ($res = DB::query("SELECT * FROM `$tbstats` ORDER BY `id` LIMIT $jump,$rowprepage") ))
{
    Render::ShowMessage('Something error...');
    exit('');
}
$row = array();
$user = array();
while($data = DB::fetch($res))
{
    $row[] = $data;
    $user[]= $data['owner'];
}

$nickname = DB::getuserdata('account',$user,'`uid`,`nickname`');
$_E['template']['row']=$row;
$_E['template']['nickname'] = array();
foreach($user as $uid)
{
    $_E['template']['nickname'][$uid] = $nickname[(string)$uid]['nickname'];
}
Render::render("rank_list",'rank');

