<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function modifyHandle()
{
    global $SkyOJ,$_E,$_G;

    try{
        $sb_id = $SkyOJ->UriParam(2);

        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('Access denied');

        if( !\userControl::isAdmin($_G['uid']) )
            throw new \Exception('Access denied');

        $sb = new \SKYOJ\ScoreBoard($sb_id);
        if( $sb->sb_id() === null )
        {
            throw new \Exception('沒有這一個記分板');
        }

        //TODO: getpermission

        $_E['template']['sb'] = $sb;
        $sb->GetUsers(); //Load Data
        $sb->GetProblems();
        \Render::render('rank_modify', 'rank');

    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
