<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

if (!userControl::isAdmin()) {
    header('Location: index.php');
    exit(0);
}
//plugins
$_E['template']['sysplugins'] = [];
$_E['template']['syspluginsstats'] = [];
$path = ['rank/board_other_oj', 'user/login'];
foreach ($path as $p) {
    $classes = Plugin::loadClassFileByFolder($p);
    $data = Plugin::checkInstall($classes);
    $_E['template']['sysplugins'][$p] = $data ? $data : [];
}

$_E['template']['syslog'] = [];
$tsyslog = DB::tname('syslog');
$d = DB::fetchAll("SELECT * FROM `$tsyslog` ORDER by `id` DESC LIMIT 20");
if ($d !== false) {
    $_E['template']['syslog'] = $d;
}

Render::render('admin_main', 'admin');
