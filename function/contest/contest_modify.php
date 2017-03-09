<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function modifyHandle()
{
    global $SkyOJ,$_E;

    $cont_id = $SkyOJ->UriParam(2);

    try{
        $contest = new \SKYOJ\Contest($cont_id);
        $cont_id = $contest->cont_id();

        if( $contest->cont_id()===null || !\userControl::getpermission($contest->owner()) )
            throw new \Exception('Access denied');

    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    $_E['template']['contest'] = $contest;
    \Render::render('contest_modify','contest');
}
