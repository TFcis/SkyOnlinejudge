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
    Render::setbodyclass('loginbody');
    Render::render('user_login_box','user');
    exit(0);
}
else // API CALL
{
    $email = safe_post('email');
    $password = safe_post('password');
    $user = login($email,$password);
    if( !$user[0]  )
    {
        $_E['template']['alert'] = $user[1];
        LOG::msg(Level::Notice,"<$email> want to login but fail.(".$user[1].")");
        throwjson('error',$user[1]);
    }
    else
    {
        $user = $user[1];
        userControl::SetLoginToken($user['uid']);
        throwjson('SUCC','index.php');
    }
}