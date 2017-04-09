<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function problem_api_modifyHandle()
{
    global $_G,$_E;

    if( !\userControl::isAdmin($_G['uid']) )
        \SKYOJ\throwjson('error', 'Access denied');

    $pid = \SKYOJ\safe_post('pid');
    $title   = \SKYOJ\safe_post('title');
    $content = \SKYOJ\safe_post('content');
    $judge   = \SKYOJ\safe_post('judge')??'';
    $judge_type   = \SKYOJ\safe_post('judge_type');
    $contenttype  = \SKYOJ\safe_post('contenttype');
    $content_access = \SKYOJ\safe_post('content_access');
    $submit_access  = \SKYOJ\safe_post('submit_access');
    $codeview_access  = \SKYOJ\safe_post('codeview_access');
    $pjson = \SKYOJ\safe_post('json_data');
    \Log::msg(\Level::Debug,"",$pjson);
    if( !isset($pid,$title,$content,$contenttype) )
        \SKYOJ\throwjson('error','param error');
    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();
        if( $problem->pid()===null || !\userControl::getpermission($problem->owner()) )
            throw new \Exception('Access denied');

        if( !$problem->SetTitle($title) )
            throw new \Exception('title length more than limit');
        if( !$problem->SetJudge($judge) )
            throw new \Exception('no such judge');
        if( !$problem->SetJudgeType($judge_type) )
            throw new \Exception('no such judge type');
        if( !$problem->SetContentAccess($content_access) )
            throw new \Exception('no such ContentAccess type');
        if( !$problem->SetSubmitAccess($submit_access) )
            throw new \Exception('no such SubmitAccess type');
        if( !$problem->SetCodeviewAccess($codeview_access) )
            throw new \Exception('no such CodeviewAccess type');
        $problem->SetRowContent($content);

        file_put_contents($_E['DATADIR']."problem/{$pid}/{$pid}.json",$pjson);
        $problem->Update();
        \SKYOJ\throwjson('SUCC','succ');

    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}