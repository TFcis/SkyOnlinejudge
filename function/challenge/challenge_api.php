<?php namespace SKYOJ\Challenge;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $SkyOJ,$_E;

    $param = $SkyOJ->UriParam(2);
    switch( $param )
    {
        case 'waitjudge':
            break;
            
        default:
            \SKYOJ\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/challenge/challenge_api_$param.php";
    $func     = __NAMESPACE__ ."\\challenge_api_{$param}Handle";

    require_once($funcpath);
    $func();
}