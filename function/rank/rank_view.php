<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle()
{
    global $SkyOJ,$_E,$_G;
    try{
        $sb_id = $SkyOJ->UriParam(2);

        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('SBID Error');

        $sb = new \SKYOJ\ScoreBoard($sb_id);
        $sb_id = $sb->sb_id();

        \SKYOJ\nickname($users = $sb->GetUsers());
        $problems = $sb->GetProblems();

        $sb->make_inline();
        $_E['template']['sb'] = $sb;
        $_E['template']['tsb'] = $sb->GetScoreBoard();
        \Render::render('rank_scoreboard', 'rank');
    } catch(\Exception $e) {
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
