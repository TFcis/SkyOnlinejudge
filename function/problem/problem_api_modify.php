<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function problem_api_modifyHandle()
{
    global $_G,$_E,$SkyOJ;

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
    //$pjson = \SKYOJ\safe_post('json_data');
    //\Log::msg(\Level::Debug,"",$pjson);
    
    try{
        if( !isset($pid,$title,$content,$contenttype) )
            throw new \Exception('param error');

        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('NO SUCH PROBLEM');
        
        if( !$problem->isAllowEdit($SkyOJ->User) )
            throw new \Exception('Access denied');

        $problem->title = $title;
        $problem->judge = $judge;
        $problem->content_access = (int)$content_access;
        $problem->submit_access = (int)$submit_access;
        $problem->codeview_access = (int)$codeview_access;
        $problem->setContent($content,(int)$contenttype);

        //file_put_contents($_E['DATADIR']."problem/{$pid}/{$pid}.json",$pjson);
        $problem->save();
        \SKYOJ\throwjson('SUCC','succ');

    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}