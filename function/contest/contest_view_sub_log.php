<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function sub_logHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;
    $tname = \DB::tname('challenge');
    $tpid  = \DB::tname('contest_problem');
    $chals = \DB::fetchAllEx("SELECT * FROM $tname WHERE `uid`=? AND `timestamp` BETWEEN ? AND ? AND `pid` IN (
        SELECT `pid` FROM `$tpid` WHERE `cont_id`=?
    ) ORDER BY `cid` DESC", $_G['uid'],$contest->starttime,$contest->endtime,$contest->cont_id());
    $_E['template']['challenge_info'] = $chals;
    \Render::renderSingleTemplate('view_submit_log','contest');
    exit(0);
}