<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}


function ContestHandle()
{
    global $SkyOJ,$_E;
    require_once $_E['ROOT'].'/function/common/contest.php';
    require_once $_E['ROOT'].'/function/common/problem.php';

    $param = $SkyOJ->UriParam(1)??'list';
    switch( $param )
    {
        case 'view':
        case 'register':
        case 'scoreboard':
        case 'list':
            break;
        
        case 'api'://cbfetch
            break;
        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/contest/contest_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
