<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function problem_api_modifyHandle()
{
    global $_G,$_E,$SkyOJ;
  
    try{
        $pid = \SKYOJ\safe_post('pid');
        $title   = \SKYOJ\safe_post('title');
        $content = \SKYOJ\safe_post('content');
        $judge_profile   = \SKYOJ\safe_post('judge_profile');
        $content_type  = \SKYOJ\safe_post('content_type');
        $runtime_limit = \SKYOJ\safe_post('runtime_limit');
        $memory_limit = \SKYOJ\safe_post('memory_limit');
        $content_access = \SKYOJ\safe_post('content_access');
        $submit_access  = \SKYOJ\safe_post('submit_access');
        $codeview_access  = \SKYOJ\safe_post('codeview_access');
        $judgejson = \SKYOJ\safe_post('json_data');
        //\Log::msg(\Level::Debug,"",$pjson);
    
        if( !isset($pid,$title,$content,$content_type) )
            throw new \Exception('param error');

        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('NO SUCH PROBLEM');
        
        if( !$problem->writeable($SkyOJ->User) )
            throw new \Exception('Access denied');

        $problem->title = $title;
        $problem->judge_profile = (int)$judge_profile;
        $problem->runtime_limit = $runtime_limit;
        $problem->memory_limit =  $memory_limit;
        $problem->content_access = (int)$content_access;
        $problem->submit_access = (int)$submit_access;
        $problem->codeview_access = (int)$codeview_access;
        $problem->setContent($content,(int)$content_type);
        $problem->setJudgeJson($judgejson);

        //file_put_contents($_E['DATADIR']."problem/{$pid}/{$pid}.json",$pjson);
        $problem->save();
        \SKYOJ\throwjson('SUCC','succ');

    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}