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
    $contenttype = \SKYOJ\safe_post('contenttype');

    if( !isset($pid,$title,$content,$contenttype) )
        \SKYOJ\throwjson('error','param error');
    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();
        if( $problem->pid()===null || !\userControl::getpermission($problem->owner()) )
            throw new \Exception('Access denied');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }

    if( strlen($title) > \SKYOJ\Problem::TITLE_LENTH_MAX )
        \SKYOJ\throwjson('error','title length more than limit');

    $problem->SetTitle($title);
    $problem->SetRowContent($content);
    $problem->Update();

    \SKYOJ\throwjson('SUCC','succ');
}