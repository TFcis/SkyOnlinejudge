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
if( !preg_match('/[A-Za-z0-9]{8}/',$hash))
{
    Render::ShowMessage('error hash');
    exit(0);
}

$table = DB::tname('codepad');

$res = DB::fetch("SELECT `owner`,`timestamp`,`content` FROM `{$table}` WHERE hash =?",array($hash));
if( !$res )
{
    Render::ShowMessage('無此資料或資料已遺失'.$hash);
    exit(0);
}

$_E['template']['owner'] = $res['owner'];
//nickname($res['owner']);
$_E['template']['defaultcode'] = $res['content'];
$_E['template']['timestamp'] = $res['timestamp'];
$_E['template']['hash'] = $hash;

if( isset($QUEST[2]) && $QUEST[2]=='iframe' )
{
    Render::renderSingleTemplate('code_view_iframe','code');
}
elseif( isset($QUEST[2]) && $QUEST[2]=='al' )
{
    Render::render('code_view_al','code');
}
else
{
    Render::render('code_view','code');
}