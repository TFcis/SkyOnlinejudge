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
        $resolver = false;
        if($SkyOJ->UriParam(3)=='resolver'){
            $resolver = true;
        }

        if( $contest->ispreparing() )
            throw new \Exception('Contest is preparing!');
        $data = $contest->get_scoreboard();

        $_E['template']['contest'] = $contest;
        $_E['template']['user'] = $data['userinfo'];
        $_E['template']['pids'] = $data['probleminfo'];
        $_E['template']['scoreboard'] = $data['scoreboard'];
        $tmpl = $contest->scoreboard_template($resolver);
        if(isset($tmpl[2])){
            if($tmpl[2]=='resolver'){
                \Render::renderSingleTemplate('common_header');
                \Render::renderSingleTemplate($tmpl[0],$tmpl[1]);
            }
        }
        else{
            \Render::render($tmpl[0],$tmpl[1]);
        }
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}