<?php namespace SKYOJ\Code;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function codepadHandle(){
    global $_G,$_E;

    if ($_G['uid'] == 0 && $_E['Codepad']['allowguestsubmit'] == false) {
        \Render::ShowMessage('Only for registed user now!');
        exit(0);
    }

    \userControl::RegisterToken('CODEPAD_EDIT', 3600);
    \Render::render('code_codepad', 'code');
}


