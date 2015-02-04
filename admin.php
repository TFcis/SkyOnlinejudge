<?php
require_once('GlobalSetting.php');

if( !userControl::isAdmin() ) 
{
    header("Location: index.php");
    exit(0);
}

$_E['template']['syslog'] = array();
$tsyslog = DB::tname('syslog');
$res = DB::query("SELECT * FROM `$tsyslog` ORDER by `id` DESC");
while( $d = DB::fetch($res) )
{
    $_E['template']['syslog'][]=$d;
}
Render::render('admin_log','admin');