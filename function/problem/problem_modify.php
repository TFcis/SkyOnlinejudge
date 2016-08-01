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
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }

    $_E['template']['problem'] = $problem;
    \Render::render('problem_modify','problem');
}