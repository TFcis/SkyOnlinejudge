<?php namespace SKYOJ\Code;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle(){
    global $SkyOJ,$_E;
    $hash = $SkyOJ->UriParam(2);
    $useiframe = $SkyOJ->UriParam(3)==='iframe';

    if( !isset($hash) ){
        \Render::ShowMessage('?!?');
        exit(0);
    }
    //advance check?
    if( !GetCode($hash,namespace\CodeType::CODEPAD,$res) ){
        \Render::ShowMessage('無此資料或資料已遺失'.$hash);
        exit(0);
    }
    $_E['template']['owner'] = $res['owner'];
    //nickname($res['owner']);
    $_E['template']['defaultcode'] = $res['content'];
    $_E['template']['timestamp'] = $res['timestamp'];
    $_E['template']['hash'] = $hash;

    if ($useiframe) {
        \Render::renderSingleTemplate('code_view_iframe', 'code');
    } elseif (false) {//???
        \Render::render('code_view_al', 'code');
    } else {
        \Render::render('code_view', 'code');
    }
}
