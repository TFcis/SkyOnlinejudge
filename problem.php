<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');

//URI Format
/*
    /problem/list/[page] ? quest str (default)
    /pboblem/new
    /problem/[PID]
    /problem/[PID]/modify
*/
$allowmod =array('list');

//set Default mod
$mod = empty($QUEST[0])?'list':$QUEST[0];

if( !in_array($mod,$allowmod) )
{
    Render::render('nonedefined');
    exit('');
}
else
{
    require_once($_E['ROOT']."/function/problem/problem.lib.php");
    $funcpath =  $_E['ROOT']."/function/problem/problem_$mod.php";
    if(file_exists($funcpath))
    {
        require($funcpath);
    }
    else
    {
        Render::render("problem_$mod",'problem');
    }
}