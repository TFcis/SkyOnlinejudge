<?php namespace SKYOJ\Challenge;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function challenge_api_modify_commentHandle()
{
    global $_G,$_E;
    $tchallenge = \DB::tname('challenge');

    try{
        $cid = \SKYOJ\safe_post_int('cid');
        $result = \SKYOJ\safe_post_int('result');
        $comment = \SKYOJ\safe_post('comment');
        #Load Challenge
        $chal = new \SKYOJ\Challenge\Challenge($cid);
        if( is_null($chal->cid) )
        {
            throw new \Exception('No such challenge!');
        }

        $chal->set_comment($comment);
        if( !$chal->set_result($result) )
        {
            throw new \Exception('Update data fail!');
        }
        \SKYOJ\throwjson('SUCC',true);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}