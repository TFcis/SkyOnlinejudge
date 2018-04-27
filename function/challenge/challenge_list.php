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
    //$uid = \SKYOJ\safe_get('uid')??null;
    //$pid = \SKYOJ\safe_get('pid')??null;
    //$result = \SKYOJ\safe_get('result')??null;

    if( !preg_match('/^[1-9][0-9]*$/',$page) )
        $page = '1';

    $pl = new PageList('challenge');

    $data = $pl->GetPageDataByPage($page,'cid','*','DESC');
    $data = \SkyOJ\Challenge\Container::loadRange( ($page-1)*PageList::ROW_PER_PAGE , $page*PageList::ROW_PER_PAGE-1 );

    //LOG::msg(Level::Debug, '', $data);
    $_E['template']['challenge_list_pagelist'] = $pl;
    $_E['template']['challenge_list_now'] = $page;
    $_E['template']['challenge_info'] = $data ? $data : [];

    \Render::render('challenge_list', 'challenge');
}
