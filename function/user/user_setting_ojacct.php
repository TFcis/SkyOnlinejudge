<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function setting_ojacctHandle(UserInfo $userInfo)
{
    global $_E;
    $table = \DB::tname("userojacct");
    $viewdata = \DB::fetchALLEx("SELECT * FROM $table WHERE uid=?",$userInfo->uid());
    $_E['template']['acct'] = $viewdata;
    $ojcaptures = \SkyOJ\Scoreboard\OJCaptureEnum::getConstants();
    unset( $ojcaptures[\SkyOJ\Scoreboard\OJCaptureEnum::str(\SkyOJ\Scoreboard\OJCaptureEnum::None)] );
    $_E['template']['ojs'] = $ojcaptures;
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
