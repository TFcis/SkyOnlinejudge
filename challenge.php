<?php namespace SKYOJ\Challenge;


if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//URI Format
/*
    /chal/list/[page] ? quest str (default)
*/
require_once 'function/challenge/challenge.lib.php';
function ChallengeHandle()
{
    global $SkyOJ,$_E;
    $param = $SkyOJ->UriParam(1)??('list');

    switch( $param )
    {
        case 'list':
        case 'result':
            break;
        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/challenge/challenge_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
