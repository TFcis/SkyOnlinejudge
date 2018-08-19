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

        $sb = new \SkyOJ\Scoreboard\ScoreBoard();
        if( !$sb->load($sb_id) )
            throw new \Exception('Load Scoreboard error!');

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
