<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function sub_scoreboardHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;    
    $data = $contest->get_scoreboard();

    $_E['template']['user'] = $data['userinfo'];
    $_E['template']['pids'] = $data['probleminfo'];
    $_E['template']['scoreboard'] = $data['scoreboard'];
    $tmpl = $contest->scoreboard_template();
    \Render::renderSingleTemplate($tmpl[0],$tmpl[1]);
    exit(0);
}