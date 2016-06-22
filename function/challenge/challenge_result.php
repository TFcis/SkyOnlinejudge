<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

if (isset($QUEST[1])) {
    $rid = $QUEST[1];
} else {
    Render::render('nonedefined');
    exit('');
}

$table = DB::tname('challenge');
$pdo = DB::prepare("SELECT * FROM `$table` WHERE `id` = ?");
if (DB::execute($pdo, [$rid])) {
    $data = $pdo->fetchAll();
}

if (isset($data)) {
    $_E['template']['challenge_result_info'] = $data ? $data : [];
}

$resultpath = $_E['challenge']['path'].'result/'.$rid.'.json';
$resultdata = file_read($resultpath);
$resultdata = json_decode($resultdata);

$_E['template']['challenge_result_info']['result'] = $resultdata;

LOG::msg(Level::Debug, '', $data);
Render::render('challenge_result', 'challenge');
