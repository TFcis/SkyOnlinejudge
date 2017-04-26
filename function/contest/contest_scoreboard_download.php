<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function scoreboard_downloadHandle()
{
    global $SkyOJ,$_E,$_G;
    
    try{
        $cont_id = $SkyOJ->UriParam(2);
        $contest = GetContestByID($cont_id);

        if( $contest->ispreparing() )
            throw new \Exception('Contest is preparing!');
        $data = $contest->get_scoreboard();
        $csv_string = $contest->to_csv_string($contest,$data);

        $_E['template']['csv_string'] = $csv_string;
        $_E['template']['title'] = $contest->title;
        //\Render::renderSingleTemplate('common_header');
        \Render::renderSingleTemplate('contest_scoreboard_download','contest');
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}