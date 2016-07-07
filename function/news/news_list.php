<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once $_E['ROOT'].'/function/common/pagelist.php';

$page = isset($QUEST[1]) ? make_int($QUEST[1], 1) : '1';
$pl = new PageList('news');
if ($page < 1 || $pl->all() < $page) {
    $page = 1;
}

$tnews = DB::tname('news');

$pdo = DB::prepare("SELECT `id`,`title`,`timestamp` FROM `$tnews` 
                    ORDER BY `id` DESC
                    LIMIT :st,:num");
$pdo->bindValue(':st', ($page - 1) * PageList::ROW_PER_PAGE, PDO::PARAM_INT);
$pdo->bindValue(':num', PageList::ROW_PER_PAGE, PDO::PARAM_INT);
if (DB::execute($pdo, null)) {
    $data = $pdo->fetchAll();
} else {
    $data = [];
}

//更新已讀
if($_G['uid'])
{
    LOG::msg(Level::Debug, 'update user seen_news');
    $taccount = DB::tname('account');
    $uid = $_G['uid'];
    $tnews = DB::tname('news');
    $sql = "SELECT * FROM `$tnews` ORDER BY `id` DESC LIMIT 1";
    $newsid = DB::fetchAll($sql);
    $newsid = $newsid[0]['id'];
    //$newsid create in template/common/common_nav.php
    $sql = "UPDATE `$taccount` SET `seen_news` = ? WHERE `uid` = ?";
    $pdo = DB::query($sql, [$newsid,$uid]);
    if(!$pdo)LOG::msg(Level::Debug, 'update user seen_news error');
}

LOG::msg(Level::Debug, '', $data);
$_E['template']['news_list_pagelist'] = $pl;
$_E['template']['news_list_now'] = $page;
$_E['template']['news_info'] = $data ? $data : [];

Render::render('news_list', 'news');
