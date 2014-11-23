<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( $_G['uid'] )
{
    Render::ShowMessage("你不是登入了!?");
    exit('');
}

if( !isset($_POST['mod']) )
{
    Render::render('user_login_box','user');
    exit(0);
}
else // API CALL
{
    $email = safe_post('email');
    $password = safe_post('password');
    $usenickname = ( safe_post('usenickname') === "1" );
    $user = login($email,$password,$usenickname);
    if( !$user[0]  )
    {
        $_E['template']['alert'] = $user[1];
        throwjson('error',$user[1]);
    }
    else
    {
        $user = $user[1];
        userControl::SetLoginToken($user['uid']);
        throwjson('SUCC','index.php');
    }
}