<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $SkyOJ,$_E;

    $param = $SkyOJ->UriParam(2);
    switch( $param )
    {
        case 'balloon':
        case 'bangkok_results_before':
        case 'bangkok_results_final':
            break;
            
        default:
            \SKYOJ\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/contest/contest_api_$param.php";
    $func     = __NAMESPACE__ ."\\contest_api_{$param}Handle";

    require_once($funcpath);
    $func();
}