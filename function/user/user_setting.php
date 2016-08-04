<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function settingHandle(UserInfo $userInfo)//Permission Checked
{
    global $SkyOJ,$_E,$_G;
    $page = $SkyOJ->UriParam(4);
    
    if( empty($page) ){
        $page = $_SESSION['QUEST4']??'profile';
        unset($_SESSION['QUEST4']);
        if( !preg_match('/^[a-z]+$/',$page) )
            $page = 'profile';
        $_E['template']['setting']['defaultpage'] = $page;
        \Render::renderSingleTemplate('user_setting', 'user');
        exit(0);
    }

    switch ($page) {
        case 'profile':
        case 'account':
        case 'mycodepad':
            break;
        default:
            \Render::renderSingleTemplate('nonedefined');
            exit(0);
    }
    $funcpath = $_E['ROOT']."/function/user/user_setting_$page.php";
    $func     = __NAMESPACE__ ."\\setting_{$page}Handle";

    require_once($funcpath);
    $func($userInfo);
}