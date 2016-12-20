<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function problem_api_submitHandle()
{
    global $SkyOJ,$_G,$_E;

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

        $judge = null;
        $judgename = $problem->GetJudge();
        if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
            $judge = new $judgename;

        if( $problem->pid()===null )
            throw new \Exception('Problem data load fail!');

        #題目權限
        if( !$problem->hasSubmitAccess($_G['uid']) )
            throw new \Exception('Access denied');
        
        //TODO
        if( $judge ){
            $compilers = $judge->get_compiler();
            if( !\array_key_exists($compiler,$compilers) )
                throw new \Exception('NoSuchJudge');
        }else if( !empty($compiler) ){
            throw new \Exception('NoSuchJudge');
        }


        if( strlen($code)>100000 )
            throw new \Exception('code length more than limit');

        $cid = \SKYOJ\Challenge\Challenge::create($uid,$pid,$code,$compiler);
        if( $cid===null )
            throw new \Exception('SQL Error');

        $SkyOJ->throwjson_keep('SUCC',$cid);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }

    //Flushed! run on back round
    try{
        $data = new \SKYOJ\Challenge\Challenge($cid);
        $res = $data->run_judge();

        if( $res === false )
        {
            //Give JE for this
            throw new \Exception('run_judge error');
        }
    }catch(\Exception $e){
        \Log::msg(\Level::Error,'judge error:'.$e->getMessage());
    }
}