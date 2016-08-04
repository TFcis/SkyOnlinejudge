<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function edit_accountHandle(UserInfo $userInfo)
{
    $oldpass = \SKYOJ\safe_post('oldpasswd');

    $newpass  =\SKYOJ\safe_post('newpasswd');
    $realname =\SKYOJ\safe_post('realname');

    $data = $userInfo->load_data('account');
    
    if ( empty($data) || !password_verify($oldpass,$data['passhash'])) {
        \SKYOJ\throwjson('error', 'Worng Old Password');
    }

    //Change Old Password    
    if (isset($newpass)) {
        if (!CheckPasswordFormat($newpass)) {
            \SKYOJ\throwjson('error', 'Password format error!');
        }
        $data['passhash'] = GetPasswordHash($newpass);
    }

    //change Realname
    if( !empty($realname) ) {
        $realname = trim($realname);
        if (strlen($realname) > 9) {
            \SKYOJ\throwjson('error', 'Realname 太長');
        }
        $data['realname'] = $realname;
    }

    if ( !$userInfo->save_data('account', $data) ) {
        \SKYOJ\throwjson('error', 'Server error. Cannot save data!');
    }
    \SKYOJ\throwjson('SUCC', 'modify');
}