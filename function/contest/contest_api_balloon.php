<?php namespace SKYOJ\Contest;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function contest_api_balloonHandle()
{
    global $_G,$_E;
    try{
        set_time_limit(0);
        session_write_close();
        if( !\userControl::isAdmin($_G['uid']) )
        {
            \SKYOJ\throwjson('error', 'Access denied');
        }

        $cont_id  = \SKYOJ\safe_get_int('cont_id');
        $start    = \SKYOJ\safe_get_int('start');
        $contest = GetContestByID($cont_id);

        if( $contest->ispreparing() )
            throw new \Exception('Contest is preparing!');
        
        $try_times = 3;
        $delay = 5;
        $ac = [];
        while($try_times--)
        {
            $end = time();
            $all = $contest->get_chal_data_by_timestamp(\SKYOJ\get_timestamp($start),\SKYOJ\get_timestamp($end));
            $start = $end;
            $flag = false;
            foreach($all as $row){
                if( $row['result'] == \SKYOJ\RESULTCODE::AC ){
                    $flag = true;
                    $nickname=\SKYOJ\nickname($row['uid']);
                    $ac [] = ['team'=>$nickname[$row['uid']]];
                }
            }
            if($flag)break;
            if($try_times>=0){
                sleep($delay);
            }
        }
        \SKYOJ\throwjson('SUCC',['last'=>$end,'AC'=>$ac]);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}