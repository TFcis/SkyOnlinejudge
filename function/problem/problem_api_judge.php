<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function problem_api_judgeHandle()
{
    global $_G,$_E;
    $cid = \SKYOJ\safe_get('cid');
    try{
        $data = new \SKYOJ\Challenge\Challenge($cid);
        $res = $data->run_judge();

        if( $res === false )
            throw new \Exception('judge error');
        \SKYOJ\throwjson('SUCC',"Yeeee!");
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}