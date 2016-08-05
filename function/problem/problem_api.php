<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $SkyOJ,$_E;

    $param = $SkyOJ->UriParam(2);
    switch( $param )
    {
        case 'new':
        case 'modify':
            break;
            
        default:
            \SKYOJ\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/problem/problem_api_$param.php";
    $func     = __NAMESPACE__ ."\\problem_api_{$param}Handle";

    require_once($funcpath);
    $func();
}