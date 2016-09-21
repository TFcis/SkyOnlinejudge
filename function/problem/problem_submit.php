<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function submitHandle()
{
    global $_G,$_E,$SkyOJ;
    try{
        $pid = $SkyOJ->UriParam(2);

        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( $pid===null )
            throw new \Exception('Access denied');

        if( !$problem->hasSubmitAccess($_G['uid']) )
        {
            if( $_G['uid'] == 0 )
                throw new \Exception('請登入後再操作');
            throw new \Exception('沒有權限');
        }
        $_E['template']['problem'] = $problem;

        $judge = null;
        $judgename = $problem->GetJudge();
        if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
            $judge = new $judgename;
        //Get Compiler info
         /*
            this is decided by judge plugin, and select which is availible in problem setting
            key : unique id let judge plugin work(named by each judge plugin)
            val : judge info support by judge plugin
        */
        $_E['template']['compiler'] = $judge->get_compiler();
        \Render::render('problem_submit','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage(),'Problem closed');
        \Render::render('nonedefined');
    }
}