<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

$page = safe_get('page');
if (!$page || !preg_match('/^[0-9]+$/', $page)) {
    $page = '1';
}

$tbstats = DB::tname('statsboard');
if (!($res = DB::query("SELECT COUNT(1) FROM `$tbstats` WHERE `state` > 0"))) {
    Render::ShowMessage('Something error...');
    exit(0);
}

$res = DB::fetch($res);
$numofAllboard = $res[0];
$rowprepage = 10;

$_E['template']['pagerange'] = $tmp = page_range($numofAllboard, $rowprepage, $page, 3);
$page = $tmp[1];
$jump = ($page - 1) * $rowprepage;
if (!($res = DB::query("SELECT `id`,`name`,`owner`
        FROM `$tbstats` WHERE `state` > 0 ORDER BY `id` DESC LIMIT $jump,$rowprepage"))) {
    Render::ShowMessage('Something error...');
    exit(0);
}
$row = [];
$user = [];
while ($data = DB::fetch($res)) {
    $data['userstatus'] = false;
    $data['userstatusinfo'] = '';
    $id = $data['id'];
    if ($_G['uid']) {
        if ($tmp = DB::loadcache("cache_board_$id")) {
            $tmp = $tmp['data'];
            if (in_array($_G['uid'], $tmp['userlist'])) {
                $data['userstatus'] = true;
                $rate = $tmp['ratemap'][$_G['uid']];
                $solved = $tmp['userdetail'][$_G['uid']]['statistics']['90'];
                $allprob = count($tmp['problems']);
                if ($allprob === 0) {
                    $data['userstatusinfo'] = '0%';
                } elseif ($allprob == $solved) {
                    $data['userstatusinfo'] = true;
                } else {
                    $num = round($solved * 100 / $allprob);
                    $data['userstatusinfo'] = "$num%";
                }
            }
        }
    }
    $user[] = $data['owner'];
    $row [] = $data;
}

nickname($user);
$_E['template']['row'] = $row;
Render::render('rank_list', 'rank');
