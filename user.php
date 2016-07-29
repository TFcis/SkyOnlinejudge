<?php namespace SKYOJ\User;


if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once 'function/user/user.lib.php';
function UserHandle()
{
    global $SkyOJ,$_E,$_G;
    $param = $SkyOJ->UriParam(1)??($_G['uid']?'view':'login');

    switch( $param )
    {
        //api
        case 'edit':
            break;

        case 'login':
        case 'logout':
        case 'view':
        case 'register':
            break;

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/user/user_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
