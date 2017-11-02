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

    //var_dump(\SkyOJ\Problem\Container::loadRange(1,10));
    $pl = new PageList('problem');
    $data = $pl->GetPageDataByPage($page,'pid','`pid`,`owner`,`title`,`content_access`,`submit_access`');

    foreach($data as &$row) #TODO Write a new PageList
    {
        $t = new \SkyOJ\Problem\Container();
        $t->loadByData($row);
        $row = $t;
    }

    $_E['template']['problem_list_pagelist'] = $pl;
    $_E['template']['problem_list_now'] = $page;
    $_E['template']['problem_info'] = $data ? $data : [];
    \Render::render('problem_list', 'problem');
}
