<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function modifyHandle()
{
    global $SkyOJ,$_E;
    
    try{
        $cont_id = $SkyOJ->UriParam(2);
        $contest = new \SKYOJ\Contest($cont_id);

        if( $contest->isIdfail() || !\userControl::getpermission($contest->owner) )
            throw new \Exception('Access denied');
        $_E['template']['contest'] = $contest;
        \Render::render('contest_modify','contest');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
