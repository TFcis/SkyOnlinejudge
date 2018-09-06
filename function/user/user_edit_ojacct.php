<?php namespace SKYOJ\User;
use \SkyOJ\Scoreboard;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function edit_ojacctHandle(UserInfo $userInfo)
{
    $table = \DB::tname("userojacct");
    $olddata = \DB::fetchALLEx("SELECT * FROM $table WHERE uid=?",$userInfo->uid());
    $ojs = \SkyOJ\Scoreboard\OJCaptureEnum::getConstants();
    unset( $ojs[\SkyOJ\Scoreboard\OJCaptureEnum::str(\SkyOJ\Scoreboard\OJCaptureEnum::None)] );
    $plugins = [];

    $ojacctinfo = [];
    foreach( $ojs as $ojname => $ojid )
    {
        $username = \SKYOJ\safe_post("oj{$ojid}")??'';
        if( !isset($plugins[$ojid]) )
        {
            $cname = '\\SkyOJ\\Scoreboard\\Plugin\\'.$ojname;
            $plugins[$ojid] = new $cname;
        }
        if( $username!=='' && !$plugins[$ojid]->verifyAccount($username) )
        {
            \SKYOJ\throwjson('error', 'verify account no.'.$ojname.' error!');
        }
        $ojacctinfo[] = [
            'uid' => $userInfo->uid(),
            'id' => $ojid,
            'account' => $username,
            'approve' => 0
        ];
    }
    
    if ($userInfo->save_data('ojacct', $ojacctinfo)) {
        \SKYOJ\throwjson('SUCC', 'SUCC');
    } else {
        \SKYOJ\throwjson('error', 'Something error...');
    }
}
