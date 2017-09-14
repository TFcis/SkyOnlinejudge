<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once $_E['ROOT'].'/function/common/scoreboard.php';
function edit_ojacctHandle(UserInfo $userInfo)
{
    $olddata = $userInfo->load_data('ojacct');
    $ojs = list_oj_column();

    \SKYOJ\ScoreBoard::plugin_init();
    $plugins = \SKYOJ\ScoreBoard::$plugins;
    
    $ojacctinfo = [];
    foreach( $ojs as $oj )
    {
        $username = \SKYOJ\safe_post("oj{$oj['id']}")??'';
        $ojacctinfo[] = [
            'uid' => $userInfo->uid(),
            'id' => $oj['id'],
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