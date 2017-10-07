<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function setting_ojacctHandle(UserInfo $userInfo)
{
    global $_E;
    $viewdata = $userInfo->load_data('ojacct');
    $_E['template']['acct'] = $viewdata;
    $_E['template']['ojs'] = list_oj_column();
    $ojacct= [];
    foreach( $viewdata as $row )
    {
        $ojacct[$row['id']] = $row['account'];
    }
    $_E['template']['ojacct'] = $ojacct;
    \userControl::registertoken('EDIT', 3600);
    \Render::renderSingleTemplate('user_data_modify_ojacct', 'user');
    exit(0);
}
