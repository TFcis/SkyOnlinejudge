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

if( !isset($_POST['mod']))
{
    Render::render('user_login_box','user');
    exit('');
}
else
{
    $email = safe_post('email');
    $password = safe_post('password');
    $usenickname = ( safe_post('usenickname') === "1" );
    $user; 
    if( !($user = login($email,$password,$usenickname)) )
    {
        Render::render('user_login_box','user');
        exit('');
    }
    else
    {
        userControl::SetLoginToken($user['uid']);
        header("Location:index.php");
    }
}