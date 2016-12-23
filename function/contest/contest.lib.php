<?php namespace SKYOJ\Contest;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function GetContestByID($cont_id)
{
    if( !\SKYOJ\check_tocint($cont_id) )
        throw new \Exception('CONT_ID Error');
    $contest = new \SKYOJ\Contest($cont_id);
    if( $contest->isIdfail() )
        throw new \Exception('CONT_ID Error');
    return $contest;
}