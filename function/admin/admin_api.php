<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $SkyOJ,$_E;
    $param = $SkyOJ->UriParam(2);
    
    switch( $param )
    {
            //api
        case 'CheckAdminToken':
        case 'GetAdminToken':
            (__NAMESPACE__.'\\api'.$param)();
            \SKYOJ\NeverReach();
            break;

        case 'PluginInstall':
        case 'PluginUninstall':
		case 'NewJudgeProfile':
            checkToken(true);//use json
            break;

        default:
            \SKYOJ\throwjson('error', 'Access denied');
    }

    $funcpath = $_E['ROOT']."/function/admin/admin_api_$param.php";
    $func     = __NAMESPACE__ ."\\admin_api_{$param}Handle";

    require_once($funcpath);
    $func();
}

function apiCheckAdminToken()
{
    $token = \SKYOJ\safe_post('token');

    if( CheckAdminToken($token) ){
        \SKYOJ\throwjson('SUCC',$token);
    }else{
        \SKYOJ\throwjson('error','Access denied');
    }
}

/*
    if success, apiGetAdminToken will return a token 
    else it will return a key for next time
*/
function apiGetAdminToken()
{
    global $_G,$_E;
    require_once $_E['ROOT'].'/function/common/encrypt.php';
    
    $password = \SKYOJ\safe_post('password');
    $key      = \SKYOJ\safe_post('key');

    $status = 'error';
    $msg = '密碼錯誤';

    if( isset($password) ){
        $data = \userControl::getuserdata('account',[$_G['uid']],['passhash']);
        if( isset($data[$_G['uid']]) ){
            $passReal = $data[$_G['uid']]['passhash'];
            if( password_verify($password,$passReal) ){
                $status = 'SUCC';
            }
        }
    }
    if( $status == 'SUCC' ){
        $token = \userControl::RegisterToken('ADMIN_CSRF', 3600, false);
        \SKYOJ\throwjson('SUCC',$token);
    }else{
        \SKYOJ\throwjson('error',$msg);
    }
}