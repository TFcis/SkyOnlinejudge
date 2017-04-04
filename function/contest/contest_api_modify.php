<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
function contest_api_modifyHandle()
{
    global $_G,$_E;
    
    try{
        if( !\userControl::isAdmin($_G['uid']) )
            \SKYOJ\throwjson('error', 'Access denied');
        $cont_id = \SKYOJ\safe_post_int('cont_id');
        $title   = \SKYOJ\safe_post('title');
        $content = \SKYOJ\safe_post('content');

        $starttime = \SKYOJ\safe_post('start');
        $endtime  = \SKYOJ\safe_post('end');

        $registertype  = \SKYOJ\safe_post_int('registertype');
        $registerbegin = \SKYOJ\safe_post_int('registerbegin');
        $registerdelay = \SKYOJ\safe_post_int('registerdelay');

        $penalty   = \SKYOJ\safe_post_int('penalty');
        $freezesec = \SKYOJ\safe_post_int('freezesec');
        $class = \SKYOJ\safe_post('class');

        $problems  = \SKYOJ\safe_post('problems');

        if( !isset($cont_id,$title,$content))
            \SKYOJ\throwjson('error','param error');

        $contest = GetContestByID($cont_id);
        $cont_id = $contest->cont_id();
        if( $contest->isIdfail() || !\userControl::getpermission($contest->owner) )
            throw new \Exception('Access denied');

        $contest->title = $title;
        $contest->register_type = $registertype;
        
        if( strtotime($starttime) > strtotime($endtime) )
            throw new \Exception('time range error');
        $contest->starttime = $starttime;
        $contest->endtime = $endtime;

        $contest->register_beginsec = $registerbegin;
        $contest->register_delaysec = $registerdelay;

        $contest->penalty = $penalty;
        $contest->freeze_sec = $freezesec;
        $contest->class = $class;

        $contest->problems = $problems;

        $contest->UpdateSQL();
        \SKYOJ\throwjson('SUCC','succ');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}
