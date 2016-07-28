<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function editHandle()
{
    global $SkyOJ,$_E;
    if( !\userControl::CheckToken('EDIT') )
        \SKYOJ\throwjson('error', 'Access denied');

    $param = $SkyOJ->UriParam(2);
    $euid = \SKYOJ\safe_post('id');

    if( !CheckUidFormat($euid) )
        \SKYOJ\throwjson('error', 'Access denied');
    $user = new UserInfo($euid);

    // for admin test!
    if ( !$user->is_registed() || !\userControl::getpermission($euid))
        \SKYOJ\throwjson('error', 'not admin or owner');
    $userInfo = new UserInfo($euid);

    switch( $param )
    {
        case 'quote':
        case 'account':
            break;
        default:
            \SKYOJ\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/user/user_edit_$param.php";
    $func     = __NAMESPACE__ ."\\edit_{$param}Handle";

    require_once($funcpath);
    $func($userInfo);
}
