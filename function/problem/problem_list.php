<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
require_once($_E['ROOT'].'/function/common/pagelist.php');

$page = isset($QUEST[1])?make_int($QUEST[1],1):'1';
$pl = new PageList('problem');
//echo $pl->all();
$_E['template']['problem_list_pagelist'] = $pl;
$_E['template']['problem_list_now'] = $page;
Render::render("problem_list",'problem');