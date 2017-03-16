<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
function contest_api_modifyHandle()
{
    global $_G,$_E;
    if( !\userControl::isAdmin($_G['uid']) )
        \SKYOJ\throwjson('error', 'Access denied');
    $cont_id = \SKYOJ\safe_post('cont_id');
    $title   = \SKYOJ\safe_post('title');
    $content = \SKYOJ\safe_post('content');
    $start_time = \SKYOJ\safe_post('start');
    $end_time = \SKYOJ\safe_post('end');
    $registertype = \SKYOJ\safe_post('registertype');
    $registerbegin = \SKYOJ\safe_post('registerbegin');
    $registerdelay = \SKYOJ\safe_post('registerdelay');
    $penalty = \SKYOJ\safe_post('penalty');
    $freezesec = \SKYOJ\safe_post('freezesec');
    $problems = \SKYOJ\safe_post('problems');
    if( !isset($cont_id,$title,$content))
        \SKYOJ\throwjson('error','param error');
    try{
        $contest = new \SKYOJ\Contest($cont_id);
        $cont_id = $contest->cont_id();
        if( $contest->cont_id()===null || !\userControl::getpermission($contest->owner()) )
            throw new \Exception('Access denied');
        if( !$contest->SetTitle($title) )
            throw new \Exception('title length more than limit');
        if( !$contest->SetRegisterType($registertype) )
            throw new \Exception('no such register_type');
        if( strtotime($start_time) > strtotime($end_time) )
            throw new \Exception('time range error');
        if( !$contest->SetStart($start_time) )
            throw new \Exception('modify start_time error');
        if( !$contest->SetEnd($end_time) )
            throw new \Exception('modify end_time error');
        if( !$contest->SetRegisterBegin($registerbegin) )
            throw new \Exception('modify register_begin error');
        if( !$contest->SetRegisterDelay($registerdelay) )
            throw new \Exception('modify register_delay error');
        if( !$contest->SetPenalty($penalty) )
            throw new \Exception('modify penalty error');
        if( !$contest->SetFreezeSec($freezesec) )
            throw new \Exception('modify freeze_sec error');
        if( !$contest->SetProblems($problems) )
            throw new \Exception('modify problems error');
        $contest->UpdateSQL();
        \SKYOJ\throwjson('SUCC','succ');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}
