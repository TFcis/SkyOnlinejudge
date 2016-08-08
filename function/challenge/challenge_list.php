<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once $_E['ROOT'].'/function/common/pagelist.php';

$page = isset($QUEST[1]) ? \SKYOJ\make_int($QUEST[1], 1) : '1';
$searchpid = isset($_GET['pid']) ? \SKYOJ\make_int($_GET['pid'], -1) : '%';
$searchuid = isset($_GET['uid']) ? \SKYOJ\make_int($_GET['uid'], -1) : '%';
/*\LOG::msg(Level::Debug, 'pid:', $searchpid);
\LOG::msg(Level::Debug, 'uid:', $searchuid);*/
if($searchpid==-1 || $searchuid==-1){
    \LOG::msg(Level::Warning, 'search error(make_int error)');
    $searchpid='%';
    $searchuid='%';
}
$pl = new PageList('challenge');
if ($page < 1 || $pl->all() < $page) {
    $page = 1;
}

$tproblem = DB::tname('challenge');

$pdo = DB::prepare("SELECT `id`,`problem`,`user`,`result`,`time` FROM `$tproblem` 
                    WHERE `problem` LIKE '$searchpid' AND `user` LIKE '$searchuid' 
                    ORDER BY `id` 
                    LIMIT :st,:num");
$pdo->bindValue(':st', ($page - 1) * PageList::ROW_PER_PAGE, PDO::PARAM_INT);
$pdo->bindValue(':num', PageList::ROW_PER_PAGE, PDO::PARAM_INT);
if (DB::execute($pdo, null)) {
    $data = $pdo->fetchAll();
} else {
    $data = [];
}
if($searchpid==='%'){
    $searchpid=-1;
}
if($searchuid==='%'){
    $searchuid=-1;
}
/*\LOG::msg(Level::Debug, 'pid(ch):', $searchpid);
\LOG::msg(Level::Debug, 'uid(ch):', $searchuid);*/

//\LOG::msg(Level::Debug, '', $data);
$_E['template']['challenge_list_pagelist'] = $pl;
$_E['template']['challenge_list_now'] = $page;
$_E['template']['challenge_info'] = $data ? $data : [];
$_E['template']['challenge_pid'] = $searchpid;
$_E['template']['challenge_uid'] = $searchuid;

Render::render('challenge_list', 'challenge');
