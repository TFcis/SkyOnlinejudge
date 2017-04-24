<?php namespace SKYOJ\Contest;

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function listHandle()
{
    global $SkyOJ,$_E;
   
    $page = $SkyOJ->UriParam(2)??'1';

    if( !preg_match('/^[1-9][0-9]*$/',$page) )
        $page = '1';

    $pl = new PageList('contest');
    $data = $pl->GetPageDataByPage($page,'cont_id','*','DESC');

    $_E['template']['contest_list_pagelist'] = $pl;
    $_E['template']['contest_list_now'] = $page;
    $_E['template']['contest_info'] = $data ? $data : [];
    
    \Render::render('contest_list', 'contest');
}