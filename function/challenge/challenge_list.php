<?php namespace SKYOJ\Challenge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function listHandle()
{
    global $SkyOJ,$_E;
    $page = $SkyOJ->UriParam(2)??'1';

    if( !preg_match('/^[1-9][0-9]*$/',$page) )
        $page = '1';

    $pl = new PageList('challenge');
    $data = $pl->GetPageDataByPage($page,'cid','*','DESC');
    $pids = [];
    foreach($data as $row)
        $pids[] = $row['pid'];

    //LOG::msg(Level::Debug, '', $data);
    $_E['template']['challenge_list_pagelist'] = $pl;
    $_E['template']['challenge_list_now'] = $page;
    $_E['template']['challenge_info'] = $data ? $data : [];
    $_E['template']['challenge_prob'] = \userControl::getuserdata('problem',$pids,['owner','content_access'],'pid');

    \Render::render('challenge_list', 'challenge');
}
