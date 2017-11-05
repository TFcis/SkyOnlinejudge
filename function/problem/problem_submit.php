<?php namespace SKYOJ\Problem;

function submitHandle()
{
    global $_G,$_E,$SkyOJ;
    try{
        $pid = $SkyOJ->UriParam(2);

        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
        {
            throw new \Exception('Access denied');
        }

        if( !$problem->isAllowSubmit($SkyOJ->User) )
        {
            if( $SkyOJ->User->uid == 0 )
                throw new \Exception('請登入後再操作');
            throw new \Exception('沒有權限');
        }

        if( \Plugin::loadClassFileInstalled('judge',$problem->judge)===false )
            throw new \Exception('Judge Not Ready!');
        $judge = new $problem->judge;
        //Get Compiler info
         /*
            this is decided by judge plugin, and select which is availible in problem setting
            key : unique id let judge plugin work(named by each judge plugin)
            val : judge info support by judge plugin
        */
        $_E['template']['problem'] = $problem;
        $_E['template']['compiler'] = $judge->get_compiler();
        $_E['template']['jscallback'] = 'location.href="'.$SkyOJ->uri('chal','result').'/"+res.data;';
        \Render::render('problem_submit','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage(),'Problem closed');
        \Render::render('nonedefined');
    }
}