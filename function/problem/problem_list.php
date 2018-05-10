<?php namespace SKYOJ\Problem;
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

    $pl = new PageList('problem');
    $data = $pl->GetPageDataByPage($page,'pid','`pid`,`owner`,`title`,`content_access`,`submit_access`');
    $data = \SkyOJ\Problem\Container::loadRange( ($page-1)*PageList::ROW_PER_PAGE , $page*PageList::ROW_PER_PAGE-1 );

    $_E['template']['problem_list_pagelist'] = $pl;
    $_E['template']['problem_list_now'] = $page;
    $_E['template']['problem_info'] = $data ? $data : [];
    \Render::render('problem_list', 'problem');
}
