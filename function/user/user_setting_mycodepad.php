<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function setting_mycodepadHandle(UserInfo $userInfo)
{
    //WAIT FOR PRESYSTEM
    global $_G,$_E;

    $codepad = \DB::tname('codepad');
    if (\userControl::isAdmin($userInfo->uid()) && $userInfo->uid() == $_G['uid']) {
        $rowdata = \DB::fetchAll("SELECT `id`,`hash`,`timestamp` FROM `$codepad`");
    } else {
        $rowdata = \DB::fetchAllEx("SELECT `id`,`hash`,`timestamp` FROM `$codepad` WHERE `owner` = ?",$userInfo->uid());
    }

    if (!$rowdata) {
        $rowdata = [];
    }

    $_E['template']['row'] = $rowdata;
    \Render::renderSingleTemplate('user_data_modify_mycodepad', 'user');
    exit(0);
}