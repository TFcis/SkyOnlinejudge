<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function scoreboardHandle()
{
    global $SkyOJ,$_E,$_G;
    
    try{
        $cont_id = $SkyOJ->UriParam(2);
        $contest = GetContestByID($cont_id);

        if( $contest->ispreparing() )
            throw new \Exception('Contest is preparing!');
        $data = $contest->get_scoreboard();

        $_E['template']['contest'] = $contest;
        $_E['template']['user'] = $data['userinfo'];
        $_E['template']['pids'] = $data['probleminfo'];
        $_E['template']['scoreboard'] = $data['scoreboard'];
        \Render::render('view_scoreboard_acm','contest');
        exit(0);
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}