<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

if (!userControl::CheckToken('CODEPAD_EDIT') || !isset($_POST['code'])) {
    throwjson('error', 'Access denied');
}

if ($_G['uid'] == 0 && $_E['Codepad']['allowguestsubmit'] == false) {
    throwjson('error', 'Access denied');
}

$code = safe_post('code');

if (empty($code)) {
    throwjson('error', 'Empty Code!');
}
if (($s = strlen($code)) > $_E['Codepad']['maxcodelen']) {
    throwjson('error', 'Code Too LONG! :'.$s);
}

$table = DB::tname('codepad');
$times = 10;

do {
    $times--;
    $hash = GenerateRandomString(8);
    $uid = $_G['uid'];
    $res = DB::query("INSERT INTO $table (`id`, `owner`, `hash`,`timestamp`,`content`) 
                                VALUES (NULL,?,?,NULL,?)", [$uid, $hash, $code]);
} while (!$res && $times > 0);

if ($times <= 0) {
    throwjson('error', 'DB FULL');
}
throwjson('SUCC', $hash);
