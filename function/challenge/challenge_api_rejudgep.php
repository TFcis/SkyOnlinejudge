<?php namespace SKYOJ\Challenge;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function challenge_api_rejudgepHandle()
{
    global $_G,$_E;
    $pid = \SKYOJ\safe_get('pid');
    $tchallenge = \DB::tname('challenge');
    set_time_limit(0);
    ignore_user_abort(true);
    session_write_close();

    try{
        \Log::msg(\Level::Notice,"Start Rejudge Pid:",$pid);

        #Load Problem
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();
        if( $problem->pid()===null )
            throw new \Exception('Load Problem Error');
        
        //Set All problem to waiting
        \DB::queryEx("UPDATE `{$tchallenge}` SET `result` = ? WHERE `pid` = ?",\SKYOJ\RESULTCODE::WAIT,$pid);

        //GET All CID
        $cids = \DB::fetchAllEx("SELECT `cid` FROM `{$tchallenge}` WHERE `pid` = ?",$pid);
        if( $cids===false )
            throw new \Exception('Load Chall Error');
        
        //Rejudge
        foreach( $cids as $data )
        {
            $cid = $data['cid'];
            $d = new \SKYOJ\Challenge\Challenge($cid);
            $res = $d->run_judge();
        }
        \SKYOJ\throwjson('SUCC',true);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}