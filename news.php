<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

$allowmod = ['list','announce'];

//set Default mod
$mod = empty($QUEST[0]) ? 'list' : $QUEST[0];

if (!in_array($mod, $allowmod)) {
    Render::render('nonedefined');
    exit('');
} else {
    //require_once $_E['ROOT'].'/function/news/news.lib.php';
    $funcpath = $_E['ROOT']."/function/news/news_$mod.php";
    if (file_exists($funcpath)) {
        require $funcpath;
    } else {
        Render::render("news_$mod", 'news');
    }
}
