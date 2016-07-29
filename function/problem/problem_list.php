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

    //TODO : It's very ugly. Make it perfect.
    $pl = new PageList('problem');
    $tproblem = \DB::tname('problem');

    $pdo = \DB::prepare("SELECT `pid`,`owner`,`title`
                         FROM `{$tproblem}` 
                         ORDER BY `pid` 
                         LIMIT :st,:num");
    $pdo->bindValue(':st', ($page - 1) * PageList::ROW_PER_PAGE, \PDO::PARAM_INT);
    $pdo->bindValue(':num', PageList::ROW_PER_PAGE, \PDO::PARAM_INT);

    if ( \DB::execute($pdo, null) ) {
        $data = $pdo->fetchAll();
    } else {
        $data = [];
    }

    \Log::msg(\Level::Debug, 'Problem List', $data);
    $_E['template']['problem_list_pagelist'] = $pl;
    $_E['template']['problem_list_now'] = $page;
    $_E['template']['problem_info'] = $data ? $data : [];
    \Render::render('problem_list', 'problem');
}
