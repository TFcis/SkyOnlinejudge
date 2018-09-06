<?php namespace SKYOJ\Problem;

function problem_api_add_testcasezipHandle()
{
    global $_G,$_E,$SkyOJ;

    try{
        if( !$SkyOJ->User->isAdmin() )
            \SKYOJ\throwjson('error', 'Access denied');

        $pid = \SKYOJ\safe_post('pid');
        $problem = new \SkyOJ\Problem\Container();

        if( !$problem->load($pid) )
            \SKYOJ\throwjson('error','param error');
        if( !$problem->writeable($SkyOJ->User) )
            \SKYOJ\throwjson('error', 'Access denied');
        
        $file = $_FILES['file']??['error'=>1];
        if( $file['error'] != \UPLOAD_ERR_OK)
            \SKYOJ\throwjson('error', 'Upload Error : '.$file['error']);

        $problem->getDataManager()->copyTestcasesZip($file['tmp_name']);
        \SKYOJ\throwjson('SUCC',"succ");
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}