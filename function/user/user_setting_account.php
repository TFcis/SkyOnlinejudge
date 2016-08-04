<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function setting_accountHandle(UserInfo $userInfo)
{
    global $_E;
    $viewdata = $userInfo->load_data('account');
    $_E['template']['acct'] = $viewdata;
    \userControl::registertoken('EDIT', 3600);
    \Render::renderSingleTemplate('user_data_modify_account', 'user');
    exit(0);
}