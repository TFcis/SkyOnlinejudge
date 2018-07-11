<?php namespace SKYOJ\Problem;

use \SkyOJ\Judge\Judge;

function problem_api_judgeHandle()
{
    global $SkyOJ,$_G,$_E;
    try
    {
        $cid = \SKYOJ\safe_get('cid');

        session_write_close(); // prevent stuck
        if( !$SkyOJ->User->isAdmin() )
            \SKYOJ\throwjson('error', 'Access denied');
        
        
        $data = new \SkyOJ\Challenge\Container();
        if( !$data->load($cid) )
            \SKYOJ\throwjson('error', 'Load Chal Error');

        $judge = Judge::getJudgeReference($data->problem()->judge_profile);

        $res = '';
        if( isset($judge) )
        {
            $res = $judge->judge($data);
        }

        $data->applyResult($res);
        if( $res === false )
            throw new \Exception('judge error');
        \SKYOJ\throwjson('SUCC',"Yeeee!");
    }
    catch(\Exception $e)
    {
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}