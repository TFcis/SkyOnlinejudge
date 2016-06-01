<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once $_E['ROOT'].'/function/common/pagelist.php';

$page = isset($QUEST[1]) ? make_int($QUEST[1], 1) : '1';
$pl = new PageList('challenge');
if ($page < 1 || $pl->all() < $page) {
    $page = 1;
}

$tproblem = DB::tname('challenge');

$pdo = DB::prepare("SELECT `id`,`problem`,`user`,`result`,`time` FROM `$tproblem` 
                    ORDER BY `id` 
                    LIMIT :st,:num");
$pdo->bindValue(':st', ($page - 1) * PageList::ROW_PER_PAGE, PDO::PARAM_INT);
$pdo->bindValue(':num', PageList::ROW_PER_PAGE, PDO::PARAM_INT);
if (DB::execute($pdo, null)) {
    $data = $pdo->fetchAll();
} else {
    $data = [];
}

LOG::msg(Level::Debug, '', $data);
$_E['template']['challenge_list_pagelist'] = $pl;
$_E['template']['challenge_list_now'] = $page;
$_E['template']['challenge_info'] = $data ? $data : [];

Render::render('challenge_list', 'challenge');
