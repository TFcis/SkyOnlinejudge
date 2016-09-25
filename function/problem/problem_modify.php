<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function modifyHandle()
{
    global $SkyOJ,$_E;

    $pid = $SkyOJ->UriParam(2);

    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( $problem->pid()===null || !\userControl::getpermission($problem->owner()) )
            throw new \Exception('Access denied');

         $judges_info = \Plugin::listInstalledClassFileByFolder('judge');
         $judges = [];

         $judges['empty'] = '';
         if( !empty($problem->GetJudge()) && !isset($judges_info[$problem->GetJudge()]) )
         {
             $judges['default(Not Availible)'] = $problem->judge();
         }

         foreach( $judges_info as $data )
         {
            $class = $data['class'];
            $judges[$class] = $class;
         }
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    $_E['template']['pjson'] = @file_get_contents($_E['DATADIR']."problem/{$pid}/{$pid}.json");
    $_E['template']['problem'] = $problem;
    $_E['template']['judges'] = $judges;
    \Render::render('problem_modify','problem');
}