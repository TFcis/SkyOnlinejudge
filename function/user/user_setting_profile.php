<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function setting_profileHandle(UserInfo $userInfo)
{
    global $_E;
    $viewdata = $userInfo->load_data('view');
    $_E['template'] = array_merge($_E['template'], $viewdata);
    \userControl::registertoken('EDIT', 3600);
    \Render::renderSingleTemplate('user_data_modify_profile', 'user');
    exit(0);
}