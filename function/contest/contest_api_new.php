<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function contest_api_newHandle()
{
    global $_G,$_E;

    if( !\userControl::isAdmin($_G['uid']) )
        \SKYOJ\throwjson('error', 'Access denied');

    $title   = \SKYOJ\safe_post('title');

    if( strlen($title)>\SKYOJ\Contest::TITLE_LENTH_MAX )
        \SKYOJ\throwjson('error', 'Title out of lenth');

    $cont_id = CreateNewContestID($_G['uid'],$title);
    if( $cont_id === null )
        \SKYOJ\throwjson('error', 'SQL Server Error');

    \SKYOJ\throwjson('SUCC', $cont_id);
}