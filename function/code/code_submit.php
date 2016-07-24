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
        \throwjson('error', 'Access denied');
    }

    $code = \safe_post('code');

    if (empty($code)) {
        var_dump($code,$_POST);
        \throwjson('error', 'Empty Code!');
    }
    if (($s = strlen($code)) > $_E['Codepad']['maxcodelen']) {
        \throwjson('error', 'Code Too LONG! :'.$s);
    }

    $hash=namespace\PutCode($code,namespace\CodeType::CODEPAD,$_G['uid']);

    if( $hash!== false)
        \throwjson('SUCC', $hash);
    else
        \throwjson('error', 'DB FULL');
}
