<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'function/common/problem.php';


function ProblemSubmitNum(int $pid ,int $uid = 0,bool $update = false):int
{
    static $val = [];
    static $table;
    if( !isset($val[$uid]) ) $val[$uid] = [];
    if( isset($val[$uid][$pid]) && !$update )return $val[$uid][$pid];
    if( !isset($table) ) $table = \DB::tname('challenge');

    if( $uid!==0 )
        $d = \DB::fetchEx("SELECT COUNT(*) FROM `{$table}` WHERE `uid`=? AND `pid`=? ",$uid,$pid);
    else
        $d = \DB::fetchEx("SELECT COUNT(*) FROM `{$table}` WHERE `pid`=? ",$pid);

    return $d===false ? 0 : ($val[$uid][$pid] = $d[0]);
}

function ProblemStateNum(int $pid ,int $state,int $uid = 0,bool $update = false):int
{
    static $val = [];
    static $table;
    if( !isset($val[$uid]) ) $val[$uid] = [];
    if( isset($val[$uid][$pid]) && !$update )return $val[$uid][$pid];
    if( !isset($table) ) $table = \DB::tname('challenge');

    if( $uid!==0 )
        $d = \DB::fetchEx("SELECT COUNT(*) FROM `{$table}` WHERE `uid`=? AND `pid`=? AND `result`=?",$uid,$pid,$state);
    else
        $d = \DB::fetchEx("SELECT COUNT(*) FROM `{$table}` WHERE `pid`=? AND `result`=?",$pid,$state);

    return $d===false ? 0 : ($val[$uid][$pid] = $d[0]);
}

function UserProblemState(int $pid ,int $uid,bool $update = false):int
{
    static $val = [];
    static $table;
    if( !isset($val[$uid]) ) $val[$uid] = [];
    if( isset($val[$uid][$pid]) && !$update )return $val[$uid][$pid];
    if( !isset($table) ) $table = \DB::tname('challenge');

    $d = \DB::fetchEx("SELECT MIN(`result`) FROM `{$table}` WHERE `uid`=? AND `pid`=?",$uid,$pid);

    return $d===false || !isset($d[0]) ? 0 : ($val[$uid][$pid] = $d[0]);
}
