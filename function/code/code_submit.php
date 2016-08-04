<?php namespace SKYOJ\Code;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function submitHandle()//api
{
    global $_G,$_E;
    /*if ( !\userControl::CheckToken('CODEPAD_EDIT') ) {
        \throwjson('error', 'Access denied');
    }*/
    if ( $_G['uid'] == 0 && $_E['Codepad']['allowguestsubmit'] == false ) {
        \SKYOJ\throwjson('error', 'Access denied');
    }

    $code = \SKYOJ\safe_post('code');

    if (empty($code)) {
        var_dump($code,$_POST);
        \SKYOJ\throwjson('error', 'Empty Code!');
    }
    if (($s = strlen($code)) > $_E['Codepad']['maxcodelen']) {
        \SKYOJ\throwjson('error', 'Code Too LONG! :'.$s);
    }

    $hash=namespace\PutCode($code,namespace\CodeType::CODEPAD,$_G['uid']);

    if( $hash!== false)
        \SKYOJ\throwjson('SUCC', $hash);
    else
        \SKYOJ\throwjson('error', 'DB FULL');
}
