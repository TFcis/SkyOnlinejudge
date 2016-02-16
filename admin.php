<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');

if( !userControl::isAdmin() ) 
{
    header("Location: index.php");
    exit(0);
}

$_E['template']['syslog'] = array();
$tsyslog = DB::tname('syslog');
$d = DB::fetchAll("SELECT * FROM `$tsyslog` ORDER by `id` DESC LIMIT 20");
if( $d !== false )
{
    $_E['template']['syslog'] = $d;
}

Render::render('admin_log','admin');