<?php namespace SKYOJ\Problem;


if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//URI Format
/*
    /problem/list/[page] ? quest str (default)
    /problem/view/[PID] 
    /pboblem/new
    /problem/modify/[PID]
    /problem/api/[method]
*/
require_once 'function/problem/problem.lib.php';
require_once 'function/common/contest.php';
function ProblemHandle()
{
    global $SkyOJ,$_E;
    $param = $SkyOJ->UriParam(1)??('list');

    switch( $param )
    {
        case 'list':
        case 'new' :
        case 'modify':
        case 'view':
        case 'submit':
            break;

        //api
        case 'api':
            break;

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/problem/problem_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
