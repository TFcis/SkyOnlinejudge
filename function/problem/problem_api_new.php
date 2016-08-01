<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function problem_api_newHandle()
{
    global $_G,$_E;

    if( !\userControl::isAdmin($_G['uid']) )
        \SKYOJ\throwjson('error', 'Access denied');

    $title   = \SKYOJ\safe_post('title');
    $default = \SKYOJ\safe_post('default');

    if( !\SKYOJ\ProblemDescriptionEnum::isValidValue($default) )
        \SKYOJ\throwjson('error', 'No Such Type');
    if( strlen($title)>\SKYOJ\Problem::TITLE_LENTH_MAX )
        \SKYOJ\throwjson('error', 'Title out of lenth');

    $pid = CreateNewProblemID($_G['uid'],$title);
    if( $pid === null )
        \SKYOJ\throwjson('error', 'SQL Server Error');
    
    if( !\SKYOJ\Problem::CreateDefault($pid) )
        \SKYOJ\throwjson('error', 'Server DATA Creat Error!');

    \SKYOJ\throwjson('SUCC', $pid);
}