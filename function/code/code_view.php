<?php

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( !isset($QUEST[1]) )
{
    Render::ShowMessage('?!?');
}

$hash = $QUEST[1];
if( !preg_match('/[a-z0-9]{8}/',$hash))
{
    Render::ShowMessage('error hash');
    exit(0);
}
$table = DB::tname('codepad');

$res = DB::query("SELECT `filename`,`owner`,`timestamp` FROM `$table` WHERE hash = '$hash'");
if( !$res )
{
    Render::ShowMessage('error hash!');
    exit(0);
}
$res = DB::fetch($res);
$storgepath = $_E['ROOT'].'/data/codepad/';
$fullname = $storgepath.$res['filename'];
if( !file_exists($fullname) )
{
    Render::ShowMessage('QQ code missing'.$fullname);
    exit(0);
}
$_E['template']['owner'] = $res['owner'];
nickname($res['owner']);
$_E['template']['defaultcode'] = file_get_contents($fullname);
$_E['template']['timestamp'] = $res['timestamp'];
Render::render('code_view','code');
//Render::ShowMessage('?!?x');