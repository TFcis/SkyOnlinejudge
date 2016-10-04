<?php namespace SKYOJ\Rank;

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function listHandle()
{
    global $SkyOJ,$_E;
   
    $page = $SkyOJ->UriParam(2)??'1';

    if( !preg_match('/^[1-9][0-9]*$/',$page) )
        $page = '1';

    $pl = new PageList('scoreboard');
    $data = $pl->GetPageDataByPage($page,'sb_id');

    $_E['template']['scoreboard_list_pagelist'] = $pl;
    $_E['template']['scoreboard_list_now'] = $page;
    $_E['template']['scoreboard_info'] = $data ? $data : [];
    
    \Render::render('rank_list', 'rank');
}