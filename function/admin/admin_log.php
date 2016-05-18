<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
$_E['template']['syslog'] = [];
$tsyslog = DB::tname('syslog');
$d = DB::fetchAll("SELECT * FROM `$tsyslog` ORDER by `id` DESC LIMIT 20");
if ($d !== false) {
    $_E['template']['syslog'] = $d;
}
Render::renderSingleTemplate('admin_log', 'admin');
