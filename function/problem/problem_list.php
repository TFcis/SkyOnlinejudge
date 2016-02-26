<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
require_once($_E['ROOT'].'/function/common/pagelist.php');

$page = isset($QUEST[1])?make_int($QUEST[1],1):'1';
$pl = new PageList('problem');
if( $page<1 || $pl->all()<$page )$page=1;

$tproblem = DB::tname('problem');

$pdo = DB::prepare("SELECT `pid`,`owner`,`title` FROM `$tproblem` 
                    ORDER BY `pid` 
                    LIMIT :st,:num");
$pdo->bindValue(':st' ,($page-1)*PageList::ROW_PER_PAGE,PDO::PARAM_INT);
$pdo->bindValue(':num',PageList::ROW_PER_PAGE          ,PDO::PARAM_INT);
if( DB::execute($pdo,null) )
{
    $data=$pdo->fetchAll();
}
else
{
    $data = array();
}

LOG::msg(Level::Debug,"",$data);
$_E['template']['problem_list_pagelist'] = $pl;
$_E['template']['problem_list_now'] = $page;
$_E['template']['problem_info'] = $data?$data:array();
Render::render("problem_list",'problem');