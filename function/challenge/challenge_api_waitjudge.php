<?php namespace SKYOJ\Challenge;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function challenge_api_waitjudgeHandle()
{
    global $_G,$_E;
    $cid = \SKYOJ\safe_get('cid');
    $tchallenge = \DB::tname('challenge');
    $Times = 30;
    $Wait = 1;//< Second
    try{
        set_time_limit(0);
        session_write_close();
        
        while( $Times-- )
        {
            $res = \DB::fetchEx("SELECT `result` FROM `{$tchallenge}` WHERE `cid` = ?",$cid);
            if( $res === false )
            {
                throw new \Exception('SQL Error');
            }
            if( $res['result'] >= 1) //AC
            {
                \SKYOJ\throwjson('SUCC',false);
            }
            sleep($Wait);
        }
        \SKYOJ\throwjson('SUCC',true);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}