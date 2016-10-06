<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function RankHandle()
{
    global $SkyOJ,$_E;
    require_once $_E['ROOT'].'/function/common/scoreboard.php';
    require_once $_E['ROOT'].'/function/common/problem.php';
    require_once 'rank.lib.php';

    $param = $SkyOJ->UriParam(1)??'list';
    switch( $param )
    {
        case 'list':
        case 'new':
        case 'modify':
        case 'view':
            break;

            //api
        case 'api'://cbfetch
            break;
        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/rank/rank_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
