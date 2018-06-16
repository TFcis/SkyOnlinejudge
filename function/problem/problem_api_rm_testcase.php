<?php namespace SKYOJ\Problem;

function problem_api_rm_testcaseHandle()
{
    global $_G,$_E,$SkyOJ;

    try{
        if( !$SkyOJ->User->isAdmin() )
            \SKYOJ\throwjson('error', 'Access denied');

        $pid = \SKYOJ\safe_get('pid')??0;
        $problem = new \SkyOJ\Problem\Container();

        if( !$problem->load($pid) )
            \SKYOJ\throwjson('error','param error');
        if( !$problem->writeable($SkyOJ->User) )
            \SKYOJ\throwjson('error', 'Access denied');

        $problem->getDataManager()->cleanTestdata();
        \SKYOJ\throwjson('SUCC',"succ");
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}