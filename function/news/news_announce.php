<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

if (isset($QUEST[1])) {
    $id = $QUEST[1];
} else {
    Render::render('nonedefined');
    exit('');
}

$tnews = DB::tname('news');
$sql = "SELECT `announce`,`title` FROM `$tnews` WHERE `id` = ?";
$pdo = DB::fetchAll($sql, [$id]);
$data['announce'] = $pdo[0]['announce'];
$data['title'] = $pdo[0]['title'];

//LOG::msg(Level::Debug, '', $data);
$_E['template']['news_announce'] = $data ? $data : [];

Render::render('news_announce', 'news');
