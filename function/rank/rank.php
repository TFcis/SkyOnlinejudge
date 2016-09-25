<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
//require_once 'function/user/user.lib.php';
require_once 'rank.lib.php';
function RankHandle()
{
    global $SkyOJ,$_E;
    $param = $SkyOJ->UriParam(1)??'list';
    switch( $param )
    {
        case 'list':
        case 'commonboard':
        case 'edit':
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
