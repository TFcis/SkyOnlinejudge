<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function problem_api_add_attachHandle()
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

        foreach($_FILES as $file)
        {
            $tmppath = $file['tmp_name'];
            $filename = $file['name'];
            if( $file['error'] != \UPLOAD_ERR_OK)
                \SKYOJ\throwjson('error', 'Upload Error : '.$file['error']);
            $problem->getFileManager()->copyin($tmppath,\SkyOJ\File\ProblemManager::ATTACH_DIR.$file['name']);
        }
        \SKYOJ\throwjson('SUCC', 'succ');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}