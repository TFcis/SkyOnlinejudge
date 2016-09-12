<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function problem_api_submitHandle()
{
    global $_G,$_E;

    //TODO : 題目權限
    if( !$_G['uid'] )
        \SKYOJ\throwjson('error', 'Access denied');
    
    $pid = \SKYOJ\safe_post('pid');
    $compiler = \SKYOJ\safe_post('compiler');
    $code = \SKYOJ\safe_post('code');
    $uid = $_G['uid'];

    if( !isset($pid,$compiler,$code) )
        \SKYOJ\throwjson('error','param error');
    
    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( $problem->pid()===null )
            throw new \Exception('Access denied');
        
        //TODO
        if( $compiler !== 'cpp11' )
            throw new \Exception('NoSuchJudge');

        if( strlen($code)>100000 )
            throw new \Exception('code length more than limit');

        $cid = \SKYOJ\Challenge\Challenge::create($uid,$pid,$code,$compiler);
        if( $cid===null )
            throw new \Exception('SQL Error');

        $data = new \SKYOJ\Challenge\Challenge($cid);
        $data->run_judge();
        \SKYOJ\throwjson('SUCC',$cid);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}