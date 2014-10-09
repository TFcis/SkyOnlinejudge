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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user; 
    if( !($user = login($email,$password)) )
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